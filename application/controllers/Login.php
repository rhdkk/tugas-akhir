<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('md_user','',TRUE);				
		$this->load->model('md_smt');				
		$this->load->model('md_set');				
	}

	function index()
	{
		$hak = $this->session->userdata('hak_thesis');
		if (empty($hak)) $this->load->view('vw_login');		
		else redirect('kelas','refresh');
	}
	
	function verifyLogin()
	{
		$username = $this->input->post('user');
    $password = $this->input->post('pass');
    
    $user = $this->md_user->cekUser($username, $password);
    
    if ($user === false) 
		{
			echo json_encode([['ada' => 0]]);
			return;
    }
    
    $this->session->set_userdata([
			'id_user_thesis' => $user['id_user'],
			'nama_thesis' => $user['nm_user'],
			'hak_thesis' => $user['hak'],
			'id_ps_thesis' => $user['id_ps'] ?? 'x',
			'id_jur_thesis' => $user['id_jur'] ?? 'x'
    ]);
    
    echo json_encode([['ada' => $user['hak']]]);
	}
	
	function logout()
	{
		$hak = $this->session->userdata('hak_thesis');
		$this->session->unset_userdata('hak_thesis');
		$this->session->unset_userdata('id_user_thesis');
		$this->session->unset_userdata('nama_thesis');
		$this->session->unset_userdata('id_smt_thesis');		
		$this->session->unset_userdata('nm_smt_thesis');		
		$this->session->unset_userdata('id_ps_thesis');		
		$this->session->unset_userdata('id_jur_thesis');		
		redirect('login','refresh');
	}
	
	function alpanum($size)
	{
		$alpha_key = '';
		$keys = range('a','z');
		for ($i=0;$i<2;$i++) 
			$alpha_key .= $keys[array_rand($keys)];
		$length = $size - 2;
		$key = '';
		$keys = range(0, 9);
		for ($i=0;$i<$length;$i++) {
			$key .= $keys[array_rand($keys)];
		}
		return $key.$alpha_key;
	}
}