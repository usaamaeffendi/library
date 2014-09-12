<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

	private $_SUCCESS 		= "SUCCESS";
	private $_FAILURE 		= "FAILURE";
	private $_NORECORDMSG 	= "No Record Found";
	private $_NOCHANGEMSG 	= "No Record Updated";
	private $_UPDATEMSG		= "Changes Saved";
	public $data = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if($this->input->is_ajax_request())
		{
			$exceptions = array('__construct','index','_output','get_instance');
			$method = $this->input->post("method");
			if(method_exists($this,$method))
			{
				$this->$method();
				$fields = array_keys($this->form_validation->error_array());
				$this->data = array('data' => $fields);
			}
			elseif($method)
			{
				$this->data = array('data' => array());
			}
			else
			{
				$methods = array_values(array_diff(get_class_methods($this),$exceptions));
				$this->data = array('data' => $methods);
			}
		}
		else
		{
			$this->load->view('services');
		}
	}
	
	/* sample method */
	public function sample()
	{
		$this->form_validation->set_rules('parameter1','Label 1','trim|required');

		if($this->form_validation->service())
		{
			/* process your data here */
			$some_processing = TRUE;
			if($some_processing)
			{
				$this->data['status'] = $this->_SUCCESS;
				$this->data['msg'] = "data processed successfully";
			}
			else
			{
				$this->data['msg'] = "Unable to process your data";
			}
		}
	}
	
	### do not remove
	public function _output($data)
	{
		if(is_array($this->data) && count($this->data))
		{
			$fileName = './logs/'.$this->router->fetch_method().'.json';
			$contents = array();
			$input = $_REQUEST;
			$input['files'] = $_FILES;
			if(file_exists($fileName))
			{
				if($c = json_decode(file_get_contents($fileName)))
				{
					$contents = $c;
				}
			}
			$file = fopen($fileName,"w+");
			$data['Date'] = date("Y-m-d H:i:s");
			$data['IP'] = $this->input->ip_address();
			$data['Input'] = $input;
			//$data['Query'] = $this->db->last_query();
			$data['Output'] = $this->data;
			while(count($contents) > 10) array_pop($contents);
			array_unshift($contents, $data);
			fputs($file,json_encode($contents));
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($this->data);
		}
		else
			echo $data;
	}
}