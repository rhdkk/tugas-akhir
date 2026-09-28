<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_kelas');				
		$this->load->model('md_dosen');
		$this->load->model('md_ruang');
		$this->load->model('md_matkul');
		$this->load->model('md_sesi');
		$this->load->model('md_prodi');		
		$this->load->model('md_plot');		
		$this->load->model('md_doskel');		
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$id_user = $this->session->userdata('id_user_jadwal');
			$data['akses'] = $this->md_prodi->getAkses($id_user);
			$this->load->view('vw_home',$data);	
		} else redirect('login','refresh');
	}
	
	public function ajar()
	{
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->view('vw_ajar');	
		} else redirect('login','refresh');
	}
	
	public function ajax_ajar()
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$hak = $this->session->userdata('hak_jadwal');
		if ($hak==1) $filter=null;
		else $filter=$this->session->userdata('id_jur_jadwal')."|".$this->session->userdata('id_ps_jadwal');
		$data = $this->md_dosen->getRekapAjar($smt,$filter);		
		$ary =  array();
		foreach($data as $d)
		{ 
			$row = array();
			/*$noDsn = explode(" ", $d['no_dosen']);
			$row[] = $noDsn[1];*/
			$strDsn = "<a href='javascript:void()' title='Detil Ajar' onclick='det_ajar(\"".$d['id_dosen']."\")'>".$d['nm_dosen']." (";
			if (trim($d['gelar1'])!="") 
			{
				$strDsn .= $d['gelar1'];
				if (trim($d['gelar2'])!="") $strDsn .= ", ".trim($d['gelar2']);
			} else $strDsn .= trim($d['gelar2']);
			$strDsn .= " )</a>";
			if ($d['jml']>12) $strDsn .= " <i class='glyphicon glyphicon-info-sign text-danger' title='Jumlah Beban Mengajar lebih dari 12 sks'></i>";
			if (trim($d['jabfung'])!="") 
			{
				if ($d['jabfung']==1) $tJabfung = "(1) Tenaga pendidik";
				elseif ($d['jabfung']==2) $tJabfung = "(2) Asisten Ahli";
				elseif ($d['jabfung']==3) $tJabfung = "(3) Lektor";
				elseif ($d['jabfung']==4) $tJabfung = "(4) Lektor kepala";
				elseif ($d['jabfung']==5) $tJabfung = "(5) Guru Besar";
				else $tJabfung = "";
			} else $tJabfung = "";			

			$row[] = $strDsn;
			$row[] = $tJabfung;			
			$row[] = $d['jnj_dsn']." ".$d['pd_dsn'];			
			$row[] = $d['jml'];			
			$row[] = $d['jml1'];
			$row[] = $d['jml2'];
			$row[] = $d['jml3'];			
			$row[] = $d['jml4'];
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_detAjar($id=null)
	{		
		$smt = $this->session->userdata('id_smt_jadwal');
		$hak = $this->session->userdata('hak_jadwal');		
		$data = $this->md_dosen->getAjarDosen($id,null);
		$ary = array();
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");		
		foreach($data as $d)
		{ 
			$row = array();
			if (!empty($d['hari'])) 
			{
				$row[] = $d['hari'];
				$row[] = $hr[$d['hari']];
				$row[] = $d['jam1']." - ".$d['jam2'];
			}
			else 
			{
				$row[] = "";
				$row[] = "";
				$row[] = "";
			}			
			$row[] = $d['nm_mk']." [".$d['kode'].chr(64+$d['nm_kls'])."]";
			$row[] = $d['jenjang']." ".$d['nm_ps'];
			$row[] = $d['sks'];
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function kuliah()
	{
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->view('vw_kuliah');	
		} else redirect('login','refresh');		
	}	
	
	public function ajax_kuliah()
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
		$data = $this->md_plot->getRekapKuliah($smt);		
		$ary =  array();
		foreach($data as $d)
		{ 
			$row = array();
			$row[] = $d['hari']." - ".$hr[$d['hari']];
			$row[] = $d['nm_sesi']." (".$d['jam1']." - ".$d['jam2'].")";			
			$row[] = "<a class='btn btn-xs btn-primary' href='javascript:void()' title='Detil Ajar' onclick='det_kuliah(\"".$d['hari']."|".$d['id_sesi']."\")'>&nbsp;".$d['kls']."&nbsp;</a>";
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
		
	public function recap()
	{
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->view('vw_lengkap');	
		} else redirect('login','refresh');	
	}	
	
	public function ajax_lengkap()
	{		
		$smt = $this->session->userdata('id_smt_jadwal');
		$hak = $this->session->userdata('hak_jadwal');		
		$data = $this->md_dosen->getAjarDosen(null,null);
		$ary = array();
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");		
		foreach($data as $d)
		{ 
			$row = array();	
			$strDsn = $d['nm_dosen']." (";
			if (trim($d['gelar1'])!="") 
			{
				$strDsn .= $d['gelar1'];
				if (trim($d['gelar2'])!="") $strDsn .= ", ".trim($d['gelar2']);
			} else $strDsn .= trim($d['gelar2']);
			$strDsn .= " )";
			
			$row[] = $strDsn;			
			if ($d['jenjang']=="XP") $jenjang="Profesi"; else $jenjang=$d['jenjang'];
			$row[] = $jenjang." ".$d['nm_ps'];
			if ($d['jenjang_kls']=="XP") $jenjang_kls="Profesi"; else $jenjang_kls=$d['jenjang_kls'];
			$row[] = $jenjang_kls." ".$d['nm_ps_kls'];			
			
			$row[] = $d['nm_mk'];			
			$row[] = $d['kode'].chr(64+$d['nm_kls']);
			
			if (!empty($d['hari'])) 
			{
				$row[] = $d['hari'].". ".$hr[$d['hari']];
				$row[] = $d['jam1']." - ".$d['jam2'];
				$row[] = $d['nm_ruang'];
			}
			else 
			{
				$row[] = "";
				$row[] = "";
				$row[] = "";
			}		
			
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
}
?>