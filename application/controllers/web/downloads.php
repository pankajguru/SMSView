<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Downloads extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Create web interface for report downloads 
	 */
	public function index()
	{
		$data['content'] = 'test';
		$this->load->view('web/default.php', $data);
	}
}

