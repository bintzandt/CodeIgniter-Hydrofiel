<?php

/**
 * Class Mail
 * Only used for displaying mails to the user.
 */
class Mail extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mail_model');
    }

    /**
     * Show an overview of the mail history, or, when a hash is provided, show that mail
     *
     * @param null $hash string Hash of the mail
     */
    public function index($hash = null)
    {
        //Show details of mail or show error
        $result = $this->mail_model->get_mail($hash);
        if (empty($result)) {
	        show_error("Hash not found");
        }

	    $this->loadView('mail/view', $result[0]);
    }
}
