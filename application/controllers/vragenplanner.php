<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vragenplanner extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->helper(array('form', 'url'));
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Expires: Wed, 4 Jul 2012 05:00:00 GMT"); // Date in the past

        if (!$this->tank_auth->is_logged_in()) {                                 // logged in
            redirect('/auth/login/');
        }
		$this->load->view('vragenplanner');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */