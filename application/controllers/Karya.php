<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karya extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_karya');
		$this->load->model('md_konsentrasi');
		$this->load->model('md_prodi');
		$this->load->model('md_dosen');
	}
	public function index()
	{		
		$this->load->helper('url');
		$data['karya'] = $this->md_karya->getData();		
		$data['prodi'] = $this->md_prodi->getData();		
		$data['konsentrasi'] = $this->md_konsentrasi->getData();		
		$data['dosen'] = $this->md_dosen->getData();		
		$this->load->view('vw_karya',array('data'=>$data));				
	}
	public function ajax_list()
	{
		$data = $this->md_karya->getData();
		$ary =  array();
		foreach($data as $d)
		{
			if ($d['strata_prodi']==1) $jenis="<a class='label label-danger' href='javascript:void()' title='Detil' onclick='detil_karya(\"".$d['id_karya']."\")'>Skripsi</a>";
			elseif ($d['strata_prodi']==2) $jenis="<a class='label label-warning' href='javascript:void()' title='Detil' onclick='detil_karya(\"".$d['id_karya']."\")'>Tesis</a>";
			elseif ($d['strata_prodi']==3) $jenis="<a class='label label-info' href='javascript:void()' title='Detil' onclick='detil_karya(\"".$d['id_karya']."\")'>Disertasi</a>";
			$row = array();
			$row[] = $d['nim_karya'];
			$row[] = $d['nama_karya'];
			$row[] = $d['judul_karya'];			
			$row[] = $jenis;
			$row[] = "<a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_karya(\"".$d['id_karya']."\")'><i class='glyphicon glyphicon-edit'></i></a> <a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='hapus_karya(\"".$d['id_karya']."\")'><i class='glyphicon glyphicon-trash'></i></a>";
			
			$ary[] = $row;
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	public function ajax_edit($id)
	{
		$data = $this->md_karya->getByID($id);
		$ary =  array();
		foreach($data as $d)
		{
			$row = array(
				'id_karya' => $d['id_karya'],
				'id_prodi_karya' => $d['id_prodi_karya'],
				'id_konsentrasi_karya' => $d['id_konsentrasi_karya'],
				'nim_karya' => $d['nim_karya'],
				'nama_karya' => $d['nama_karya'],
				'judul_karya' => $d['judul_karya'],
				'abstrak_karya' => $d['abstrak_karya'],
				'th_karya' => $d['th_karya'],
				'nm_dosen1' => $d['nm_dosen1'],
				'nm_dosen2' => $d['nm_dosen2'],
				'nm_dosen3' => $d['nm_dosen3']
			);
			
			$ary[] = $row;
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	public function add_data() 
	{
		$data = array(
			'id_prodi_karya' => $this->input->post('idProdi'),
			'id_konsentrasi_karya' => $this->input->post('idKonsentrasi'),
			'nim_karya' => $this->input->post('nim'),
			'nama_karya' => $this->input->post('nama'),
			'judul_karya' => $this->input->post('judul'),
			'abstrak_karya' => $this->input->post('abstrak'),
			'th_karya' => $this->input->post('tahun'),
			'nm_dosen1' => $this->input->post('dosen1'),
			'nm_dosen2' => $this->input->post('dosen2'),
			'nm_dosen3' => $this->input->post('dosen3')
		);		
		
		$this->md_karya->save($data);		
		echo json_encode(array("status" => TRUE));
	}
	public function update_data()
	{
		$data = array(
			'id_prodi_karya' => $this->input->post('idProdi'),
			'id_konsentrasi_karya' => $this->input->post('idKonsentrasi'),
			'nim_karya' => $this->input->post('nim'),
			'nama_karya' => $this->input->post('nama'),
			'judul_karya' => $this->input->post('judul'),
			'abstrak_karya' => $this->input->post('abstrak'),
			'th_karya' => $this->input->post('tahun'),
			'nm_dosen1' => $this->input->post('dosen1'),
			'nm_dosen2' => $this->input->post('dosen2'),
			'nm_dosen3' => $this->input->post('dosen3')
		);
		$this->md_karya->update($data,array('id_karya' => $this->input->post('idKarya')));
		echo json_encode(array("status" => TRUE));
	}
	public function delete_data()
	{
		$this->md_karya->delete(array('id_karya' => $this->input->post('idHapus')));
		echo json_encode(array("status" => TRUE));
	}
	public function ajax_detil($id)
	{
		$data = $this->md_karya->getByID($id);
		$ary =  array();
		foreach($data as $d)
		{
			$row = array(
				'prodi_karya' => $d['nm_prodi'],
				'konsentrasi_karya' => $d['nm_konsentrasi'],
				'nim_karya' => $d['nim_karya'],
				'nama_karya' => $d['nama_karya'],
				'judul_karya' => $d['judul_karya'],
				'abstrak_karya' => $d['abstrak_karya'],
				'th_karya' => $d['th_karya'],
				'nm_dosen1' => $d['nm_dosen1'],
				'nm_dosen2' => $d['nm_dosen2'],
				'nm_dosen3' => $d['nm_dosen3']
			);
			
			$ary[] = $row;
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
}
