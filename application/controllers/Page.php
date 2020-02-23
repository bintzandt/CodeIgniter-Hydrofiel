<?php
/**
 * Handles all page related stuff
 * Class Page
 */
class Page extends _SiteController
{
	public Page_model $page_model;
	public CI_Session $session;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index function, refers to id
     *
     * @param int $page Which page needs to be shown
     */
    public function index($page = 1)
    {
        $this->id($page);
    }

    /**
     * Actual function to get page contents
     *
     * @param string $page
     */
    public function id($page = 1)
    {
	    /**
	     * Get the page from the model
	     * @var PageObject $page
	     */
        $page = $this->page_model->view($page);

        $page->check_login();

        //Check if this is an actual page
        if (empty($page)) {
            show_404();
        }

        if ($page->naam === 'Wedstrijden') {
            return $this->loadView('templates/wedstrijden');
        }

        return $this->loadView('templates/page', [ 'tekst' => $page->tekst ] );
    }

    /**
     * Overwrite the default error_404 handler.
     */
    public function page_missing()
    {
        $this->loadView('errors/html/error_404');
    }

    /**
     * Function to save routes from the database and add them to the routes file.
     * This allows for easier navigation to several pages on the website.
     */
    public function save_routes()
    {
        $pages = $this->page_model->get_all();
        $data = [];
        if (!empty($pages)) {
            $data[] = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';

            foreach ($pages as $page) {
                $data[] = '$route[\'' . strtolower( str_replace( ' ', '%20', $page->naam ) ). '\'] = \'page/id/' . $page->id . '\';';
            }
            $output = implode("\n", $data);

            write_file(APPPATH . 'cache/routes.php', $output);
        }
    }
}
