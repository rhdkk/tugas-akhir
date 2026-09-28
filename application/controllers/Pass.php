<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pass extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_user');				
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hak_thesis');
		if (!empty($hak))
		{
			$this->load->view('vw_pass');		
		} else redirect('login','refresh');
	}
	
	public function get_det()
	{
		$id = $this->session->userdata('id_user_thesis');
		$data = $this->md_user>getAkses($id);
		$output = array("pasw" => $data->pass);
		echo json_encode($output);
	}
	
	public function update_data()
	{	
		$save = false;
		$id = $this->session->userdata('id_user_thesis');
		$data = $this->md_user->getData($id);
		$pass0 = $data->pass;		
		$pass1 = password_hash($this->input->post('pass0'), PASSWORD_ARGON2ID);
		$pass2 = password_hash($this->input->post('pass1'), PASSWORD_ARGON2ID);
		$user = $this->md_user->cekUser($data->username, $this->input->post('pass0'));
		if ($user === false) { $stat = false; }
		else
		{
			$stat = true;
			if ($pass0!=$pass2) 
			{
				$save = true;
				$dtPass = array('pass'=>$pass2);
				$this->md_user->update($dtPass,array('id_user'=>$id)); 
			}
		}
		echo json_encode(array("status"=>$stat, "db"=>$save));
	}	
}
?>