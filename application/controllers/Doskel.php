<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Doskel extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_doskel');						
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hakJadwal');
		$id_user = $this->session->userdata('id_user_jadwal');
		$data['sesi'] = $this->md_sesi->getData();
		$data['dosen'] = $this->md_dosen->getData();
		$data['ruang'] = $this->md_ruang->getData();
		$data['akses'] = $this->md_prodi->getAkses($id_user);
		$this->load->view('vw_plot',$data);		
	}
	public function set_jadwal()
	{
		$data = array(
			'nm_kls' => $this->input->post('kls'),
			'id_smt' => $this->input->post('smt'),
			'id_mk' => $this->input->post('mk')
		);
		$this->md_kelas->save($data);
		echo json_encode(array("status" => TRUE));
	}
	public function update_data()
	{
		$data = array(
			'nm_mk' => $this->input->post('nama'),
			'n_mhs' => $this->input->post('mhs')
		);
		$this->md_kelas->update($data,array('id_kls' => $this->input->post('idKelas'))); 
		echo json_encode(array("status" => TRUE));
	}	
	public function delete_data($id=null)
	{
		if (!empty($id)) $idDel = $id;
		else $idDel = $this->input->post('idHapus');		
		$data['stat']=0;				
		$this->md_doskel->update($data,array('id_doskel' => $idDel)); 
		
		$user = $this->session->userdata('id_user_jadwal');
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		$dtLog['id_log_doskel'] = $this->md_doskel->getMaxLogID($th);
		$dtLog['id_user'] = $user;
		$dtLog['dt'] = $tgl." ".$jam;		
		$dtLog['id_doskel'] = $idDel;		
		$dtLog['act'] = 3;
		$this->md_doskel->saveDosen($dtLog);
		
		echo json_encode(array("status" => TRUE));
	}	
}