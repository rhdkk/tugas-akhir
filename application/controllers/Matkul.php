<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Matkul extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_matkul');				
		$this->load->model('md_prodi');				
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_thesis');
		if (!empty($hak))
		{
			$this->load->helper('url');		
			$id_user = $this->session->userdata('id_user_thesis');
			$data['prodi'] = $this->md_prodi->getData();
			$data['akses'] = $this->md_prodi->getAkses($id_user);
			$this->load->view('vw_matkul',$data);		
		} else redirect('login','refresh');
	}
	
	public function ajax_list($id=null)
	{		
		$data = $this->md_matkul->getData($id,null);
		$ary = array();
		foreach($data as $d)
		{ 
			if ($d['stat']==1)
			{
				$row = array();
				$row[] = $d['kode_mk'];			
				$row[] = $d['thkur'];			
				$row[] = $d['nm_mk'];			
				$row[] = $d['sks'];			
				$row[] = $d['smt'];			
				if ($d['twr']==1) $tTwr="Ganjil";
				elseif ($d['twr']==2) $tTwr="Genap";				
				else $tTwr="Ganjil - Genap";				
				$row[] = $tTwr;			
				if ($d['jns']==1) $tJns="Wajib";
				else $tJns="Pilihan";				
				$row[] = $tJns;		
				if ($d['jenjang']=="S1") $strata="Sarjana ";
				elseif ($d['jenjang']=="S2") $strata="Magister ";
				elseif ($d['jenjang']=="S3") $strata="Doktor ";
				else $strata="Pendidikan Profesi ";
				$row[] = $strata.$d['nm_ps'];			
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_matkul(\"".$d['id_mk']."\")'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='hapus_matkul(\"".$d['id_mk']."\")'><i class='glyphicon glyphicon-trash'></i></a></div>";
				
				$ary[] = $row;
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listMK($ps)
	{		
		$smt = $this->session->userdata('id_smt_thesis');
		$pSm = substr($smt,-1);
		$data = $this->md_matkul->getDataKls($smt,$ps);
		$ary = array();
		$n=0;
		foreach($data as $d)
		{ 
			$n++;
				
			$row = array();
			$row[] = $d['kode_mk'];			
			$row[] = $d['nm_mk'];
			if ($d['twr']==3)
			{
				if ($pSm==1) $sem = "<span class='label label-success'>GANJIL</span>";				
				else $sem = "<span class='label label-success'>GENAP</span>";				
			}
			else
			{
				if ($d['twr']==1) 
				{
					if ($pSm==1) $sem = "<span class='label label-success'>";
					else $sem = "<span class='label label-danger'>";
					$sem .= "GANJIL</span>"; 
				}
				else 
				{
					if ($pSm==1) $sem = "<span class='label label-danger'>";
					else $sem = "<span class='label label-success'>";
					$sem .= "GENAP</span>";
				}
			}
			$row[] = $sem;	
			if (empty($d['ct'])) $jml=""; else $jml=$d['ct'];
			$row[] = $jml;			
			if (empty($d['mx'])) $ada=0; else $ada=$d['mx'];
			$max = 26 - $ada;
			$cb = "<input type='hidden' name='tx".$n."' value='".$d['id_mk']."'>";
			if ($max>0) 
			{
				$cb .= "<select name='cb".$n."'>";
				for ($i=0; $i<=$max; $i++) 
					$cb .= "<option value='".$ada."-".$i."'>".$i."</option>";
				$cb .= "</select>";
			}
			$row[] = $cb;
			
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_edit($id)
	{
		$data = $this->md_matkul->getData($id,null);
		$row = array(
			'id_mk' => $data->id_mk,
			'kode_mk' => $data->kode_mk,		
			'thkur' => $data->thkur,		
			'nm_mk' => $data->nm_mk,
			'sks' => $data->sks,
			'smt' => $data->smt,
			'jns' => $data->jns,
			'twr' => $data->twr,
			'ps' => $data->id_ps
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function add_data()
	{
		$data = array(
			'id_mk' => $this->md_matkul->getMaxID(),
			'kode_mk' => $this->input->post('kode'),			
			'thkur' => $this->input->post('thkur'),			
			'nm_mk' => $this->input->post('nama'),
			'id_ps' => $this->input->post('prodi'),
			'sks' => $this->input->post('sks'),
			'smt' => $this->input->post('sem'),
			'jns' => $this->input->post('jenis'),
			'twr' => $this->input->post('tawar')
		);
		$this->md_matkul->save($data);
		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$data['id_log_mk'] = $this->md_matkul->getMaxLogID($th);
		$data['id_user'] = $this->session->userdata('id_user_thesis');;
		$data['dt'] = $tgl." ".$jam;
		$data['act'] = 1;
		$this->md_matkul->saveLog($data);		
				
		echo json_encode(array("status" => TRUE, "save" => TRUE));
	}
	public function update_data()
	{
		$dt = $this->md_matkul->getData($this->input->post('idMatkul'),null);
		$n = 0; 
		$lbDB = array ('kode_mk','thkur','nm_mk','id_ps','sks','smt','jns','twr');
		$lbForm = array ('kode','thkur','nama','prodi','sks','sem','jenis','tawar');
		for ($i=0; $i<count($lbDB); $i++)
		{
			if (trim($dt->$lbDB[$i])!=trim($this->input->post($lbForm[$i]))) 
			{ 
				$n++; 
				$dtLog[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
				$data[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
			} else $dtLog[$lbDB[$i]] = null;
		}
		
		if ($n>0)
		{ 
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$dtLog['id_mk'] = $this->input->post('idMatkul');
			$dtLog['id_log_mk'] = $this->md_matkul->getMaxLogID($th);
			$dtLog['id_user'] = $this->session->userdata('id_user_thesis');;
			$dtLog['dt'] = $tgl." ".$jam;
			$dtLog['act'] = 2;
			
			$this->md_matkul->update($data,array('id_mk' => $this->input->post('idMatkul'))); 
			$this->md_matkul->saveLog($dtLog);
			$save = 1;
		} else $save = 0;
		
		echo json_encode(array("status" => TRUE, "save" => $save));
	}	
	public function delete_data()
	{
		$data['stat'] = 0;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$dtLog['id_mk'] = $this->input->post('idHapus');
		$dtLog['id_log_mk'] = $this->md_matkul->getMaxLogID($th);
		$dtLog['id_user'] = $this->session->userdata('id_user_thesis');;
		$dtLog['dt'] = $tgl." ".$jam;
		$dtLog['act'] = 3;		
		
		$this->md_matkul->update($data,array('id_mk' => $this->input->post('idHapus'))); 
		$this->md_matkul->saveLog($dtLog);
		echo json_encode(array("status" => TRUE));
	}	
}
?>