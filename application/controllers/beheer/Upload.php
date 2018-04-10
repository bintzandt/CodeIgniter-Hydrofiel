<?php
/**
 * Class Upload
 * Handles file uploads
 */
class Upload extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_image_list(){
        $files = array();
        foreach(glob('./fotos/*.*') as $file) {
            if ($file === './fotos/index.php') continue;
            $image = new stdClass();
            $naam = explode(' ', basename($file));
            if (sizeof($naam) > 2){
                unset($naam[0]);
                unset($naam[1]);
            }
            $naam = implode(" ", $naam);
            $image->naam = $naam;
            $image->url = site_url('/fotos/' . basename($file));
            $image->thumb = site_url('/fotos/thumb/' . basename($file));
            $image->deleteUrl = site_url('/upload/delete/fotos/' . basename($file));
            array_push($files, $image);
        }
        return $files;
    }

    public function get_file_list(){
        $files = array();
        foreach(glob('./files/*.*') as $file) {
            if ($file === './files/index.php') continue;
            $document = new stdClass();
            $document->naam = basename($file);
            $document->url = site_url('/files/' . basename($file));
            $document->deleteUrl = site_url('/upload/delete/files/' . basename($file));
            array_push($files, $document);
        }
        return $files;
    }

    public function index(){
        $data['success'] = $this->session->flashdata('success');
        $data['fail'] = $this->session->flashdata('fail');
        $data['images'] = $this->get_image_list();
        $data['files'] = $this->get_file_list();
        $this->loadView('beheer/upload/upload', $data);
    }

//    UNCOMMENT IF YOU WANT TO MANUALLY GENERATE THUMBNAILS!
//    public function generate_thumbnails(){
//        foreach(glob('./fotos/*.*') as $file) {
//            if ($file === './fotos/index.php') continue;
//            $this->create_thumbnail('./fotos/' . basename($file), './fotos/thumb/' .basename($file), 60);
//        }
//    }
//
//    private function create_thumbnail($src, $dest, $desired_width){
//        /* read the source image */
//        $source_image = imagecreatefromjpeg($src);
//        $width = imagesx($source_image);
//        $height = imagesy($source_image);
//
//        /* find the "desired height" of this thumbnail, relative to the desired width  */
//        $desired_height = floor($height * ($desired_width / $width));
//
//        /* create a new, "virtual" image */
//        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
//
//        /* copy source image at a resized size */
//        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
//
//        /* create the physical thumbnail image to its destination */
//        imagejpeg($virtual_image, $dest);
//    }
}