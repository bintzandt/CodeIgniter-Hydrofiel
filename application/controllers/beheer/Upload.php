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
}