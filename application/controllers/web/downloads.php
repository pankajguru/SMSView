<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Downloads extends CI_Controller {


	/**
	 * Index Page for this controller.
	 *
	 * Create web interface for report downloads 
	 */
	public function index()
	{
		$this->config->load('sms');
		$data['content'] = 'test';
		foreach ($this->config->item('report_dirs') as $name => $dir){
			if ($handle = opendir($dir)) {
			    while (false !== ($entry = readdir($handle))) {
			        if ($entry != "." && $entry != ".." && preg_match('/.xml$/',$entry) ) {
					    $xml = simplexml_load_file($dir.'/'.$entry);
					 
					    print_r($xml);
			        }
			    }
			    closedir($handle);
			}			
			print $name;
		}
		$this->load->view('web/default.php', $data);
	}
}

