<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dosen extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_dosen');				
		$this->load->model('md_pangkat');				
		$this->load->model('md_prodi');				
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->helper('url');		
			$id_user = $this->session->userdata('id_user_jadwal');
			$data['pangkat'] = $this->md_pangkat->getData();
			$data['prodi'] = $this->md_prodi->getData();
			$this->load->view('vw_dosen',$data);		
		} else redirect('login','refresh');
	}
	
	public function ajax_jmlBimbingan($var)
	{
		$tmp = explode("-",$var);
		$data = $this->md_dosen->getJmlBimbingan($tmp[0]);
		$row = array(
			'jml' => $data->jml,
			'comp' => $tmp[1]
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function ajax_jmlBimbPS($var)
	{
		$tmp = explode("-",$var);
		if (count($tmp)==3)
		{
			$data = $this->md_dosen->getJmlBimbPS($tmp[0],$tmp[2]);
			$row = array(
				'jml1' => $data->jml1,
				'jml2' => $data->jml2,
				'comp' => $tmp[1]
			);			
			$output = array("data" => $row);
		}
		echo json_encode($output);
	}
	
	public function ajax_edit_user($id)
	{
		$data = $this->md_dosen->getDataUser($id);
		$row = array(
			'id_user' => $data->id_user,
			'id_dosen' => $data->id_dosen,
			'nm_dosen' => $data->nm_dosen,		
			'id_jur' => $data->id_jur,		
			'id_ps' => $data->id_ps,
			'jenjang' => $data->jenjang,
			'nm_ps' => $data->nm_ps
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function ajax_edit($id)
	{
		$data = $this->md_dosen->getData($id);
		$row = array(
			'id_dosen' => $data->id_dosen,
			'id_ps' => $data->id_ps,		
			'no_dosen' => $data->no_dosen,		
			'nidn' => $data->nidn,
			'id_pangkat' => $data->id_pangkat,
			'jabfung' => $data->jabfung,
			'nm_dosen' => $data->nm_dosen,
			'gelar1' => $data->gelar1,
			'gelar2' => $data->gelar2,
			'jk' => $data->jk,
			'stat_dosen' => $data->stat_dosen,
			'asal_dosen' => $data->asal_dosen,
			'pend_akhir' => $data->pend_akhir
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function add_data()
	{
		$ID = $this->input->post('no');
		$cekID = $this->md_dosen->cekID(null,$ID);
		if (strlen($cekID)==1) 
		{
			$ada = false;
			$jenis = $this->input->post('jenis');
			if ($jenis<3) 
			{
				$asal = null;
				$prodi = $this->input->post('prodi');
			}
			else
			{
				if ($jenis==5) $asal = $this->input->post('txAsal');
				else $asal = $this->input->post('cbAsal');
				$prodi = null;
			}
			$data = array(
				'id_dosen' => $this->md_dosen->getMaxID(),
				'id_ps' => $prodi,
				'asal_dosen' => $asal,
				'no_dosen' => trim($this->input->post('no')),			
				'nidn' => $this->input->post('nidn'),
				'id_pangkat' => $this->input->post('gol'),
				'jabfung' => $this->input->post('jab'),
				'nm_dosen' => addslashes(trim($this->input->post('nama'))),
				'gelar1' => trim($this->input->post('gelar1')),
				'gelar2' => trim($this->input->post('gelar2')),
				'jk' => $this->input->post('jk'),
				'stat_dosen' => $jenis,				
				'pend_akhir' => $this->input->post('pend')
			);
			$this->md_dosen->save($data);
			
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$data['id_log_dosen'] = $this->md_dosen->getMaxLogID($th);
			$data['id_user'] = $this->session->userdata('id_user_jadwal');;
			$data['dt'] = $tgl." ".$jam;
			$data['act'] = 1;
			$this->md_dosen->saveLog($data);
		}	
		else $ada=true;			
				
		echo json_encode(array("status"=>TRUE, "save"=>TRUE, "ada"=>$ada));
	}
	public function update_data()
	{
		$dt = $this->md_dosen->getData($this->input->post('idDosen'),null);		
		$n=0; $ada=false;
		$lbDB = array ('id_ps','no_dosen','nidn','id_pangkat','jabfung','nm_dosen','gelar1','gelar2','jk','stat_dosen','asal_dosen','pend_akhir');		
		$lbForm = array ('prodi','no','nidn','gol','jab','nama','gelar1','gelar2','jk','jenis','asal','pend');		
		$jenis = $this->input->post('jenis');
		if ($jenis==6 or $jenis<4) $lbForm[10]='txAsal';
		else $lbForm[10]='cbAsal';
		for ($i=0; $i<count($lbDB); $i++)
		{
			if (trim($dt->$lbDB[$i])!=trim($this->input->post($lbForm[$i]))) 
			{ 
				$n++; 
				if ($lbForm[$i]=='jenis')
				{
					$dtDB = $dt->$lbDB[$i];
					$dtFM = $this->input->post($lbForm[$i]);
					if ($dtDB<4 and $dtFM>3) $data['id_ps']=null;
				}
				
				if ($lbForm[$i]=='nama')
				{
					$dtLog[$lbDB[$i]] = addslashes(trim($this->input->post($lbForm[$i])));
					$data[$lbDB[$i]] = addslashes(trim($this->input->post($lbForm[$i])));
				}
				else
				{
					$dtLog[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
					$data[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
					
					if ($lbForm[$i]=='no')
					{
						$ID = $this->input->post('no');
						$cekID = $this->md_dosen->cekID($this->input->post('idDosen'),$ID);
						if (strlen($cekID)>1) $ada=true;
					}
				}
			} else $dtLog[$lbDB[$i]] = null;
		}
		
		if ($n>0 and !$ada)
		{ 
			$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
			$th = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');
			$dtLog['id_dosen'] = $this->input->post('idDosen');
			$dtLog['id_log_dosen'] = $this->md_dosen->getMaxLogID($th);
			$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
			$dtLog['dt'] = $tgl." ".$jam;
			$dtLog['act'] = 2;
			
			$this->md_dosen->update($data,array('id_dosen' => $this->input->post('idDosen'))); 
			$this->md_dosen->saveLog($dtLog);
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
		$dtLog['id_dosen'] = $this->input->post('idHapus');
		$dtLog['id_log_dosen'] = $this->md_dosen->getMaxLogID($th);
		$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
		$dtLog['dt'] = $tgl." ".$jam;
		$dtLog['act'] = 3;		
		
		$this->md_dosen->update($data,array('id_dosen' => $this->input->post('idHapus'))); 
		$this->md_dosen->saveLog($dtLog);
		echo json_encode(array("status" => TRUE));
	}	
}
?>