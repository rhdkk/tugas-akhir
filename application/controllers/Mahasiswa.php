<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mahasiswa extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_mahasiswa');				
		$this->load->model('md_dosen');				
		$this->load->library('mine');				
	} 
	
	public function ajax_rev($nim)
	{
		$id_dosen = $this->input->get('idD');
		$data = $this->md_mahasiswa->getRev($nim, $id_dosen);
		$ary = array();
		foreach($data as $d)
		{
			$tglDisp = $this->mine->dateStr($d['tgl_uji'],2);
			$row = array(
				'id_dosen' => $d['id_dosen'],
				'id_det_uji' => $d['id_det_uji'],
				'id_dospem' => $d['id_dospem'],
				'nm_dosen' => $d['nm_dosen'],
				'gelar1' => $d['gelar1'],
				'gelar2' => $d['gelar2'],
				'jab_dosen' => $d['jab_dosen'],
				'tgl_uji' => $tglDisp,
				'nm_tuji' => $d['nm_tuji'],
				'tipe_rev' => $d['tipe_rev'],
				'rev_uji' => $d['revisi_uji']
			);
			$ary[] = $row;			
		}		
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_tim($nim)
	{
		$data = $this->md_mahasiswa->getTim($nim);
		$ary = array();
		foreach($data as $d)
		{
			$row = array(
				'id_dosen' => $d['id_dosen'],
				'id_dospem' => $d['id_dospem'],
				'nm_dosen' => $d['nm_dosen'],
				'gelar1' => $d['gelar1'],
				'gelar2' => $d['gelar2'],
				'urut_dosen' => $d['urut_dosen'],
				'jab_dosen' => $d['jab_dosen'],			
				'stat_dospem' => $d['stat_dospem'],			
				'tgl_bimb' => $d['last_bimb'],			
				'acc_bimb' => $d['acc_bimb']
			);
			$ary[] = $row;			
		}		
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_bimb($nim)
	{
		$data = $this->md_mahasiswa->getBimbingan($nim);
		$row = array(
			'tgl_bimb' => $data->tgl_bimb,
			'gelar1' => $data->gelar1,
			'gelar2' => $data->gelar2,
			'nm_dosen' => $data->nm_dosen,
			'ket_bimb' => $data->ket_bimb,
			'acc_bimb' => $data->acc_bimb
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function ajax_edit($id)
	{
		$data = $this->md_mahasiswa->getDetil($id);
		$row = array(
			'nim' => $data->nim,
			'nm_mhs' => $data->nm_mhs,		
			'jalur' => $data->jalur,		
			'jenjang' => $data->jenjang,
			'nm_ps' => $data->nm_ps
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
}
?>