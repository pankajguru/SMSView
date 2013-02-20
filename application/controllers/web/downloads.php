<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Downloads extends CI_Controller {


	/**
	 * Index Page for this controller.
	 *
	 * Create web interface for report downloads 
	 */
	public function index($sort_by = 'sample_date', $sort_order = 'asc', $offset = 0)
	{
		$this->config->load('sms');
        $this->load->helper('url');
        $this->load->helper('form');
        $limit = 20;

        $data['fields'] = array(
            'id' => 'tech',
            'peiling_id' => 'peiling id',
            'schoolnaam' => 'Schoolnaam',
            'download'  => 'Download'
        );

        $reports =  array();
        $templates = array(''=>'kies template');
        $handle = opendir($this->config->item('template_dir'));
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && preg_match('/^muis/',$entry) && $entry !== 'muis-style.docx'  ) {
//                array_push($templates, array($entry => $entry));
                $templates[$entry] = $entry;
            }
        }

//		foreach ($this->config->item('report_dir') as $name => $dir){
        $dir = $this->config->item('report_dir');
			if ($handle = opendir($dir)) {
			    while (false !== ($entry = readdir($handle))) {
			        if ($entry != "." && $entry != ".." && preg_match('/.xml$/',$entry) ) {
                        $this -> load -> library('simplexml');
                
                        $xmlRaw = file_get_contents($dir.'/'.$entry);
                
                        $xmlData = $this -> simplexml -> xml_parse($xmlRaw);

					    $datastring = $xmlData['table.satisfaction.data'];
                        $datastring     = str_replace('\\\'', '\'', $datastring);
                        $json = json_decode($datastring);
                        $schoolname = $xmlData['schoolnaam'];
                        $techid = $xmlData['peiling.id'];
                        preg_match("/MUIS_(\d+)_(\d+).docx.xml/",$entry, $matches);
                        $peiling_id = $matches[1];
                        if (isset($json->{'refs'})){
                            $report = new stdClass();
                            $report->id = $techid;
                            $report->peiling_id = $peiling_id;
                            $report->schoolnaam = $schoolname;
                            $refs = $json->{'refs'};
                            $form_open = form_open('web/downloads/download');
                            $form_hidden =form_hidden('xml', $entry);
                            $form_options = '';
                            foreach ($refs as $key => $value) {
                                if ($value == '') continue;
                                $checked = FALSE;
                                if ($value === 'peiling') $checked = TRUE;
                                if (($xmlData['peiling.ref_group_all'] === '1') && ($value === 'alle_scholen')) $checked = TRUE;
                                $value = str_replace('_', ' ', $value);
                                $form_options .= form_checkbox('ref[]', $value, $checked).$value.' ';
                            }
                            $form_template = form_dropdown('template', $templates, '');
                            $form_button = form_submit('download', 'download');
                            $form_close = form_close();
                            $download_form = $form_open.$form_hidden.$form_options.$form_template.$form_button.$form_close;
                            $report->download = $download_form;
                            array_push($reports,$report);
                        }
			        }
			    }
			    closedir($handle);
			}			
//			print $name;
//		}
        $data['reports'] = $reports;
        // pagination
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = site_url("main/index/$sort_by/$sort_order");
        $config['total_rows'] = count($reports);
        $data['num_results'] = count($reports);
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        
        $this->load->view('web/downloads', $data);
	}

    public function download(){
        $template = $this->input->post('template');
        $ref = $this->input->post('ref');
        $xml = $this->input->post('xml');
        print ''.$template.' '.' '.$xml;
//        print_r($ref);
        $xml = $this->config->item('report_dir').'/'.$xml;
        $xml = str_replace('/','___',$xml);
        $template = $this->config->item('template_dir').'/'.$template;
        $template = str_replace('/','___',$template);
        print "http://sms.dev.local/index.php/docs/parse/doc/$template/$xml/rapport";
    }
}

