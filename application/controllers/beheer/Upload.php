<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 02/02/18
 * Time: 22:52
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