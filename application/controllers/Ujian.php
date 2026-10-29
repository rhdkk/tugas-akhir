<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ujian extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mysql_table');	
		$this->load->library('excel');					
		$this->load->model('md_ujian');			
		$this->load->model('md_tuji');			
		$this->load->model('md_syarat');			
		$this->load->model('md_berkas');			
		$this->load->model('md_kriteria');			
		$this->load->library('mine');	
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_thesis');
		if (!empty($hak))
		{
			$this->load->helper('url');					
			$id_user = $this->session->userdata('id_user_thesis');
			$data['sesi'] = $this->md_sesi->getData();
			$data['dosen'] = $this->md_dosen->getData();
			$data['ruang'] = $this->md_ruang->getData();
			$data['akses'] = $this->md_prodi->getAkses($id_user);
			$data['jalur'] = $this->md_prodi->getJalur($data['akses'][0]['id_ps']);						
			$data['mhs'] = $this->md_mahasiswa->getDetil($id_user);						
			$this->load->view('vw_kelas',$data);
		} else redirect('login','refresh');
	}
	
	/* fungsi hilangkan NBSP, rapikan whitespace, trim */
	protected function norm ($v) 
	{
		if ($v === null) return '';
		$v = (string) $v;
		$v = preg_replace('/\x{00A0}/u', ' ', $v); 
		$v = preg_replace('/\s+/u', ' ', $v);      
		return trim($v);
	}
	
	public function ajax_list_syarat($id)
	{		
		$data = $this->md_tuji->getSyaratUji($id);
		$ary = array();
		$nm_tahap = "";
		foreach($data as $d)		
		{	
			$row = array();
			if ($d['jns_syarat']==1) $tJns = "Wajib";
			elseif ($d['jns_syarat']==2) $tJns = "Tidak";
			
			$nm_tahap = $d['nm_tuji'];
			$row[] = $d['urut_syarat'];
			$row[] = $d['nm_berkas'];
			$row[] = $tJns;
			$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_syarat(\"".$d['id_syarat']."\")'><i class='fas fa-pencil-alt'></i></a></div>";
			
			$ary[] = $row;
		}
		$output = array("id_tahap"=>$id, "nm_tahap"=>$nm_tahap, "data"=>$ary);
		echo json_encode($output);		
	}
	
	public function ajax_list_nilai($id)
	{		
		$data = $this->md_tuji->getKriteriaNilai($id);
		$ary = array();
		$nm_tahap = "";
		foreach($data as $d)		
		{	
			$row = array();
			$nm_tahap = $d['nm_tuji'];
			$row[] = $d['nm_kriteria'];
			$row[] = $d['pers_nilai'];
			$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_kriteria(\"".$d['id_kriteria']."\")'><i class='fas fa-pencil-alt'></i></a></div>";
			
			$ary[] = $row;
		}
		$output = array("id_tahap"=>$id, "nm_tahap"=>$nm_tahap, "data"=>$ary);
		echo json_encode($output);		
	}
	
	public function ajax_unlisted_syarat($id)
	{		
		$data = $this->md_tuji->getUnlistedSyarat($id);
		$ary = array();
		foreach($data as $d)		
		{	
			$row = array();
			$row[] = $d['id_berkas'];
			$row[] = $d['nm_berkas'];			
			$ary[] = $row;
		}
		$output = array("data"=>$ary);
		echo json_encode($output);		
	}
	
	public function add_syarat()
	{
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		
		$urut = $this->md_syarat->getMaxUrut($this->input->post('idTahapSyarat'));		
		$idSyarat = $this->md_syarat->getMaxID();
		$dtSyarat['id_syarat'] = $idSyarat;
		$dtSyarat['id_tuji'] = $this->input->post('idTahapSyarat');
		if (!empty($this->input->post('txSyarat')))			
		{
			$dtBerkas['id_berkas'] = $this->md_berkas->getMaxID();
			$dtBerkas['nm_berkas'] = $this->norm($this->input->post('txSyarat'));
			$this->md_berkas->save($dtBerkas);
			
			$dtBerkas['id_log_berkas'] = $this->md_berkas->getMaxLogID($th);
			$dtBerkas['id_user'] = $this->session->userdata('id_user_thesis');;
			$dtBerkas['dt'] = $tgl." ".$jam;
			$dtBerkas['act'] = 1;		
			$this->md_berkas->saveLog($dtBerkas);
			
			$dtSyarat['id_berkas'] = $dtBerkas['id_berkas'];
		}
		else $dtSyarat['id_berkas'] = $this->input->post('cbSyarat');
		$dtSyarat['jns_syarat'] = $this->input->post('jenisSyarat');
		$dtSyarat['urut_syarat'] = $urut;
		$this->md_syarat->save($dtSyarat);
		
		$dtSyarat['id_log_syarat'] = $this->md_syarat->getMaxLogID($th);
		$dtSyarat['id_user'] = $this->session->userdata('id_user_thesis');;
		$dtSyarat['dt'] = $tgl." ".$jam;
		$dtSyarat['act'] = 1;		
		$this->md_syarat->saveLog($dtSyarat);
		$save = 1;
		
		echo json_encode(array("status"=>TRUE, "save"=>$save));
	}
	
	public function add_kriteria()
	{
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		
		$idKrit = $this->md_kriteria->getMaxID();
		$dtKrit['id_kriteria'] = $idKrit;
		$dtKrit['id_tuji'] = $this->input->post('idTahapNilai');
		$dtKrit['nm_kriteria'] = $this->input->post('nilai');
		$dtKrit['pers_nilai'] = $this->input->post('pNilai');
		$this->md_kriteria->save($dtKrit);
		
		$dtKrit['id_log_kriteria'] = $this->md_kriteria->getMaxLogID($th);
		$dtKrit['id_user'] = $this->session->userdata('id_user_thesis');;
		$dtKrit['dt'] = $tgl." ".$jam;
		$dtKrit['act'] = 1;		
		$this->md_kriteria->saveLog($dtKrit);
		$save = 1;
		
		echo json_encode(array("status"=>TRUE, "save"=>$save));
	}
	
	public function update_syarat()
	{
		$idSyarat = $this->input->post('idSyarat');
		$dt = $this->md_syarat->getData(null,$idSyarat);

		$lbDB   = ['id_berkas', 'jns_syarat'];
		$lbForm = ['cbSyarat', 'jenisSyarat'];

		$data = [];
		$pen = 0;
		$n = 0;
		
		$wkt = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
		$th  = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
	
		if (!empty($this->input->post('txSyarat')))
		{	
			$dtBerkas['id_berkas'] = $this->md_berkas->getMaxID();
			$dtBerkas['nm_berkas'] = $this->norm($this->input->post('txSyarat'));
			$this->md_berkas->save($dtBerkas);
			
			$dtBerkas['id_log_berkas'] = $this->md_berkas->getMaxLogID($th);
			$dtBerkas['id_user'] = $this->session->userdata('id_user_thesis');;
			$dtBerkas['dt'] = $tgl." ".$jam;
			$dtBerkas['act'] = 1;		
			$this->md_berkas->saveLog($dtBerkas);
			
			$idBerkas = $dtBerkas['id_berkas'];
		}
		else $idBerkas = $this->input->post('cbSyarat');
		$sama = ($dt->id_berkas === $idBerkas);
		if (!$sama) 
		{
			$n++;
			$data['id_berkas'] = $idBerkas;
		}

		$sama = ($dt->jns_syarat === $this->input->post('jenisSyarat'));
		if (!$sama) 
		{
			$n++;
			$data['jns_syarat'] = $this->input->post('jenisSyarat');			
		}
		
		$save = 0;
		if ($n>0) 
		{
			$this->md_syarat->update($data, ['id_syarat' => $idSyarat]);

			$data['id_syarat'] = $idSyarat;
			$data['id_log_syarat'] = $this->md_syarat->getMaxLogID($th);
			$data['id_user'] = $this->session->userdata('id_user_thesis');
			$data['dt'] = $tgl.' '.$jam;
			$data['act'] = 2;
			
			$this->md_syarat->saveLog($data);
			$save = 1;
		}

		echo json_encode(["status"=>TRUE, "save"=>$save]);
	}
	
	public function update_kriteria()
	{
		$idKrit = $this->input->post('idNilai');
		$dt = $this->md_kriteria->getData(null, $idKrit);

		$lbDB   = ['nm_kriteria', 'pers_nilai'];
		$lbForm = ['nilai', 'pNilaiThp'];

		$data = [];
		$pen = 0;
		$n = 0;

		for ($i=0; $i<count($lbDB); $i++) 
		{
			$dbField = $lbDB[$i];
			$formField = $lbForm[$i];

			$rawDB = $dt->$dbField;
			$rawForm = $this->input->post($formField);

			$valDB = $this->norm($rawDB);
			$valForm = $this->norm($rawForm);
			
			$sama = ($valDB === $valForm);			
			if (!$sama) 
			{
				$n++;
				$data[$dbField] = $this->norm($rawForm);				
			}
		}
		
		$save = 0;
		if ($n>0) 
		{
			$this->md_kriteria->update($data, ['id_kriteria' => $idKrit]);

			$wkt = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
			$th  = $wkt->format('y');
			$tgl = $wkt->format('Y-m-d');
			$jam = $wkt->format('H:i:s');

			$data['id_kriteria'] = $idKrit;
			$data['id_log_kriteria'] = $this->md_kriteria->getMaxLogID($th);
			$data['id_user'] = $this->session->userdata('id_user_thesis');
			$data['dt'] = $tgl.' '.$jam;
			$data['act'] = 2;
			
			$this->md_kriteria->saveLog($data);
			$save = 1;
		}

		echo json_encode(["status"=>TRUE, "save"=>$save]);
	}
	
	public function ajax_syarat($id)
	{
		$data = $this->md_syarat->getData(null,$id);
		$row = array(
			'id_syarat' => $data->id_syarat,
			'id_tuji' => $data->id_tuji,		
			'id_berkas' => $data->id_berkas,		
			'nm_berkas' => $data->nm_berkas,		
			'jns_syarat' => $data->jns_syarat,
			'urut_syarat' => $data->urut_syarat
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function ajax_kriteria($id)
	{
		$data = $this->md_kriteria->getData(null,$id);
		$row = array(
			'id_kriteria' => $data->id_kriteria,
			'id_tuji' => $data->id_tuji,		
			'nm_kriteria' => $data->nm_kriteria,			
			'pers_nilai' => $data->pers_nilai		
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function expPDF($tipe,$id)
	{
		if ($tipe=="ba")
		{
			$data = $this->md_ujian->getDetUji($id);
			$ary = array();	
			foreach($data as $d)
			{			
				$nm="";
				if (trim($d['gelar1'])!="") $nm .= $d['gelar1']." ";
				$nm .= $d['nm_dosen'];
				if (trim($d['gelar2'])!="") $nm .= ", ".$d['gelar2'];
				
				$row['nim'] = $d['nim'];
				$row['tgl_uji'] = $d['tgl_uji'];
				$row['jam1_uji'] = $d['jam1_uji'];
				$row['jam2_uji'] = $d['jam2_uji'];
				$row['nm_ruang'] = $d['nm_ruang'];
				$row['nilai_uji'] = $d['nilai_uji'];
				$row['nm_dosen'] = $nm;
				$row['no_dosen'] = $d['no_dosen'];
				$row['nm_mhs'] = $d['nm_mhs'];
				$row['nm_ps'] = $d['nm_ps'];
				$row['nm_jur'] = $d['nm_jur'];
				$row['jenjang'] = $d['jenjang'];
				$row['judul_ta'] = $d['judul_ta'];
				$row['revisi_uji'] = $d['revisi_uji'];
				$row['nm_tuji'] = $d['nm_tuji'];
				$row['urut_tuji'] = $d['urut_tuji'];
				$row['jml_uji'] = $d['jml_uji'];
				$ary[] = $row;					
			}
		}
		elseif ($tipe=="nu" or $tipe=="ru")
		{
			$dtDetNilai = $this->md_ujian->getDetNilai($id);
			$tAry1 = array();	
			$i=0; 
			foreach($dtDetNilai as $d)
			{			
				$i++; $nm="";
				if (trim($d['gelar1'])!="") $nm .= $d['gelar1']." ";
				$nm .= $d['nm_dosen'];
				if (trim($d['gelar2'])!="") $nm .= ", ".$d['gelar2'];
				
				if ($i==1) 
				{
					$minID = $d['id_kriteria'];
					$idx = $d['no_dosen'];
				}
				
				if ($d['no_dosen']!=$idx)
				{
					$row['det'] = $aryDet;
					$tAry1[] = $row;
					$idx = $d['no_dosen'];
					unset($aryDet);
				}
				
				$row['nim'] = $d['nim'];
				$row['tgl_uji'] = $d['tgl_uji'];
				$row['jam1_uji'] = $d['jam1_uji'];
				$row['jam2_uji'] = $d['jam2_uji'];
				$row['nm_ruang'] = $d['nm_ruang'];
				$row['nilai_uji'] = $d['nilai_uji'];
				$row['nm_dosen'] = $nm;
				$row['no_dosen'] = $d['no_dosen'];
				$row['jab_dosen'] = $d['jab_dosen'];
				$row['urut_dosen'] = $d['urut_dosen'];
				$row['nm_mhs'] = $d['nm_mhs'];
				$row['nm_ps'] = $d['nm_ps'];
				$row['nm_jur'] = $d['nm_jur'];
				$row['jenjang'] = $d['jenjang'];
				$row['judul_ta'] = $d['judul_ta'];
				$row['nm_tuji'] = $d['nm_tuji'];
				$row['nilai_p1'] = $d['nilai_p1'];
				$row['nilai_p2'] = $d['nilai_p2'];
				$row['n_p1'] = $d['n_p1'];
				$row['n_p2'] = $d['n_p2'];
				$row['jml_uji'] = $d['jml_uji'];
				$row['revisi_uji'] = $d['revisi_uji'];
				
				$rowDet['pers_nilai'] = $d['pers_nilai'];
				$rowDet['nm_kriteria'] = $d['nm_kriteria'];
				$rowDet['nilai'] = $d['nilai'];
				$rowDet['na'] = $d['nilai']*($d['pers_nilai']/100);
				$aryDet[] = $rowDet;
				
				if ($i==count($dtDetNilai))
				{
					$row['det'] = $aryDet;
					$tAry1[] = $row;
				}
			};
			$ary["nu"] = $tAry1;
			
			unset($row);
			$dtHistNilai = $this->md_ujian->getHistNilai($id);
			$tAry2 = array();	
			foreach($dtHistNilai as $d)
			{			
				$row['nilai_uji'] = $d['nilai_uji'];
				$row['pers_tuji'] = $d['pers_tuji'];
				$row['nm_tuji'] = $d['nm_tuji'];
				$row['urut_tuji'] = $d['urut_tuji'];
				$tAry2[] = $row;				
			}			
			$ary["hu"] = $tAry2;		
		}
		
		$data = [
			'tipe' => $this->security->xss_clean($tipe),
			'list' => $this->security->xss_clean($ary)
		];
		$this->load->view('vw_pdf',$data);				
	}
}
?>