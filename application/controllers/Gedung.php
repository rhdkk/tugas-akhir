<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gedung extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_gedung');				
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hak_room');
		if (!isset($hak)) $this->load->view('vw_gedung');		
		else $this->load->view('vw_gedung');
	}
	
	public function ajax_list($id=null)
	{		
		$data = $this->md_gedung->getData($id);
		foreach($data as $d)
		{ 
			if ($d['stat']==1)
			{
				$row = array();
				$row[] = $d['kd_ged'];							
				$row[] = $d['nm_ged'];											
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit Data' onclick='edit_gedung(\"".$d['kd_ged']."\")'><i class='glyphicon glyphicon-edit'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus Data' onclick='hapus_gedung(\"".$d['kd_ged']."\")'><i class='glyphicon glyphicon-trash'></i></a></div>";
				
				$ary[] = $row;
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_edit($id)
	{
		$data = $this->md_gedung->getData($id);
		$row = array(
			'kd_ged' => $data->kd_ged,
			'nm_ged' => $data->nm_ged
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function add_data()
	{
		$ID = $this->input->post('kode');
		$cekID = $this->md_gedung->cekID(null,$ID);
		if (strlen($cekID)==1) 
		{
			$ada = false;
			$data = array(
				'kd_ged' => $this->input->post('kode'),			
				'nm_ged' => $this->input->post('nama')
			);
			$this->md_gedung->save($data);		
			
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$data['id_log_ged'] = $this->md_gedung->getMaxLogID($th);
			$data['id_user'] = $this->session->userdata('id_user_jadwal');;
			$data['dt'] = $tgl." ".$jam;
			$data['act'] = 1;
			$this->md_gedung->saveLog($data);
		} 
		else $ada=true;
		
		echo json_encode(array("status"=>TRUE, "save"=>TRUE, "ada"=>$ada));
	}
	public function update_data()
	{
		$dt = $this->md_gedung->getData($this->input->post('kdGed'));		
		$n=0; $ada=false;
		$lbDB = array ('kd_ged','nm_ged');		
		$lbForm = array ('kode','nama');		
		for ($i=0; $i<count($lbDB); $i++)
		{
			if (trim($dt->$lbDB[$i])!=trim($this->input->post($lbForm[$i]))) 
			{ 
				$n++; 
				if ($lbForm[$i]=='nama')
				{
					$dtLog[$lbDB[$i]] = addslashes(trim($this->input->post($lbForm[$i])));
					$data[$lbDB[$i]] = addslashes(trim($this->input->post($lbForm[$i])));
				}
				elseif ($lbForm[$i]=='kode')
				{
					$ID = $this->input->post('kode');
					$cekID = $this->md_gedung->cekID($this->input->post('kdGed'),$ID);
					if (strlen($cekID)>1) $ada=true;
				}			
			} else $dtLog[$lbDB[$i]] = null;
		}
		
		if ($n>0 and !$ada)
		{ 
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$dtLog['kd_ged'] = $this->input->post('kdGed');
			$dtLog['id_log_ged'] = $this->md_gedung->getMaxLogID($th);
			$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
			$dtLog['dt'] = $tgl." ".$jam;
			$dtLog['act'] = 2;
			
			$this->md_gedung->update($data,array('kd_ged' => $this->input->post('kdGed'))); 
			$this->md_gedung->saveLog($dtLog);
			$save = true;
		} else $save = false;
		
		echo json_encode(array("status"=>TRUE, "save"=>$save, "ada"=>$ada));
	}	
	public function delete_data()
	{
		$data['stat'] = 0;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$dtLog['kd_ged'] = $this->input->post('idHapus');
		$dtLog['id_log_ged'] = $this->md_gedung->getMaxLogID($th);
		$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
		$dtLog['dt'] = $tgl." ".$jam;
		$dtLog['act'] = 3;
		
		$this->md_gedung->update($data,array('kd_ged' => $this->input->post('idHapus'))); 
		$this->md_gedung->saveLog($dtLog);
		echo json_encode(array("status" => TRUE));
	}		
}