<?php

/**
 * Class Upload
 * Handles file uploads
 */
class Upload extends _BeheerController {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get a list of images and files from our page_model and show them in a table.
	 */
	public function index() {
		$data['images']  = $this->page_model->get_image_list();
		$data['files']   = $this->page_model->get_file_list();
		$this->loadView( 'beheer/upload/upload', $data );
	}

	/**
	 * Function to import users from file
	 */
	public function import_users() {
		$config['upload_path']   = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']      = 100;
		$config['max_width']     = 1024;
		$config['max_height']    = 768;

		$this->load->library( 'upload', $config );

		if( ! $this->upload->do_upload( 'leden' ) ) {
			$error = [ 'error' => $this->upload->display_errors() ];
			$this->loadView( 'beheer/leden/importeren', $error );
		}
		else {
			$data      = $this->upload->data();
			$full_path = $data["full_path"];
			if( ( $result = $this->profile_model->upload_users( $full_path ) ) > 0 ) {
				$this->session->set_flashdata( 'success', $result . " rijen bijgewerkt." );
			}
			elseif( $result === 0 )
				$this->session->set_flashdata( 'success', "De database was up-to-date." );
			else $this->session->set_flashdata( 'error', "Er is helaas iets fout gegaan." );
			redirect( '/beheer/leden' );
		}
	}

	/**
	 * Function to upload files from the beheer panel
	 */
	public function files() {
		$config['upload_path']   = './uploads/';
		$config['allowed_types'] = 'jpg|pdf';
		$config['max_size']      = 10000;
		$config['remove_spaces'] = FALSE;

		$this->load->library( 'upload', $config );
		if( ( $data = $this->upload->do_multi_upload() ) && $data !== FALSE ) {
			foreach( $data as $file ) {
				if( $file['is_image'] ) {
					$this->create_thumbnail( $file['full_path'], './fotos/thumb/' . $file['file_name'], 60 );
					rename( $file['full_path'], './fotos/' . $file['file_name'] );
				}
				else {
					rename( $file['full_path'], './files/' . $file['file_name'] );
				}
			}
			$this->session->set_flashdata( 'success', 'Bestand(en) succesvol geupload!' );
		}
		else {
			if( $data === NULL ) {
				$this->session->set_flashdata( 'error', "Geen bestand(en) geselecteerd" );
			}
			else {
				$this->session->set_flashdata( 'error', $this->upload->display_errors() );
			}
		}
		redirect( 'beheer/upload' );
	}

	/**
	 * Function to create a thumbnail from an uploaded image.
	 *
	 * @param $src           String The source image
	 * @param $dest          String The destination
	 * @param $desired_width Int The desired width
	 */
	private function create_thumbnail( $src, $dest, $desired_width ) {
		/* read the source image */
		$source_image = imagecreatefromjpeg( $src );
		$width        = imagesx( $source_image );
		$height       = imagesy( $source_image );

		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor( $height * ( $desired_width / $width ) );

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor( $desired_width, $desired_height );

		/* copy source image at a resized size */
		imagecopyresampled( $virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height );

		/* create the physical thumbnail image to its destination */
		imagejpeg( $virtual_image, $dest );
	}

	/**
	 * Function to delete a file
	 *
	 * @param $foto boolean Is the file a boolean
	 * @param $path string Path to the file
	 */
	public function delete( $type, $path ) {
		if( $type === "files" ) {
			$url = './files/';
		}
		elseif( $type === "fotos" ) {
			$url = './fotos/';
		}
		$file = rawurldecode( $url . $path );
		if( is_file( $file ) ) {
			unlink( $file );
			$this->session->set_flashdata( 'success', "Het bestand is verwijderd!" );
		}
		else {
			$this->session->set_flashdata( 'error', "Er is iets mis gegaan." );
		}
		redirect( '/beheer/upload' );
	}

//    UNCOMMENT IF YOU WANT TO MANUALLY GENERATE THUMBNAILS!
//    After uncommenting you can visit /beheer/upload/generate_thumbnails with sufficient rights to generate the thumbnails.
//    public function generate_thumbnails(){
//        foreach(glob('./fotos/*.*') as $file) {
//            if ($file === './fotos/index.php') continue;
//            $this->create_thumbnail('./fotos/' . basename($file), './fotos/thumb/' .basename($file), 60);
//        }
//    }
}