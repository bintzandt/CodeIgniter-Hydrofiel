<?php
/**
 * Class Upload
 * Handles file uploading on the server
 * TODO: Move this controller to beheer/Upload.php
 */
class Upload extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->logged_in){
            redirect('/inloggen');
        }
        if (!$this->session->superuser){
            show_error("Je bent geen beheerder!");
        }
        $this->load->helper(array('form', 'url'));
        $this->load->model('profile_model');
    }

    /**
     * Function to import users from file
     */
    public function import_users(){
        $config['upload_path']          = '/home/hydrof1q/uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('leden'))
        {
            $error = array('error' => $this->upload->display_errors());
            $this->loadViewBeheer('beheer/leden/importeren', $error);
        }
        else
        {
            $data = $this->upload->data();
            $full_path = $data["full_path"];
            if (($result = $this->profile_model->upload_users($full_path)) > 0){
                $this->session->set_flashdata('success', $result . " rijen bijgewerkt.");
            }
            elseif ($result === 0) $this->session->set_flashdata('success', "De database was up-to-date.");
            else $this->session->set_flashdata('fail', "Er is helaas iets fout gegaan.");
            redirect('/beheer/leden');
        }
    }

    /**
     * Function to upload files from the beheer panel
     */
    public function files(){
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'jpg|pdf';
        $config['max_size']             = 10000;

        $this->load->library('upload', $config);

        if ( $this->upload->do_upload('files')){
            $data = $this->upload->data();
            //Process all files accordingly....
            if ($data['is_image']){
                $url = './fotos/';
            } else {
                $url = './files/';
            }
            rename($data['full_path'], $url . $data['orig_name']);
            $files = array(
                "files" => array(
                    array(
                     "name" => $data["orig_name"],
                     "size" => $data["file_size"] * 100,
                     "url" => site_url($url . $data["orig_name"]),
                     "thumbnailUrl" => NULL,
                     "deleteUrl" => site_url('/upload/delete/' . intval($data['is_image']) . '/' . $data["orig_name"]),
                     "deleteType" => "POST"
                    )
                )
            );
            echo json_encode($files);
        } else {
            $files = array(
                "files" => array()
            );
            foreach(glob('./fotos/*.*') as $file) {
                if ($file === './fotos/index.php') continue;
                array_push($files["files"], array(
                    "name" => basename($file),
                    "size" => filesize($file),
                    "url" => site_url($file),
                    "thumbnailUrl" => site_url($file),
                    "deleteUrl" => site_url('/upload/delete/1/' . basename($file)),
                    "deleteType" => "POST"
                ));
            }
            foreach(glob('./files/*.*') as $file) {
                if ($file === './files/index.php') continue;
                array_push($files["files"], array(
                    "name" => basename($file),
                    "size" => filesize($file),
                    "url" => site_url($file),
                    "thumbnailUrl" => NULL,
                    "deleteUrl" => site_url('/upload/delete/0/' . basename($file)),
                    "deleteType" => "POST"
                ));
            }
            echo json_encode($files);
        }
    }

    /**
     * Function to delete a file
     * @param $foto boolean Is the file a boolean
     * @param $path string Path to the file
     */
    public function delete($foto, $path){
        if ($foto){
            $url = './fotos/';
        } else {
            $url = './files/';
        }
        if (is_file($url . $path)){
            unlink($url . $path);
            echo TRUE;
        }
        echo FALSE;
    }
}