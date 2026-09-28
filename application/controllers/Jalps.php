<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jalps extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_jalps');				
		$this->load->model('md_prodi');				
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->helper('url');		
			$id_user = $this->session->userdata('id_user_jadwal');
			$data['prodi'] = $this->md_prodi->getData();			
			$this->load->view('vw_jalps',$data);		
		} else redirect('login','refresh');
	}
	
	public function ajax_list()
	{		
		$data = $this->md_jalps->getData();
		$ary = array();
		foreach($data as $d)
		{ 
			$row = array();
			if ($d['jenjang']=="S1") $strata="S1 ";
			elseif ($d['jenjang']=="S2") $strata="S2 ";
			elseif ($d['jenjang']=="S3") $strata="S3 ";
			else $strata="Profesi ";
			$row[] = $strata.$d['nm_ps'];						
			$row[] = $d['kd1'];			
			$row[] = $d['kd2'];			
			$row[] = $d['kd3'];			
			$row[] = $d['kd4'];			
			$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_jalps(\"".$d['id_ps']."\")'><i class='glyphicon glyphicon-pencil'></i></a></div>";
			
			$ary[] = $row;
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_edit($id)
	{
		$data = $this->md_jalps->getData($id);
		if ($data->jenjang=="S1") $nmPS="S1 ".$data->nm_ps;
		elseif ($data->jenjang=="S2") $nmPS="S2 ".$data->nm_ps;
		elseif ($data->jenjang=="S1") $nmPS="S2 ".$data->nm_ps;
		else $nmPS="Profesi ".$data->nm_ps;		
		
		$row = array(
			'id_ps' => $data->id_ps,
			'nm_ps' => $nmPS,
			'kode1' => $data->kd1,
			'kode2' => $data->kd2,
			'kode3' => $data->kd3,
			'kode4' => $data->kd4			
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function add_data()
	{
		$data = array(
			'id_ps' => $this->input->post('prodi'),
			'id_jalur' => $this->input->post('jalur'),
			'kode' => $this->input->post('kode')
		);
		$this->md_jalps->save($data);
		
		/*$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$data['id_log_jalps'] = $this->md_matkul->getMaxLogID($th);
		$data['id_user'] = $this->session->userdata('id_user_jadwal');;
		$data['dt'] = $tgl." ".$jam;
		$data['act'] = 1;
		$this->md_matkul->saveLog($data);*/
				
		echo json_encode(array("status"=>TRUE, "save"=>TRUE));
	}
	public function update_data()
	{
		$dt = $this->md_jalps->getData($this->input->post('idPS'));
		$dtKode = array (31,32,33,34);
		$lbAsal = array ('edKode1','edKode2','edKode3','edKode4');
		$lbForm = array ('kode1','kode2','kode3','kode4');
		$save = false;
		for ($i=0; $i<count($lbForm); $i++)
		{
			if ($this->input->post($lbAsal[$i]) != $this->input->post($lbForm[$i]))
			{ 
				$data['kode'] = trim($this->input->post($lbForm[$i]));
				$dtLog['kode'] = trim($this->input->post($lbForm[$i]));
				if (strlen(trim($this->input->post($lbForm[$i])))>0)
				{
					$data['stat'] = 1;
					$this->md_jalps->update($data,array('id_ps'=>$this->input->post('idPS'),'id_jalur'=>$dtKode[$i])); 					
					$dtLog['act'] = 2;					
					$dtLog['id_jalur'] = $dtKode[$i];					
				}
				else 
				{
					$data['stat'] = 0;
					$dtLog['act'] = 3;
					$dtLog['id_jalur'] = $dtKode[$i];
					$this->md_jalps->update($data,array('id_ps'=>$this->input->post('idPS'),'id_jalur'=>$dtKode[$i])); 
				}
				
				$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
				$th = $wkt->format('y');
				$tgl = $wkt->format('Y-m-d');
				$jam = $wkt->format('H:i:s');
				$dtLog['id_ps'] = $this->input->post('idPS');
				$dtLog['id_log_jalps'] = $this->md_jalps->getMaxLogID($th);
				$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
				$dtLog['dt'] = $tgl." ".$jam;
				$this->md_jalps->saveLog($dtLog);
				$save = true;
			} 
		}
		
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
		$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
		$dtLog['dt'] = $tgl." ".$jam;
		$dtLog['act'] = 3;		
		
		$this->md_matkul->update($data,array('id_mk' => $this->input->post('idHapus'))); 
		$this->md_matkul->saveLog($dtLog);
		echo json_encode(array("status" => TRUE));
	}	
}