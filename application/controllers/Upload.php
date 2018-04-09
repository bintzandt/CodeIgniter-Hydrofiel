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
        $config['upload_path']          = './uploads/';
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
        $config['max_size']             = 100000;
        $config['remove_spaces']        = FALSE;

        $this->load->library('upload', $config);
        if (($data = $this->upload->do_multi_upload()) !== NULL){
            foreach ($data as $file) {
                if ($file['is_image']) {
                    rename($file['full_path'], './fotos/' . $file['file_name']);
                } else {
                    rename($file['full_path'], './files/' . $file['file_name']);
                }
            }
            $this->session->set_flashdata('success', 'Bestand(en) succesvol geupload!');
        } else {
            $this->session->set_flashdata('fail', 'Er is iets mis gegaan met uploaden.');
        }
        redirect('beheer/upload');
    }

    /**
     * Function to delete a file
     * @param $foto boolean Is the file a boolean
     * @param $path string Path to the file
     */
    public function delete($type, $path){
        if ($type === "files") {
            $url = './files/';
        }
        elseif ($type === "fotos") {
            $url = './fotos/';
        }
        $file = rawurldecode($url . $path);
        if (is_file($file)){
            unlink($file);
            $this->session->set_flashdata('success', "Het bestand is verwijderd!");
        }
        else {
            $this->session->set_flashdata('fail', "Er is iets mis gegaan.");
        }
        redirect('/beheer/upload');
    }
}