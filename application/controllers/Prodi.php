<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prodi extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_prodi');
		$this->load->model('md_tuji');
	} 
	public function index() 
	{
		$hak = $this->session->userdata('hak_thesis');
		if (!empty($hak))
		{
			$this->load->helper('url');		
			$id_user = $this->session->userdata('id_user_thesis');
			$this->load->view('vw_prodi');		
		} else redirect('login','refresh');
	}
	public function view_jalur($id)
	{		
		$data = $this->md_prodi->getJalur($id);
		$ary =  array();
		foreach($data as $d) $ary[$d['id_jalur']] = $d['nm_jalur'];						
		echo json_encode($ary);
	}
	public function ajax_list($id=null)
	{		
		$data = $this->md_prodi->getData();
		$ary = array();
		foreach($data as $d)
		{ 
			if ($d['stat']==1)
			{
				$row = array();
				if ($d['id_jur']==11) $tJur = "Akuntansi";
				elseif ($d['id_jur']==12) $tJur = "Ekonomi Pembangunan";
				else $tJur = "Manajemen";
				
				if ($d['jenjang']=="S1") $tJen = "Sarjana";
				elseif ($d['jenjang']=="S2") $tJen = "Magister";
				elseif ($d['jenjang']=="S3") $tJen = "Doktor";
				else $tJen = "Profesi";
				
				if ($d['tipe_reg']==1) $txReg="Ya";
				elseif ($d['tipe_reg']==2) $txReg="Tidak";
				else $txReg="";
				
				if ($d['tipe_rev']==1) $txRev="Pembimbing";
				elseif ($d['tipe_rev']==2) $txRev="Pemb. & Peng.";
				else $txRev="";
				
				$row[] = $d['nm_jur'];			
				$row[] = $tJen." ".$d['nm_ps'];
				$row[] = $d['p1'];
				$row[] = $d['p2'];
				$row[] = $txReg;
				$row[] = $txRev;
				$row[] = $d['n_uji'];
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-info' href='javascript:void()' title='Tahap Ujian' onclick='det_tahap(\"".$d['id_ps']."\")'><i class='glyphicon glyphicon-stats'></i></a><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_prodi(\"".$d['id_ps']."\")'><i class='fas fa-pencil-alt'></i></a></div>";
				
				$ary[] = $row;
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	public function ajax_list_tahap($ps=null)
	{		
		$data = $this->md_tuji->getData(null,$ps);
		$nm_ps = "";
		$ary = array();
		$total = count($data);
		foreach($data as $d)		
		{	
			if (empty($nm_ps) && isset($d['nm_ps'])) 
			{ 
				if ($d['jenjang']=="S1") $tJen = "Sarjana";
				elseif ($d['jenjang']=="S2") $tJen = "Magister";
				elseif ($d['jenjang']=="S3") $tJen = "Doktor";
				else $tJen = "Profesi";
				$nm_ps=$tJen." ".$d['nm_ps'];
				$id_ps=$d['id_ps']; 
				$p1=$d['p1']; 
				$p2=$d['p2']; 
			}
			if ($d['stat']==1)
			{
				$row = array();
				if ($d['jns_tuji']==1) $tJns = "Wajib";
				elseif ($d['jns_tuji']==2) $tJns = "Tidak";
				if ($d['penilai']==1) $tPen = "Pembimbing & Penguji";
				elseif ($d['penilai']==2) $tPen = "Pembimbing";
				
				$row[] = $d['urut_tuji'];
				$row[] = $d['nm_tuji'];
				$row[] = $tJns;
				$row[] = $tPen;
				$row[] = $d['pers_tuji'];
				$row[] = $d['nilai_p1'];
				$row[] = $d['nilai_p2'];
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-info' href='javascript:void()' title='Syarat' onclick='edit_tahap(\"".$d['id_tuji']."\")'><i class='fas fa-pencil-alt'></i></a><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_tahap(\"".$d['id_tuji']."\")'><i class='fas fa-pencil-alt'></i></a></div>";
				
				$ary[] = $row;
			}
		}
		$output = array("id_ps"=>$id_ps, "nm_ps"=>$nm_ps, "p1"=>$p1, "p2"=>$p2, "data"=>$ary);
		echo json_encode($output);		
	}
	public function ajax_edit($id)
	{
		$data = $this->md_prodi->getData($id);
		$row = array(
			'id_ps' => $data->id_ps,
			'id_jur' => $data->id_jur,		
			'nm_ps' => $data->nm_ps,		
			'jenjang' => $data->jenjang,
			'p1' => $data->p1,
			'p2' => $data->p2,
			'tipe_reg' => $data->tipe_reg,
			'tipe_rev' => $data->tipe_rev
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function ajax_tahap($id)
	{
		$data = $this->md_tuji->getData($id,null);
		$row = array(
			'id_ps' => $data->id_ps,
			'id_tuji' => $data->id_tuji,		
			'nm_tuji' => $data->nm_tuji,		
			'jns_tuji' => $data->jns_tuji,
			'urut_tuji' => $data->urut_tuji,
			'pers_tuji' => $data->pers_tuji,
			'penilai' => $data->penilai,
			'nilai_p1' => $data->nilai_p1,
			'nilai_p2' => $data->nilai_p2,
			'p1' => $data->p1,
			'p2' => $data->p2
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function update_data()
	{
		$dt = $this->md_prodi->getData($this->input->post('idProdi'),null);
		$n = 0; 
		$ada = false;
		$lbDB = array ('id_jur','nm_ps','jenjang','p1','p2','tipe_reg','tipe_rev');
		$lbForm = array ('jur','prodi','jen','pemb','peng','reg','rev');
		
		for ($i=0; $i<count($lbDB); $i++)
		{			
			if (trim($dt->$lbDB[$i])!=trim($this->input->post($lbForm[$i]))) 
			{ 
				$n++; 
				$dtLog[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
				$data[$lbDB[$i]] = trim($this->input->post($lbForm[$i]));
			} else $dtLog[$lbDB[$i]] = null;
		}
		
		$save = 0;
		if ($n>0)
		{ 
			$cekDt = $this->md_prodi->cekData($this->input->post('idProdi'),$this->input->post('jur'),$this->input->post('jen'),$this->input->post('prodi'));
			if (strlen($cekDt)>1) $ada=true;
			else{
				$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
				$th = $wkt->format('y');
				$tgl = $wkt->format('Y-m-d');
				$jam = $wkt->format('H:i:s');
				$dtLog['id_ps'] = $this->input->post('idProdi');
				$dtLog['id_log_ps'] = $this->md_prodi->getMaxLogID($th);
				$dtLog['id_user'] = $this->session->userdata('id_user_thesis');;
				$dtLog['dt'] = $tgl." ".$jam;
				$dtLog['act'] = 2;
				
				$this->md_prodi->update($data,array('id_ps' => $this->input->post('idProdi'))); 
				$this->md_prodi->saveLog($dtLog);
				$save = 1;
			}
		}
		
		echo json_encode(array("status"=>TRUE, "save"=>$save, "ada"=>$ada));
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
	
	public function add_tahap()
	{
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		
		$urut = $this->md_tuji->getMaxUrut($this->input->post('idProdiThp'));
		$penilai = $this->input->post('penilai');
		if ($penilai==1)
		{
			$nilaiP1 = $this->input->post('pPemb');
			$nilaiP2 = $this->input->post('pPeng');
			$nP1 = $this->input->post('nPemb');
			$nP2 = $this->input->post('nPeng');
		}
		else
		{
			$nilaiP1 = 100;
			$nilaiP2 = 0;
			$nP1 = $this->input->post('nPemb');
			$nP2 = 0;
		}
		
		$idTahap = $this->md_tuji->getMaxID();
		$dtPS['id_tuji'] = $idTahap;
		$dtPS['id_ps'] = $this->input->post('idProdiThp');
		$dtPS['nm_tuji'] = $this->input->post('tahap');
		$dtPS['jns_tuji'] = $this->input->post('jenis');
		$dtPS['urut_tuji'] = $urut;
		$dtPS['pers_tuji'] = $this->input->post('pNilai');
		$dtPS['penilai'] = $penilai;				
		$dtPS['nilai_p1'] = $nilaiP1;
		$dtPS['nilai_p2'] = $nilaiP2;
		$dtPS['n_p1'] = $nP1;
		$dtPS['n_p2'] = $nP2;
		$this->md_tuji->save($dtPS);
		
		$dtPS['id_log_tuji'] = $this->md_tuji->getMaxLogID($th);
		$dtPS['id_user'] = $this->session->userdata('id_user_thesis');;
		$dtPS['dt'] = $tgl." ".$jam;
		$dtPS['act'] = 1;		
		
		$this->md_tuji->saveLog($dtPS);
		$save = 1;
		
		echo json_encode(array("status"=>TRUE, "save"=>$save));
	}	
	
	public function update_tahap()
	{
		$idTahap = $this->input->post('idTahap');
		$dt = $this->md_tuji->getData($idTahap, null);

		$lbDB   = ['nm_tuji', 'jns_tuji', 'pers_tuji', 'penilai'];
		$lbForm = ['tahap',   'jenis',    'pNilai',    'penilai'];
		$lbInt = 	['jns_tuji'];

		/* fungsi hilangkan NBSP, rapikan whitespace, trim */
		$norm = function ($v) 
		{
			if ($v === null) return '';
			$v = (string) $v;
			$v = preg_replace('/\x{00A0}/u', ' ', $v); 
			$v = preg_replace('/\s+/u', ' ', $v);      
			return trim($v);
		};

		$data = [];
		$pen = 0;
		$n = 0;

		for ($i=0; $i<count($lbDB); $i++) 
		{
			$dbField = $lbDB[$i];
			$formField = $lbForm[$i];

			$rawDB = $dt->$dbField;
			$rawForm = $this->input->post($formField);

			if (in_array($dbField, $lbInt, true)) 
			{
				$valDB = ($rawDB === null || $rawDB === '') ? '' : (string)(int)$rawDB;
				$tValForm = ($rawForm === null || $rawForm === '') ? '' : (string)(int)$rawForm;
			} 
			else 
			{
				$valDB = $norm($rawDB);
				$valForm = $norm($rawForm);
			}

			$sama = ($valDB === $valForm);			
			if (!$sama) 
			{
				$n++;
				$data[$dbField] = $norm($rawForm);
				if ($i===3) $pen = $norm($rawForm); 				
			}
		}
		
		if ($pen>0)
		{
			$data['nilai_p1'] = ($pen==="1") ? $this->input->post('pPemb') : 100;
			$data['nilai_p2'] = ($pen==="1") ? $this->input->post('pPeng') : 0;
			$data['n_p2'] = ($pen==="1") ? $this->input->post('nPeng') : 0;;
		}
		else
		{
			if ($dt->penilai === "1")
			{
				if ($dt->nilai_p1 != $this->input->post('pPemb')) $data['nilai_p1'] = $this->input->post('pPemb');
				if ($dt->nilai_p2 != $this->input->post('pPeng')) $data['nilai_p2'] = $this->input->post('pPeng');
			}
		}
		
		$save = 0;
		if ($n>0) 
		{
			$this->md_tuji->update($data, ['id_tuji' => $idTahap]);

			$wkt = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
			$th  = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');

			$data['id_tuji'] = $idTahap;
			$data['id_log_tuji'] = $this->md_tuji->getMaxLogID($th);
			$data['id_user'] = $this->session->userdata('id_user_thesis');
			$data['dt'] = $tgl.' '.$jam;
			$data['act'] = 2;
			
			$this->md_tuji->saveLog($data);
			$save = 1;
		}

		echo json_encode(["status"=>TRUE, "save"=>$save]);
	}
}
?>