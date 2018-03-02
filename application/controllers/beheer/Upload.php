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

    public function index(){
        $this->loadView('templates/upload');
    }
}