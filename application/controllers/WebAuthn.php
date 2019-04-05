<?php

class Webauthn extends _SiteController {
	private $appid;

	/**
	 * construct object on which to operate
	 *
	 * @param string $appid a string identifying your app, typically the domain of your website which people
	 *                      are using the key to log in to. If you have the URL (ie including the
	 *                      https:// on the front) to hand, give that;
	 *                      if it's not https, well what are you doing using this code?
	 */
	function __construct() {
		parent::__construct();
		$this->load->model( 'login_model' );
		$this->appid = "hydrofiel.test";
	}

	/**
	 * cancel all keys for a user
	 * @return string to store as the user's webauthn field in your database
	 */
	function cancel() {
		return '';
	}

	public function get_registration_challenge() {
		$this->output->enable_profiler( FALSE );
		header("Content-Type: application/json");

		$id = $this->session->id;

		// Just to make sure.
		if( ! isset( $id ) ) {
			return FALSE;
		}

		$user = $this->profile_model->get_profile_array( $id );

		echo $this->prepare_challenge_for_registration( $user["naam"], $user["id"] );
	}

	/**
	 * generate a challenge ready for registering a hardware key, fingerprint or whatever:
	 *
	 * @param $username string by which the user is known potentially displayed on the hardware key
	 * @param $userid   string by which the user can be uniquely identified. Don't use email address as this can change,
	 *                  user perhaps the database record id
	 *
	 * @return string pass this JSON string back to the browser
	 */
	function prepare_challenge_for_registration( $username, $userid ) {
		$result             = (object) [];
		$rbchallenge        = random_bytes( 16 );
		$result->challenge  = self::string_to_array( $rbchallenge );
		$result->user       = (object) [];
		$result->user->name = $result->user->displayName = $username;
		$result->user->id   = self::string_to_array( $userid );

		$result->rp       = (object) [];
		$result->rp->name = $result->rp->id = $this->appid;

		$result->pubKeyCredParams          = [];
		$result->pubKeyCredParams[0]       = (object) [];
		$result->pubKeyCredParams[0]->alg  = - 7;
		$result->pubKeyCredParams[0]->type = 'public-key';

		$result->authenticatorSelection = (object) [];
		// $result->authenticatorSelection->authenticatorAttachment = 'cross-platform';
		/* that line would restrict to external devices, not including built-in ones like integral fingerprint reader */

		$result->authenticatorSelection->requireResidentKey = FALSE;
		$result->authenticatorSelection->userVerification   = 'preferred';

		$result->attestation        = NULL;
		$result->timeout            = 60000;
		$result->excludeCredentials = []; // No excludeList
		$result->extensions         = (object) [];
		$result->extensions->exts   = TRUE;

		return json_encode( [
			'publicKey'    => $result,
			'b64challenge' => rtrim( strtr( base64_encode( $rbchallenge ), '+/', '-_' ), '=' ),
		] );
	}

	/**
	 * registers a new key for a user
	 * requires info from the hardware via javascript given below
	 *
	 * @param string $info         supplied to the PHP script via a POST, constructed by the Javascript given below,
	 *                             ultimately provided by the key
	 * @param string $userwebauthn the exisitng webauthn field for the user from your
	 *                             database (it's actaully a JSON string, but that's entirely internal to
	 *                             this code)
	 *
	 * @return string modified to store in the user's webauthn field in your database
	 */
	function register() {
		$this->output->enable_profiler( FALSE );
		$user_id = $this->session->id;

		$user = $this->profile_model->get_profile_array( $user_id );

		$userwebauthn = $user["webauthn"];

		$info = $this->input->post( 'register', TRUE);

		if( ! is_string( $info ) ) {
			$this->oops( 'info must be a string', 1 );
		}
		$info = json_decode( $info );
		if( empty( $info ) ) {
			$this->oops( 'info is not properly JSON encoded', 1 );
		}
		if( empty( $info->response->attestationObject ) ) {
			$this->oops( 'no attestationObject in info', 1 );
		}
		if( empty( $info->rawId ) ) {
			$this->oops( 'no rawId in info' );
		}

		/* check response from key and store as new identity. This is a hex string representing the raw CBOR
		   attestation object received from the key */

		$aos = self::array_to_string( $info->response->attestationObject );
		$ao  = (object) ( \CBOR\CBOREncoder::decode( $aos ) );
		if( empty( $ao ) ) {
			$this->oops( 'cannot decode key response (1)' );
		}
		if( empty( $ao->fmt ) ) {
			$this->oops( 'cannot decode key response (2)' );
		}
		if( empty( $ao->authData ) ) {
			$this->oops( 'cannot decode key response (3)' );
		}
		$bs = $ao->authData->get_byte_string();

		if( $ao->fmt == 'fido-u2f' ) {
			$this->oops( "cannot decode FIDO format responses, sorry" );
		}
		elseif( $ao->fmt != 'none' ) {
			$this->oops( 'cannot decode key response (4)' );
		}

		$ao->rpIdHash = substr( $bs, 0, 32 );
		$ao->flags    = ord( substr( $bs, 32, 1 ) );
		$ao->counter  = substr( $bs, 33, 4 );

		$hashId = hash( 'sha256', $this->appid, TRUE );
		if( $hashId != $ao->rpIdHash ) {
			//log(bin2hex($hashId).' '.bin2hex($ao->rpIdHash));
			$this->oops( 'cannot decode key response (5)' );
		}

		if( ! ( $ao->flags & 0x41 ) ) {
			$this->oops( 'cannot decode key response (6)' );
		} /* TUP and AT must be set */

		$ao->attData            = (object) [];
		$ao->attData->aaguid    = substr( $bs, 37, 16 );
		$ao->attData->credIdLen = ( ord( $bs[53] ) << 8 ) + ord( $bs[54] );
		$ao->attData->credId    = substr( $bs, 55, $ao->attData->credIdLen );
		$cborPubKey             = substr( $bs, 55 + $ao->attData->credIdLen ); // after credId to end of string

		$ao->pubKey = \CBOR\CBOREncoder::decode( $cborPubKey );
		if( ! isset( $ao->pubKey[1] /* cose_kty */ ) ) {
			$this->oops( 'cannot decode key response (7)' );
		}
		if( ! isset( $ao->pubKey[3] /* cose_alg */ ) ) {
			$this->oops( 'cannot decode key response (8)' );
		}
		if( ! isset( $ao->pubKey[ - 1 ] /* cose_crv */ ) ) {
			$this->oops( 'cannot decode key response (9)' );
		}
		if( ! isset( $ao->pubKey[ - 2 ] /* cose_crv_x */ ) ) {
			$this->oops( 'cannot decode key response (10)' );
		}
		if( ! isset( $ao->pubKey[ - 3 ] /* cose_crv_y */ ) ) {
			$this->oops( 'cannot decode key response (11)' );
		}
		if( $ao->pubKey[1] != 2 /* cose_kty_ec2 */ ) {
			$this->oops( 'cannot decode key response (12)' );
		}
		if( $ao->pubKey[3] != - 7 /* cose_alg_ECDSA_w_SHA256 */ ) {
			$this->oops( 'cannot decode key response (13)' );
		}
		if( $ao->pubKey[ - 1 ] != 1 /* cose_crv_P256 */ ) {
			$this->oops( 'cannot decode key response (14)' );
		}

		/* assemblePublicKeyBytesData */
		$x = $ao->pubKey[ - 2 ]->get_byte_string();
		$y = $ao->pubKey[ - 3 ]->get_byte_string();
		if( strlen( $x ) != 32 || strlen( $y ) != 32 ) {
			$this->oops( 'cannot decode key response (15)' );
		}
		$ao->attData->keyBytes = chr( 4 ) . $x . $y;

		$rawId = self::array_to_string( $info->rawId );
		if( $ao->attData->credId != $rawId ) {
			$this->oops( 'cannot decode key response (16)' );
		}

		$publicKey      = (object) [];
		$publicKey->key = $this->pubkey_to_pem( $ao->attData->keyBytes );
		$publicKey->id  = $info->rawId;
		//log($publicKey->key);

		if( empty( $userwebauthn ) ) {
			$userwebauthn = [ $publicKey ];
		}
		else {
			$userwebauthn = json_decode( $userwebauthn );
			$found        = FALSE;
			foreach( $userwebauthn as $idx => $key ) {
				if( implode( ',', $key->id ) != implode( ',', $publicKey->id ) ) {
					continue;
				}
				$userwebauthn[ $idx ]->key = $publicKey->key;
				$found                     = TRUE;
				break;
			}
			if( ! $found ) {
				array_unshift( $userwebauthn, $publicKey );
			}
		}
		$userwebauthn = json_encode( $userwebauthn );

		$this->profile_model->update( $user["id"], [ "webauthn" => $userwebauthn ] );

		echo $userwebauthn;
	}

	/**
	 * generates a new key string for the physical key, fingerprint
	 * reader or whatever to respond to on login
	 *
	 * @param string $userwebauthn the existing webauthn field for the user from your database
	 *
	 * @return string to pass to javascript webauthnAuthenticate
	 */
	function prepare_for_login(){
		$this->output->enable_profiler( FALSE );

		$email = $this->input->post( 'email', TRUE );

		$user = $this->profile_model->get_user_by_email( $email );

		$userwebauthn = $user["webauthn"];

		$allow             = (object) [];
		$allow->type       = 'public-key';
		$allow->transports = [ 'usb', 'nfc', 'ble' ];
		$allow->id         = NULL;
		$allows            = [];
		if( ! empty( $userwebauthn ) ) {
			foreach( json_decode( $userwebauthn ) as $key ) {
				$allow->id = $key->id;
				$allows[]  = clone $allow;
			}
		}
		else {
			/* including empty user, so they can't tell whether the user exists or not (need same result each
			   time for each user) */
			// log("fabricating key");
			$allow->id = [];
			$rb        = md5( (string) time() );
			$allow->id = self::string_to_array( $rb );
			$allows[]  = clone $allow;
		}

		/* generate key request */
		$publickey                           = (object) [];
		$publickey->challenge                = self::string_to_array( random_bytes( 16 ) );
		$publickey->timeout                  = 60000;
		$publickey->allowCredentials         = $allows;
		$publickey->userVerification         = 'preferred';
		$publickey->extensions               = (object) [];
		$publickey->extensions->txAuthSimple = 'Execute order 66';
		$publickey->rpid                     = str_replace( 'https://', '', $this->appid );

		echo json_encode( $publickey );
	}

	/**
	 * validates a response for login or 2fa
	 * requires info from the hardware via javascript given below
	 *
	 * @param string $info         supplied to the PHP script via a POST, constructed by the Javascript given below,
	 *                             ultimately provided by the key
	 * @param string $userwebauthn the exisiting webauthn field for the user from your
	 *                             database (it's actaully a JSON string, but that's entirely internal to
	 *                             this code)
	 *
	 * @return boolean TRUE for valid authentication or FALSE for failed validation
	 */
	function authenticate() {
		$this->output->enable_profiler( FALSE );

		$info  = $this->input->post( 'auth', TRUE );
		$email = $this->input->post( 'email', TRUE );

		$user = $this->profile_model->get_user_by_email( $email );

		$userwebauthn = $user["webauthn"];

		if( ! is_string( $info ) ) {
			$this->oops( 'info must be a string', 1 );
		}

		$info = json_decode( $info );

		if( empty( $info ) ) {
			$this->oops( 'info is not properly JSON encoded', 1 );
		}
		$webauthn = empty( $userwebauthn ) ? [] : json_decode( $userwebauthn );

		/* find appropriate key from those stored for user (usually there'll only be one, but if
		   someone has, say, a physical yubikey on desktop and fingerprint reader on mobile, for
		   example, may be more than one. */
		$key = NULL;
		foreach( $webauthn as $candkey ) {
			if( implode( ',', $candkey->id ) != implode( ',', $info->rawId ) ) {
				continue;
			}
			$key = $candkey;
			break;
		}
		if( empty( $key ) ) {
			$this->oops( "no key with id " . implode( ',', $info->rawId ) );
		}

		/* cross-check challenge */
		$oc = rtrim( strtr( base64_encode( self::array_to_string( $info->originalChallenge ) ), '+/', '-_' ), '=' );
		if( $oc != $info->response->clientData->challenge ) {
			$this->oops( "challenge mismatch" );
		}

		/* cross check origin */
		if( $this->appid != str_replace( 'https://', '', $info->response->clientData->origin ) ) {
			$this->oops( "origin mismatch '{$info->response->clientData->origin}'" );
		}

		if( $info->response->clientData->type != 'webauthn.get' ) {
			$this->oops( "type mismatch '{$info->response->clientData->type}'" );
		}

		$bs = self::array_to_string( $info->response->authenticatorData );
		$ao = (object) [];

		$ao->rpIdHash = substr( $bs, 0, 32 );
		$ao->flags    = ord( substr( $bs, 32, 1 ) );
		$ao->counter  = substr( $bs, 33, 4 );

		$hashId = hash( 'sha256', $this->appid, TRUE );
		if( $hashId != $ao->rpIdHash ) {
			// log(bin2hex($hashId).' '.bin2hex($ao->rpIdHash));
			$this->oops( 'cannot decode key response (2b)' );
		}

		/* experience shows that at least one device (OnePlus 6T/Pie (Android phone)) doesn't set this,
		   so this test would fail. This is not correct according to the spec, so  pragmatically it may
		   have to be removed */
		if( $ao->flags != 0x1 ) {
			$this->oops( 'cannot decode key response (2c)' );
		} /* only TUP must be set */

		/* assemble signed data */
		$clientdata = self::array_to_string( $info->response->clientDataJSONarray );
		$signeddata = $hashId . chr( $ao->flags ) . $ao->counter . hash( 'sha256', $clientdata, TRUE );

		if( count( $info->response->signature ) < 70 ) {
			$this->oops( 'cannot decode key response (3)' );
		}

		$signature = self::array_to_string( $info->response->signature );

		//$key = str_replace(chr(13), '', $key->key);
		$key = $key->key;
		// log($key);
		switch( @openssl_verify( $signeddata, $signature, $key, OPENSSL_ALGO_SHA256 ) ) {
			case 1:
				/* hooray, we're in */
				$userdata = [
					'id'        => $user["id"],
					'superuser' => ( $user["rank"] <= 2 ),
					'logged_in' => TRUE,
					'engels'    => $user["engels"],
				];
				$this->session->set_userdata( $userdata );
				$this->login_model->unset_recovery( $user["id"] );

				echo TRUE;
				break;
			case 0:
				echo FALSE;
				break;
			default:
				return $this->oops( 'cannot decode key response (4) ' . openssl_error_string() );
		}
	}

	/**
	 * convert an array of uint8's to a binary string
	 *
	 * @param array $a to be converted (array of unsigned 8 bit integers)
	 *
	 * @return string converted to bytes
	 */
	private static function array_to_string( $a ) {
		$s = '';
		foreach( $a as $c ) {
			$s .= chr( $c );
		}

		return $s;
	}

	/**
	 * convert a binary string to an array of uint8's
	 *
	 * @param string $s to be converted
	 *
	 * @return array converted to array of unsigned integers
	 */
	private static function string_to_array( $s ) {
		/* convert binary string to array of uint8 */
		$a = [];
		for( $idx = 0; $idx < strlen( $s ); $idx ++ ) {
			$a[] = ord( $s[ $idx ] );
		}

		return $a;
	}

	/**
	 * convert a public key from the hardware to PEM format
	 *
	 * @param string $key to be converted to PEM format
	 *
	 * @return string converted to PEM format
	 */
	private function pubkey_to_pem( $key ) {
		/* see https://github.com/Yubico/php-u2flib-server/blob/master/src/u2flib_server/U2F.php */
		if( strlen( $key ) !== 65 || $key[0] !== "\x04" ) {
			return NULL;
		}
		/*
		* Convert the public key to binary DER format first
		* Using the ECC SubjectPublicKeyInfo OIDs from RFC 5480
		*
		*  SEQUENCE(2 elem)                        30 59
		*   SEQUENCE(2 elem)                       30 13
		*    OID1.2.840.10045.2.1 (id-ecPublicKey) 06 07 2a 86 48 ce 3d 02 01
		*    OID1.2.840.10045.3.1.7 (secp256r1)    06 08 2a 86 48 ce 3d 03 01 07
		*   BIT STRING(520 bit)                    03 42 ..key..
		*/
		$der = "\x30\x59\x30\x13\x06\x07\x2a\x86\x48\xce\x3d\x02\x01";
		$der .= "\x06\x08\x2a\x86\x48\xce\x3d\x03\x01\x07\x03\x42";
		$der .= "\x00" . $key;
		$pem = "-----BEGIN PUBLIC KEY-----\x0A";
		$pem .= chunk_split( base64_encode( $der ), 64, "\x0A" );
		$pem .= "-----END PUBLIC KEY-----\x0A";

		return $pem;
	}

	/**
	 * just an abbreviation to throw an error: never returns
	 *
	 * @param string $s error message
	 * @param int    $c error code (0 fo    r user error, 1 for incorrect usage)
	 */
	private function oops( $s, $c = 0 ) {
		throw new \Exception( $s, $c );
	}

}