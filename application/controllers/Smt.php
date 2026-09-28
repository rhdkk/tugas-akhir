<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Smt extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_smt');				
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hakJadwal');
		$this->load->view('vw_smt');		
	}
	
	public function ajax_list()
	{		
		$data = $this->md_smt->getData();
		$ary = array();
		foreach($data as $d)
		{ 
			if ($d['stat']==1)
			{
				$row = array();
				if ($d['aktif']==1)
					$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-success' href='javascript:void()' title='Aktif'><i class='glyphicon glyphicon-ok'></i></a></div>";
				else 
					$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-danger' href='javascript:void()' title='Tidak Aktif' onclick='on_smt(\"".$d['id_smt']."\")'><i class='glyphicon glyphicon-remove'></i></a></div>";
				$row[] = $d['nm_smt'];			
				$row[] = $d['th_ajar']." / ".($d['th_ajar']+1);							
				
				$ary[] = $row;
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function next_smt()
	{
		$maxID = $this->md_smt->getMaxID();
		$subTh = substr($maxID,0,2);
		$subSem = substr($maxID,-1);
		if ($subSem=='1') $nmSmt = "Ganjil"; 
		elseif ($subSem=='2') $nmSmt = "Genap"; 
		else $nmSmt = "Pendek"; 
		$th1="20".$subTh; $th2=(int)$th1+1;
		$data = array(
			'id_smt' => $maxID,
			'nm_smt' => $nmSmt,
			'th_ajar' => $th1
		);
		$this->md_smt->update(array('aktif' => '0'));
		$this->md_smt->save($data);
		
		$this->session->set_userdata('id_smt_jadwal', $maxID);		
		$this->session->set_userdata('nm_smt_jadwal', $nmSmt." ".$th1." / ".$th2);				
		echo json_encode(array("status" => TRUE,"sem"=>$nmSmt." ".$th1." / ".$th2));
	}
	public function aktif_smt()
	{
		$this->md_smt->update(array('aktif' => '0'));
		$this->md_smt->update(array('aktif' => '1'),array('id_smt' => $this->input->post('idOn')));
		$subTh = substr($this->input->post('idOn'),0,2);
		$subSem = substr($this->input->post('idOn'),-1);
		if ($subSem=='1') $nmSmt = "Ganjil"; 
		elseif ($subSem=='2') $nmSmt = "Genap"; 
		else $nmSmt = "Pendek"; 
		$th1="20".$subTh; $th2=(int)$th1+1;
		
		$this->session->set_userdata('id_smt_jadwal', $this->input->post('idOn'));		
		$this->session->set_userdata('nm_smt_jadwal', $nmSmt." ".$th1." / ".$th2);	
		echo json_encode(array("status" => TRUE, "sem"=>$nmSmt." ".$th1." / ".$th2));
	}
	public function delete_data()
	{
		$data = array(
			'stat' => 0
		);
		$this->md_kelas->update($data,array('id_smt' => $this->input->post('idHapus'))); 
		echo json_encode(array("status" => TRUE));
	}	
}