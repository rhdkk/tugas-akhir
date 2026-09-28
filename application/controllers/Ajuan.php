<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajuan extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_user');				
		$this->load->model('md_ajuan');		
		$this->load->model('md_jenis');		
		$this->load->model('md_berkas');		
		$this->load->model('md_proses');		
		$this->load->model('md_majelis');		
		$this->load->model('md_progress');		
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_layanan');
		if (!empty($hak))
		{
			$this->load->helper('url');		
			$id_user = $this->session->userdata('id_user_layanan');
			$ps = $this->session->userdata('ps_layanan');		
			$urut = $this->md_jenis->getMaxUjian($ps,$id_user);
			$data['ujian'] = $this->md_jenis->getUjian($ps,$urut);
			$data['layanan'] = $this->md_jenis->getLayanan($ps);
			$data['dosen'] = $this->md_user->getDosen();
			$this->load->view('vw_ajuan',$data);	
		} else redirect('login','refresh');		
	}	
	public function ajax_list()
	{		
		$idU = $this->session->userdata('id_user_layanan');
		$hak = $this->session->userdata('hak_layanan');	
		$dept = $this->session->userdata('dept_layanan');	
		$data = $this->md_ajuan->getData($idU,$hak,$dept);
		$proc[1] = 'verf_ajuan';
		$proc[2] = 'pilih_dosen';
		$proc[3] = 'set_dt';
		$proc[4] = 'up_ajuan';
		$proc[5] = 'skor_ajuan';
		$ary = array();
		foreach($data as $d)
		{ 
			$id = $d['id_ajuan']."-".$d['id_jenis']."-".$d['step'];
			$row = array();
			$tmp = explode("-",$d['dt']);
			$tgl = substr($tmp[0],0,4)."-".substr($tmp[0],4,2)."-".substr($tmp[0],6,2);
			$wkt = substr($tmp[1],0,2).":".substr($tmp[1],2,2).":".substr($tmp[1],4,2);
			$row[] = $d['nim'];			
			$row[] = $d['nm_user'];			
			$row[] = $d['nm_jenis'];			
			$row[] = $tgl." ".$wkt;
			$row[] = $d['nm_tahap'];	
			if ($hak==2)
			{
				if ($d['hak']==$hak)
					$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Proses' onclick='".$proc[$d['form']]."(\"".$id."\")'><i class='glyphicon glyphicon-share-alt'></i></a></div>";
				else $row[] = "";
			}
			else 
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Proses' onclick='".$proc[$d['form']]."(\"".$id."\")'><i class='glyphicon glyphicon-share-alt'></i></a></div>";			
			
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	public function ajax_listUjian()
	{		
		$idU = $this->session->userdata('id_user_layanan');
		$hak = $this->session->userdata('hak_layanan');	
		$dept = $this->session->userdata('dept_layanan');	
		$data = $this->md_ajuan->getUjian($idU,$hak,$dept);
		$proc[5] = 'skor_ajuan';
		$ary = array();
		foreach($data as $d)
		{ 
			$id = $d['id_ajuan']."-".$d['id_jenis']."-".$d['step'];
			$row = array();
			$tmp = explode("-",$d['dt']);
			$tgl = substr($tmp[0],0,4)."-".substr($tmp[0],4,2)."-".substr($tmp[0],6,2);
			$wkt = substr($tmp[1],0,2).":".substr($tmp[1],2,2).":".substr($tmp[1],4,2);
			$row[] = $d['nim'];			
			$row[] = $d['nm_user'];			
			$row[] = $d['nm_jenis'];			
			$row[] = $tgl." ".$wkt;
			$row[] = $d['nm_tahap'];	
			if ($hak==2)
			{
				if ($d['hak']==$hak)
					$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Proses' onclick='".$proc[$d['form']]."(\"".$id."\")'><i class='glyphicon glyphicon-share-alt'></i></a></div>";
				else $row[] = "";
			}
			else 
				$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Proses' onclick='".$proc[$d['form']]."(\"".$id."\")'><i class='glyphicon glyphicon-share-alt'></i></a></div>";			
			
			$ary[] = $row;			
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	public function ajax_ajuan($ids)
	{
		$tmp = explode("-",$ids);
		$id = $tmp[0];
		$jenis = $tmp[1];		
		$step = $tmp[2];		
		$data1 = $this->md_ajuan->viewData($id);		
		$data2 = $this->md_majelis->getPenguji($id);
		$data3 = $this->md_proses->getSyarat($jenis,$step);
		$data4 = $this->md_ajuan->getBerkas($id,$jenis,1);
		
		$aryDosen = array();
		foreach($data2 as $d)	
			$aryDosen [] = $d['nm_user']; 
			
		$arySyarat = array();
		foreach($data3 as $d)	
			$arySyarat [] = $d['id_tipe']; 
			
		$aryBerkas = array();
		foreach($data4 as $d)	
			$aryBerkas [] = $d['nm_berkas']; 
		
		$hartang=""; $wkt="";
		if (strlen(trim($data1->dt_ujian))>0)
		{
			$aryH = array("Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
			$aryB[1]="Januari";		$aryB[2]="Februari";	$aryB[3]="Maret";			$aryB[4]="April";			
			$aryB[5]="Mei";				$aryB[6]="Juni";			$aryB[7]="Juli";			$aryB[8]="Agustus";	
			$aryB[9]="September";	$aryB[10]="Oktober";	$aryB[11]="November";	$aryB[12]="Desember";			
			
			$tmp1 = explode('-',$data1->dt_ujian);
			$tmp2 = explode('.',$tmp1[1]);
			$th = substr($tmp1[0],0,4);
			$bl = substr($tmp1[0],4,2);
			$tg = substr($tmp1[0],6,2);
			$wkt1 = substr($tmp1[1],0,2).":".substr($tmp1[1],2,2);
			$wkt2 = substr($tmp1[1],5,2).":".substr($tmp1[1],7,2);
			$hartang = $aryH[date("w", strtotime($th."-".$bl."-".$tg))].", ".(int)$tg." ".$aryB[(int)$bl]." ".$th;
			$wkt = $wkt1." - ".$wkt2." WIB";
		}
			
		$row = array(
			'jenis' => $data1->nm_jenis,
			'nim' => $data1->nim,
			'nama' => $data1->nm_user,					
			'judul' => $data1->judul,
			'link' => $aryBerkas,
			'hartang' => $hartang,
			'wkt' => $wkt,
			'dosen' => $aryDosen,	
			'syarat' => $arySyarat		
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function add_data($nS)
	{		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Ymd');
		$jam = $wkt->format('His');
		$stat = true;
		$err = "";	
		if ($nS>0)
		{
			$config['upload_path'] = './upload/';			
			$config['overwrite'] = true;
			$config['max_size']  = 20971520;
			$config['allowed_types']='pdf';
			$this->load->library('upload');			
			for ($i=0; $i<$nS; $i++)
			{
				$nmFN = "idS".$i;
				$fn = $tgl.$jam.$this->input->post($nmFN).$this->session->userdata('nim_layanan');
				$config['file_name'] = $fn;
				$this->upload->initialize($config);
				$nmS = "syarat".$i;
				if ($this->upload->do_upload($nmS)) 
				{
					$idAjuan = $this->md_ajuan->getMaxID($th);					
					$dtAjuan = array(
						'id_ajuan' => $idAjuan,
						'id_mhs' => $this->session->userdata('id_user_layanan'),			
						'id_jenis' => $this->input->post('jenis'),															
						'step' => 2
					);
					if ($this->md_jenis->getTitle($this->input->post('jenis'))==2) 
						$dtAjuan['judul'] = $this->input->post('judul');
					$this->md_ajuan->save($dtAjuan);
					
					$nmT = "idS".$i;
					$idBerkas = $this->md_berkas->getMaxID($th);
					$dtBerkas = array(
						'id_berkas' => $idBerkas,
						'id_user' => $this->session->userdata('id_user_layanan'),			
						'id_tipe' => $this->input->post($nmT),									
						'nm_berkas' => $this->upload->data("file_name"),
						'dt' => $tgl."-".$jam						
					);
					$this->md_berkas->save($dtBerkas);
					
					$id_progress = $this->md_progress->getMaxID($th); 
					$dtPG = array(
						'id_progress' => $id_progress,
						'id_ajuan' => $idAjuan,
						'id_proses' => $this->md_proses->getIDProses($this->input->post('jenis'),1),
						'id_user' => $this->session->userdata('id_user_layanan'),
						'dt' => $tgl."-".$jam,
						'alt' => 1,
					);
					$this->md_progress->save($dtPG);
					
					$dtBP = array(
						'id_progress' => $idAjuan,
						'id_berkas' => $idBerkas
					);
					$this->md_progress->saveBerkas($dtBP);
				}
				else
				{
					$stat = false;
					$err = $this->upload->display_errors();
				}
			}
		}
		echo json_encode(array("status"=>$stat, "error"=>$err));
	}
	public function proses($ids)
	{
		$tmp = explode("-",$ids);
		$ops = (int)$tmp[0];
		$idAjuan = $tmp[1];
		$id_jenis = $tmp[2];
		$step = (int)$tmp[3];
		$act = (int)$tmp[4];
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Ymd');
		$jam = $wkt->format('His');		
		
		if ($act==2) $alt=0; else 
		{
			$alt=1;		
			$dtA['step'] = $step+1;			
			$this->md_ajuan->update($dtA,array('id_ajuan' => $idAjuan));			
		}
			
		$unik=true;		
		/*Verifikasi Proposal Skripsi - Kadep*/	
		if ($ops==1) 
		{	
			$dtPG = array(
				'id_progress' => $this->md_progress->getMaxID($th),
				'id_ajuan' => $idAjuan,
				'id_proses' => $this->md_proses->getIDProses($id_jenis,$step),
				'id_user' => $this->session->userdata('id_user_layanan'),
				'dt' => $tgl."-".$jam,
				'alt' => $alt
			);
			$this->md_progress->save($dtPG);
		}
		/*Penentuan Penguji Sidpro - Kajur*/	
		else if ($ops==2)
		{
			$nD = 2;
			for ($i=0; $i<$nD; $i++)
			{
				$dosen = $this->input->post('dosen'.$i);	
				$aryDosen[$i] = $dosen;
			}		
			if ($nD>count(array_unique($aryDosen))) $unik=false; 
			if ($unik)
			{
				$dtPG = array(
					'id_progress' => $this->md_progress->getMaxID($th),
					'id_ajuan' => $idAjuan,
					'id_proses' => $this->md_proses->getIDProses($id_jenis,$step),
					'id_user' => $this->session->userdata('id_user_layanan'),
					'dt' => $tgl."-".$jam,
					'alt' => $alt
				);
				$this->md_progress->save($dtPG);
			
				for ($i=0; $i<$nD; $i++)
				{
					$dtM = array(
						'id_majelis' => $this->md_majelis->getMaxID($th),
						'id_ajuan' => $idAjuan,
						'id_dosen' => $aryDosen[$i],
						'posisi' => 2,
						'urut' => $i+1
					);
					$this->md_majelis->save($dtM);
				}
			}
		}
		/*Set Waktu Pelaksanaan Ujian - Mhs*/	
		else if ($ops==3)
		{
			$dtPG = array(
				'id_progress' => $this->md_progress->getMaxID($th),
				'id_ajuan' => $idAjuan,
				'id_proses' => $this->md_proses->getIDProses($id_jenis,$step),
				'id_user' => $this->session->userdata('id_user_layanan'),
				'dt' => $tgl."-".$jam,
				'alt' => $alt
			);
			$this->md_progress->save($dtPG);
			
			$tgl = str_replace("-","",$this->input->post('tglF3'));			
			$mulai = str_replace(":","",$this->input->post('mulaiF3'));			
			$selesai = str_replace(":","",$this->input->post('selesaiF3'));
			if ($mulai > $selesai) 
			{
				$tmp = $mulai;
				$mulai = $selesai;
				$selesai = $tmp;
			}
			$dt = $tgl."-".$mulai.".".$selesai;
			$dtE['dt_ujian'] = $dt;
			$this->md_ajuan->update($dtE,array('id_ajuan' => $idAjuan));	
		}
		/*Penjadwalan Ujian - Admin PS*/	
		else if ($ops==4)
		{
			$id_progress = $this->md_progress->getMaxID($th); 
			$dtPG = array(
				'id_progress' => $id_progress,
				'id_ajuan' => $idAjuan,
				'id_proses' => $this->md_proses->getIDProses($id_jenis,$step),
				'id_user' => $this->session->userdata('id_user_layanan'),
				'dt' => $tgl."-".$jam,
				'alt' => $alt
			);
			$this->md_progress->save($dtPG);
			
			$config['upload_path'] = './upload/';			
			$config['overwrite'] = true;
			$config['max_size']  = 20971520;
			$config['allowed_types']='pdf';
			$this->load->library('upload');
			
			$fn = $tgl.$jam.$this->input->post("idB0").$this->session->userdata('id_user_layanan');
			$config['file_name'] = $fn;
			$this->upload->initialize($config);
			if ($this->upload->do_upload("berkas0")) 
			{
				$idBerkas = $this->md_berkas->getMaxID($th);
				$dtBerkas = array(
					'id_berkas' => $idBerkas,
					'id_user' => $this->session->userdata('id_user_layanan'),			
					'id_tipe' => $this->input->post('idB0'),									
					'nm_berkas' => $this->upload->data("file_name"),
					'dt' => $tgl."-".$jam						
				);
				$this->md_berkas->save($dtBerkas);
				
				$dtBP = array(
					'id_progress' => $idAjuan,
					'id_berkas' => $idBerkas
				);
				$this->md_progress->saveBerkas($dtBP);
				
				$dtM['id_berkas'] = $idBerkas;			
				$this->md_majelis->update($dtM,array('id_ajuan' => $idAjuan));
			}
		}
		
		echo json_encode(array("status" => TRUE, "unik" => $unik));
	}
	
	public function delete_data()
	{
		$idDel = $this->input->post('idHapus');
		$data['stat'] = 0;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$dtLog1['id_opt'] = $this->session->userdata('id_user_jadwal');
		$dtLog1['id_log_user'] = $this->md_user->getMaxLogID($th);
		$dtLog1['id_user'] = $idDel;
		$dtLog1['dt'] = $tgl." ".$jam;
		$dtLog1['act'] = 3;	
		$dtLog2['id_opt'] = $this->session->userdata('id_user_jadwal');
		$dtLog2['id_log_akses'] = $this->md_user->getMaxLogAksesID($th);
		$dtLog2['id_user'] = $idDel;
		$dtLog2['dt'] = $tgl." ".$jam;
		$dtLog2['act'] = 3;	
		
		$dt = $this->md_user->getData($idDel);
		foreach($dt as $d)
		{
			if (!empty($d['id_ps']))
			{
				$dtLog2['id_ps'] = $d['id_ps'];
				$this->md_user->updateAkses($data, array('id_user'=>$idDel));							
				$this->md_user->saveLogAkses($dtLog2);
			}
		}			
		$this->md_user->update($data, array('id_user'=>$idDel));
		$this->md_user->saveLog($dtLog1);
			
		echo json_encode(array("status" => TRUE));
	}			
}
?> 