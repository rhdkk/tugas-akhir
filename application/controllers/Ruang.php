<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ruang extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_ruang');				
		$this->load->model('md_gedung');				
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->helper('url');		
			$id_user = $this->session->userdata('id_user_jadwal');
			$data['gedung'] = $this->md_gedung->getData();
			$this->load->view('vw_ruang',$data);		
		} else redirect('login','refresh');
	}
	
	public function ajax_list($id=null)
	{		
		$data = $this->md_ruang->getData($id,null);
		$ary = array();
		foreach($data as $d)
		{ 
			if ($d['stat']==1)
			{
				$row = array();
				$row[] = $d['kd_ruang'];			
				$row[] = $d['nm_ruang'];			
				$row[] = $d['nm_ged'];			
				$row[] = $d['lantai'];			
				$row[] = $d['kap_kul'];			
				$row[] = $d['kap_uji'];			
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_ruang(\"".$d['id_ruang']."\")'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='hapus_ruang(\"".$d['id_ruang']."\")'><i class='glyphicon glyphicon-trash'></i></a></div>";
				$ary[] = $row;
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_edit($id)
	{
		$data = $this->md_ruang->getData($id);
		$row = array(
			'id_ruang' => $data->id_ruang,
			'kd_ruang' => $data->kd_ruang,		
			'nm_ruang' => $data->nm_ruang,		
			'kd_ged' => $data->kd_ged,		
			'lantai' => $data->lantai,
			'kap_kul' => $data->kap_kul,
			'kap_uji' => $data->kap_uji
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function add_data()
	{
		$kd = trim($this->input->post('kode'));
		$nm = trim($this->input->post('ruang'));
		$cekKode = $this->md_ruang->cekData(null,'kd_ruang',$kd);
		$cekNama = $this->md_ruang->cekData(null,'nm_ruang',$nm);
		if (strlen($cekKode)>1) $ada="Y"; else $ada="N";			
		if (strlen($cekNama)>1) $ada.="Y"; else $ada.="N";					
		if ($ada=="NN") 
		{
			$data = array(
				'id_ruang' => $this->md_ruang->getMaxID(),
				'kd_ruang' => $kd,			
				'nm_ruang' => $nm,			
				'kd_ged' => $this->input->post('gedung'),			
				'lantai' => $this->input->post('lantai'),
				'kap_kul' => $this->input->post('kapkul'),
				'kap_uji' => $this->input->post('kapuji')
			);
			$this->md_ruang->save($data);
			
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$data['id_log_ruang'] = $this->md_ruang->getMaxLogID($th);
			$data['id_user'] = $this->session->userdata('id_user_jadwal');;
			$data['dt'] = $tgl." ".$jam;
			$data['act'] = 1;
			$this->md_ruang->saveLog($data);
		}
						
		echo json_encode(array("status"=>TRUE, "save"=>TRUE, "ada"=>$ada));
	}
	public function update_data()
	{
		$dt = $this->md_ruang->getData($this->input->post('idRuang'));
		$n = 0; 
		$cekNama = "x";
		$cekKode = "x";
		$lbDB = array ('kd_ruang','nm_ruang','kd_ged','lantai','kap_kul','kap_uji');
		$lbForm = array ('kode','ruang','gedung','lantai','kapkul','kapuji');
		for ($i=0; $i<count($lbDB); $i++)
		{
			if (trim($dt->$lbDB[$i])!=trim($this->input->post($lbForm[$i]))) 
			{ 
				$n++; 
				if ($lbForm[$i]=='ruang')
				{
					$nm = trim($this->input->post($lbForm[$i]));					
					$cekNama = $this->md_ruang->cekData($this->input->post('idRuang'),$lbDB[$i],$nm);
					if (strlen($cekNama)==1)
					{
						$dtLog[$lbDB[$i]] = addslashes($nm);
						$data[$lbDB[$i]] = addslashes($nm);
					}
				}
				elseif ($lbForm[$i]=='kode')
				{
					$kd = trim($this->input->post($lbForm[$i]));
					$cekKode = $this->md_ruang->cekData($this->input->post('idRuang'),$lbDB[$i],$kd);					
					
					$dtLog[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
					$data[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
				}
				else 				
				{
					$dtLog[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
					$data[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
				}
			} else $dtLog[$lbDB[$i]] = null;
		}
		
		if (strlen($cekKode)>1) $ada="Y"; else $ada="N";			
		if (strlen($cekNama)>1) $ada.="Y"; else $ada.="N";	
		if ($n>0 and $ada=="NN")
		{ 
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$dtLog['id_ruang'] = $this->input->post('idRuang');
			$dtLog['id_log_ruang'] = $this->md_ruang->getMaxLogID($th);
			$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
			$dtLog['dt'] = $tgl." ".$jam;
			$dtLog['act'] = 2;
			
			$this->md_ruang->update($data,array('id_ruang' => $this->input->post('idRuang'))); 
			$this->md_ruang->saveLog($dtLog);
			$save = 1;
		} else $save = 0;
		
		echo json_encode(array("status" => TRUE, "save" => $save, "ada"=>$ada));
	}	
	public function delete_data()
	{
		$data['stat'] = 0;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$dtLog['id_ruang'] = $this->input->post('idHapus');
		$dtLog['id_log_ruang'] = $this->md_ruang->getMaxLogID($th);
		$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
		$dtLog['dt'] = $tgl." ".$jam;
		$dtLog['act'] = 3;		
		
		$this->md_ruang->update($data,array('id_ruang' => $this->input->post('idHapus'))); 
		$this->md_ruang->saveLog($dtLog);
		echo json_encode(array("status" => TRUE));
	}	
}