<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 09/04/18
 * Time: 14:48
 */

class Migrate extends _BeheerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('migration');

        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        }
    }
}