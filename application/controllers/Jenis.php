<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jenis extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_jenis');			
		$this->load->model('md_proses');			
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hak_layanan');
		$id_user = $this->session->userdata('id_user_layanan');
		$ps = $this->session->userdata('ps_layanan');				
		$this->load->view('vw_jenis');		
	}	
	public function viewSyarat($id)
	{		
		$data = $this->md_proses->getSyarat($id);
		$ary =  array();
		foreach($data as $d) $ary[$d['id_tipe']] = $d['nm_tipe'];						
		echo json_encode($ary);
	}
	public function cekAttr($id)
	{		
		$data = $this->md_jenis->getAttr($id);
		$str = $data->title.$data->kons;
		$output = array("attrib" => $str);
		echo json_encode($output);
	}	
	public function ajax_akun($id)
	{
		$data = $this->md_user->getData($id);
		$row = array(
			'username' => $data[0]['username'],
			'nm_user' => $data[0]['nm_user'],
			'hak' => $data[0]['hak']
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
}