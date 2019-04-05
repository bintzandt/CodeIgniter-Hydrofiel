<script>
	function hasWebAuthnSupport() {
		return (window.PublicKeyCredential !== undefined || typeof window.PublicKeyCredential === "function");
	}

	$( document ).ready( function() {
		if ( hasWebAuthnSupport() ){
			$('#browser-supported').removeClass( 'hidden' );
		}

		$( '#form-signin' ).submit( async function( e ) {
			let password = $('#wachtwoord').val();

			if ( password != ""){
				return;
			}

			if ( ! hasWebAuthnSupport() ){
				return;
			}

			e.preventDefault();
			const email = $('#email').val();

			$.ajax({
				method: "POST",
				url: "/webauthn/prepare_for_login",
				data: { email: email},
				dataType: "json",
				success: function( r ){
					webauthnAuthenticate( r, function( success, info ){
						if ( success ) {
							$.ajax({
								method: "POST",
								url: '/webauthn/authenticate',
								data: { auth: info, email: email },
								dataType: "json",
								success: function(){
									window.location.replace( '/' );
								},
								error: function ( xhr, status, error ){
									alert( "login failed: " + error + ": " + xhr.responseText );
								},
							});
						} else {
							alert( info );
						}
					});
				},
				error: function( xhr, status, error ){
					alert( "couldn't initiate login: " + error + ": " + xhr.responseText );
				}
			})
		} );
	} );
</script>
<div class="row" style="width: 100%">
	<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
		<?php echo form_error( 'wachtwoord' ); ?>
		<?php echo form_error( 'email' ); ?>
		<?php echo form_open( 'inloggen', [ 'class' => 'form-signin', 'id' => 'form-signin' ] ); ?>
		<input type="text" name="email" id="email" class="form-control" placeholder="Email"
		       value="<?php echo set_value( 'email' ); ?>" autofocus>
		<input type="password" name="wachtwoord" id="wachtwoord" class="form-control"
		       placeholder="<?= lang( 'inloggen_password' ) ?>">
		<span class="help-block hidden" id="browser-supported"><?= lang( 'inloggen_browser_supported' ); ?></span>
		<button class="btn btn-lg btn-primary btn-block" type="submit"><?= lang( 'inloggen_login' ) ?></button>
		<a href="/inloggen/forgot_password"
		   class="pull-right need-help"><?= lang( 'inloggen_forgot_password' ) ?></a><span class="clearfix"></span>
		<input type="hidden" name="redirect" value="<?= $redirect ?>">
		<?= form_close() ?>
	</div>
</div>