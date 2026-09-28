<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mysql_table');	
		$this->load->library('excel');					
		$this->load->model('md_dosen');
		$this->load->model('md_ruang');
		$this->load->model('md_matkul');
		$this->load->model('md_prodi');		
		$this->load->model('md_dospem');		
		$this->load->model('md_set');		
		$this->load->model('md_smt');		
		$this->load->model('md_mahasiswa');		
		$this->load->model('md_bimbingan');		
		$this->load->model('md_progression');		
		$this->load->model('md_progress');		
		$this->load->model('md_tuji');		
		$this->load->model('md_ajuan');		
		$this->load->model('md_berma');			
		$this->load->model('md_ujian');			
		$this->load->model('md_nilai');			
		$this->load->model('md_revisi');	
		$this->load->library('mine');			
	} 
	
	public function index() 
	{
		$hak = $this->session->userdata('hak_thesis');
		if (!empty($hak))
		{
			$this->load->helper('url');					
			$id_user = $this->session->userdata('id_user_thesis');
			$data['dosen'] = $this->md_dosen->getData();
			$data['ruang'] = $this->md_ruang->getData();
			if ($hak!=5) $data['akses'] = $this->md_prodi->getAkses($id_user);		
			if ($hak==6)
			{
				$data['mhs'] = $this->md_mahasiswa->getDet($id_user);									
				$data['tim'] = $this->md_mahasiswa->getTim($data['mhs']->nim);									
				$data['history'] = $this->md_mahasiswa->getHistory($id_user);
				$data['tujian'] = $this->md_mahasiswa->getTUjian($id_user);
				$data['nUjian'] = $this->md_mahasiswa->getAllUji($data['mhs']->nim);
				if ($data['mhs']->urut_progress>=5 and $data['mhs']->urut_progress<=7)				
					$data['syarat'] = $this->md_tuji->getSyarat($data['mhs']->id_tuji,$data['mhs']->nim);
				if ($data['mhs']->urut_progress>=8)
					$data['ujian'] = $this->md_mahasiswa->getLastUji($data['mhs']->nim);
			}
			elseif ($hak<4)
			{
				$data['mhs'] = $this->md_mahasiswa->getDet($id_user);									
			}
			if ($hak<4) $this->load->view('vw_home_a',$data);			
			elseif ($hak==4 or $hak==5) $this->load->view('vw_home_d',$data);			
			elseif ($hak==6) $this->load->view('vw_home_m',$data);			
		} else redirect('login','refresh');
	}
	
	public function dateStr($dt,$kind)
	{
		$bln = array(1 => "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		$tTgl = explode("-",$dt);
		$strTgl = (int)$tTgl[2]." ".$bln[(int)$tTgl[1]]." ".$tTgl[0];		
		
		if ($kind==1)	return $strTgl;
		else if ($kind==2)	
		{
			$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
			$h = date("w", strtotime($dt));
			$strHari = $hr[$h].", ".$strTgl;
			return $strHari;
		}
	}
	
	public function ajax_nilaiUji()
	{		
		$idDU = $this->input->get('idDetUji');
		$data = $this->md_ujian->getKriteriaNilai($idDU);
		$ary = array();	
		foreach($data as $d)
		{			
			$row = array(
				'nim' => $d['nim'],
				'id_dospem' => $d['id_dospem'],
				'nm_mhs' => $d['nm_mhs'],
				'nm_tuji' => $d['nm_tuji'],
				'nm_ps' => $d['nm_ps'],
				'jenjang' => $d['jenjang'],
				'id_kriteria' => $d['id_kriteria'],
				'nm_kriteria' => $d['nm_kriteria'],
				'pers_nilai' => $d['pers_nilai'],
				'p1' => $d['p1'],
				'p2' => $d['p2'],
				'nilai_p1' => $d['nilai_p1'],
				'nilai_p2' => $d['nilai_p2'],
				'rata_pemb' => $d['rata_pemb'],
				'rata_peng' => $d['rata_peng'],
				'jml3' => $d['jml3'],
				'jml4' => $d['jml4'],
				'filename_uji' => $d['filename_uji'],
				'n_tim' => $d['n_tim']
			);							
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listRevisi($id)
	{		
		$data = $this->md_dosen->getRevisi($id);
		$ary = array();	$i=0;
		foreach($data as $d)
		{ 
			$i++;				
			$row = array(); 
			$row[] = $d['tgl_uji'];			
			$row[] = $d['nm_tuji'];	
			$tPS = $this->mine->PSstr($d['jenjang']);
			$tPS .= " ".$d['nm_ps'];			
			$row[] = $tPS;					
			$row[] = "<span style='font-weight:530'>".$d['nm_mhs']." </span> <br/>NIM. ".$d['nim']."";					
			if ($i==1) 
				$tBtn = "<a class='btn btn-xs btn-primary' href='javascript:void()' title='Verifikasi Revisi/Saran Ujian' onclick='set_rev(".$d['id_dospem'].",".$d['id_rev'].",".$d['tipe_rev'].")'>Verifikasi<br/>Revisi</a>";
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listPenilaian($id)
	{		
		$data = $this->md_dosen->getPenilaian($id);
		$ary = array();	$i=0;
		foreach($data as $d)
		{ 
			$i++;				
			$row = array(); 
			$row[] = $d['tgl_uji'];			
			$row[] = $d['nm_tuji'];			
			$row[] = "<span style='font-weight:530'>".$d['nm_mhs']." (".$d['nim'].")</span>";					
			if ($i==1) 
				$tBtn = "<a class='btn btn-xs btn-primary' href='javascript:void()' title='Penilaian Ujian' onclick='set_nilai(".$d['id_det_uji'].",".$d['jab_dosen'].",".$d['urut_dosen'].")'><i class='glyphicon glyphicon-th-list'></i></a>";
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listRevMhs($nim)
	{		
		$data = $this->md_revisi->getRevUji($nim);
		$ary = array();	
		foreach($data as $d)
		{ 
			$row = array(); 
			if ($d['acc_rev']==1) $str="<span class='glyphicon glyphicon-minus-sign text-default' style='font-size:24px'></span>";
			elseif ($d['acc_rev']==2) $str="<span class='glyphicon glyphicon-ok-sign text-success' style='font-size:24px'></span>";
			elseif ($d['acc_rev']==3) $str="<span class='glyphicon glyphicon-remove-sign text-danger' style='font-size:24px'></span>";
			
			$nm="";
			if (trim($d['gelar1'])!="") $nm .= $d['gelar1']." ";
			$nm .= $d['nm_dosen'];
			if (trim($d['gelar2'])!="") $nm .= ", ".$d['gelar2'];
			
			$ket=$d['nm_tuji'];
			
			$row[] = $d['tgl_rev'];			
			$row[] = "<span style='font-weight:550'>".$nm."</span><br/>".$ket;					
			$row[] = $str;
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listBerkasAju($nim)
	{		
		$data = $this->md_mahasiswa->getBerkasAjuan($nim);
		$ary = array();	$i=0;
		foreach($data as $d)
		{ 
			$row = array(); 
			$strBerkas = $d['nm_berkas'];
			if ($d['ket']==3) 
			{
				$i++;
				$strBerkas .= "
					<br/>
						<div class='file-input form-group'>
							<input type='hidden' name='idDeAj".$i."' value='".$d['id_ajuan']."-".$d['id_syarat']."'>
							<input type='hidden' name='idBrMa".$i."' value='".$d['id_berma']."'>
							<input type='hidden' name='fnBrMa".$i."' value='".$d['filename']."'>
							<input type='file' class='fileRev' name='berkasRA".$i."' style='position:absolute; top:0; left:0; opacity:0'>
							<button type='button' id='btRevFile' class='btn-xs btn-danger tombol' style='text-align:left'>Pilih File Revisi</button>
						</div>
				";
			}
			
			if ($d['ket']==1) $str="<span class='glyphicon glyphicon-minus-sign text-default' style='font-size:21px' title='Belum Verifikasi'></span>";
			elseif ($d['ket']==2) $str="<span class='glyphicon glyphicon-ok-sign text-success' style='font-size:21px' title='Valid'></span>";
			elseif ($d['ket']==3) $str="<span class='glyphicon glyphicon-remove-sign text-danger' style='font-size:21px' title='Tidak Valid'></span>";
			
			$row[] = $strBerkas;
			$row[] = $str;
			$row[] = $i;
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listBimbDsn($id)
	{		
		$data = $this->md_dosen->getBimbingan($id);
		$ary = array();	$i=0;
		foreach($data as $d)
		{ 
			if ($d['acc_bimb']==1)
			{
				$i++;
				
				if ($d['jns_bimb']==2) $ket="<span class='label label-default' style='font-size:1em; padding:0.1em 0.5em 0em'>Permohonan Persetujuan - ".$d['ket_bimb']." </span>";
				elseif ($d['jns_bimb']==3) $ket="<span class='label label-warning' style='color:black; font-weight:normal; font-size:1em; padding:0.1em 0.5em 0em'>Perubahan Judul</span> ".$d['ket_bimb'];
				else $ket=$d['ket_bimb'];
				
				$row = array(); 
				$row[] = $d['tgl_bimb'];			
				$row[] = "<span style='font-weight:530'>".$d['nm_mhs']." (".$d['nim'].")</span><br/>".$ket;					
				if ($i==1) 
				{
					if ($d['jns_bimb']==1)
						$tBtn = "<div class='btn-group' role='group'><a class='btn btn-sm btn-primary' href='javascript:void()' title='Verifikasi Bimbingan' onclick='set_bimb(".$d['id_dospem'].",".$d['id_bimb'].")'><i class='glyphicon glyphicon-open-file'></i> Verifikasi (Ya/Tidak)</a>";
					else $tBtn = "<div class='btn-group' role='group'><a class='btn btn-sm btn-warning' style='color:black' href='javascript:void()' title='Verifikasi Permohonan Persetujuan Ujian' onclick='set_bimb(".$d['id_dospem'].",".$d['id_bimb'].")'><i class='glyphicon glyphicon-open-file'></i> Verifikasi (Ya/Tidak)</a>";
				} 
				else $tBtn = "";
				$row[] = $tBtn;	
				$ary[] = $row;					
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listBimbMhs($nim)
	{		
		$data = $this->md_mahasiswa->getBimbingan($nim);
		$ary = array();	
		foreach($data as $d)
		{ 
			$row = array(); 
			if ($d['acc_bimb']==1) $str="<span class='glyphicon glyphicon-minus-sign text-default' style='font-size:24px'></span>";
			elseif ($d['acc_bimb']==2) $str="<span class='glyphicon glyphicon-ok-sign text-success' style='font-size:24px'></span>";
			elseif ($d['acc_bimb']==3) $str="<span class='glyphicon glyphicon-remove-sign text-danger' style='font-size:24px'></span>";
			
			$nm="";
			if (trim($d['gelar1'])!="") $nm .= $d['gelar1']." ";
			$nm .= $d['nm_dosen'];
			if (trim($d['gelar2'])!="") $nm .= ", ".$d['gelar2'];
			
			if ($d['jns_bimb']==2) $ket="<span class='label label-default' style='font-weight:normal; font-size:1em; padding:0.1em 0.5em 0em'>Permohonan Persetujuan - ".$d['ket_bimb']."</span>";
			elseif ($d['jns_bimb']==3) $ket="<span class='label label-warning' style='color:black; font-weight:normal; font-size:1em; padding:0.1em 0.5em 0em'>Perubahan Judul</span> ".$d['ket_bimb'];
			else $ket=$d['ket_bimb'];
			
			$row[] = $d['tgl_bimb'];			
			$row[] = "<span style='font-weight:550'>".$nm."</span><br/>".$ket;					
			$row[] = $str;
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listRev()
	{		
		$idDP = $this->input->get('idDP');
		$idR = $this->input->get('idR');
		$tipeR = $this->input->get('tipeR');
		$data = $this->md_revisi->getRevisi($idDP,$tipeR);
		$ary = array();	
		if ($tipeR==1)
		{
			$tglRev="x"; $i=0;
			foreach($data as $d)
			{ 
				$i++;
				$txPS = $this->mine->PSstr($d['jenjang']);
				$txPS .= " ".$d['nm_ps'];
				$str = "NIM. ".$d['nim']." - ".$txPS;
				$nm = "";
				if (trim($d['gelar1'])!="") $nm .= $d['gelar1']." ";
				$nm .= $d['nm_dosen'];
				if (trim($d['gelar2'])!="") $nm .= ", ".$d['gelar2'];
				
				$aryRev = explode("|-|",$d['revisi_uji']);				
				if (count($aryRev)>1)
				{
					$txRev = "<ol style='padding-left:20px'>";
					for ($j=0; $j<count($aryRev); $j++)
					{
						$txRev .= "<li>".$aryRev[$j]."</li>";
					}
					$txRev .= "</ol>";
				}
				else $txRev = $d['revisi_uji'];
				
				$strTgl1 = $this->mine->dateStr($d['tgl_rev'],2);
				$strTgl2 = $this->mine->dateStr($d['tgl_uji'],2);
				if ($d['acc_rev']==1) $strACC="-";
				else if ($d['acc_rev']==2) $strACC="Disetujui";
				else if ($d['acc_rev']==3) $strACC="Ditolak";
				
				if ($tglRev!==$d['tgl_rev'])
				{
					if ($tglRev!=="x")
					{
						$row = array(
							'nim' => $tNIM,
							'nm_mhs' => $tMhs,
							'dosen' => $tDosen,
							'rev_uji' => $tRev,
							'acc_rev' => $tAccRev,
							'tgl_rev' => $tTgl1,
							'tgl_uji' => $tTgl2,
							'id_det_uji' => $tIdDU,
							'nm_tuji' => $tUji,
							'nm_ps' => $tPS
						);
						$ary[] = $row;
					}
					
					$tNIM = $d['nim'];
					$tMhs = $d['nm_mhs'];
					$tDosen = $nm;
					if ($tglRev!=="x")
					$tRev = $strTgl1."<hr style='margin:5px 0px; border-top:1px solid #ccc'><b>".$nm."</b><br/>".$txRev;
					else $tRev = "<b>".$nm."</b><br/>".$txRev;
					$tAccRev = $strACC;
					$tTgl1 = $strTgl1;
					$tTgl2 = $strTgl2;
					$tIdDU = $d['id_det_uji'];
					$tUji = $d['nm_tuji'];
					$tPS = $txPS;
					$tglRev = $d['tgl_rev'];
				}
				else
				{
					$tRev .= "<hr style='margin:5px 0px; border:0px'><b>".$nm."</b><br/>".$txRev;
				}
				
				if ($i==count($data))
				{
					$row = array(
						'nim' => $tNIM,
						'nm_mhs' => $tMhs,
						'dosen' => $tDosen,
						'rev_uji' => $tRev,
						'acc_rev' => $tAccRev,
						'tgl_rev' => $tTgl1,
						'tgl_uji' => $tTgl2,
						'id_det_uji' => $tIdDU,
						'nm_tuji' => $tUji,
						'nm_ps' => $tPS
					);
					$ary[] = $row;					
				}
			}
		}
		elseif ($tipeR==2)
		{
			foreach($data as $d)
			{ 
				$tPS = $this->mine->PSstr($d['jenjang']);
				$tPS .= " ".$d['nm_ps'];
				$str = "NIM. ".$d['nim']." - ".$tPS;
				$strTgl1 = $this->mine->dateStr($d['tgl_rev'],2);
				$strTgl2 = $this->mine->dateStr($d['tgl_uji'],2);
				if ($d['acc_rev']==1) $strACC="-";
				else if ($d['acc_rev']==2) $strACC="Disetujui";
				else if ($d['acc_rev']==3) $strACC="Ditolak";
				
				$row = array(
					'nim' => $d['nim'],
					'nm_mhs' => $d['nm_mhs'],
					'info_rev' => "<b style='font-weight:600;'>".$strTgl1."</b><br/>".$d['revisi_uji'],
					'acc_rev' => $strACC,
					'tgl_rev' => $strTgl1,
					'tgl_uji' => $strTgl2,
					'id_det_uji' => $d['id_det_uji'],
					'nm_tuji' => $d['nm_tuji'],
					'nm_ps' => $tPS
				);							
				$ary[] = $row;					
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listMhs($key)
	{		
		$hak = $this->session->userdata('hak_thesis');
		if ($hak==1) $data = $this->md_mahasiswa->getMhs(null,null);
		elseif ($hak==2) $data = $this->md_mahasiswa->getMhs($key,null);
		elseif ($hak==3) $data = $this->md_mahasiswa->getM(null,$key);
		$ary = array();	$i = 0;
		foreach($data as $d)
		{ 
			$i++;
			$row = array(); 
			$row[] = $d['nim'];
			$row[] = $d['nm_mhs'];					
			$tPS = $this->mine->PSstr($d['jenjang']);			
			$tPS .= " ".$d['nm_ps'];
			$row[] = $tPS;	
			if ($i==1) $tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Detil Mahasiswa' onclick='det_mhs('".$d['nim']."')'><i class='glyphicon glyphicon-calendar'></i></a></div>";					
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}

	public function ajax_listUji($key)
	{		
		$hak = $this->session->userdata('hak_thesis');
		if ($hak==1) $data = $this->md_mahasiswa->getUji(null,null);
		elseif ($hak==2) $data = $this->md_mahasiswa->getUji($key,null);
		elseif ($hak==3) $data = $this->md_mahasiswa->getUji(null,$key);
		$ary = array();	$i = 0;
		foreach($data as $d)
		{ 
			$i++;
			$row = array(); 
			$row[] = $d['nim'];
			$row[] = $d['nm_mhs'];					
			$tPS = $this->mine->PSstr($d['jenjang']);			
			$tPS .= " ".$d['nm_ps'];
			$row[] = $tPS;	
			$row[] = $d['nm_tuji'];	
			if ($i==1) $tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Jadwalkan Ujian' onclick='plot_uji(\"".$d['nim']."\")'><i class='glyphicon glyphicon-calendar'></i></a>";					
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listBimb()
	{		
		$idDP = $this->input->get('idDP');
		$idB = $this->input->get('idB');
		$data = $this->md_bimbingan->getBimbingan($idDP);
		$ary = array();	
		foreach($data as $d)
		{ 
			$tPS = $this->mine->PSstr($d['jenjang']);
			$tPS .= " ".$d['nm_ps'];
			$str = "NIM. ".$d['nim']." - ".$tPS;
			$strTgl = $this->mine->dateStr($d['tgl_bimb'],2);			
			$newTitle="";
			
			if ($d['jns_bimb']==2) $ket="Permohonan Persetujuan - <b>".$d['ket_bimb']."</b>";
			elseif ($d['jns_bimb']==3) { $ket="Perubahan Judul - <b>".$d['ket_bimb']."</b>"; $newTitle=$d['ket_bimb'];}
			else $ket=$d['ket_bimb'];
			
			if ($d['acc_bimb']==1) $acc="<span class='glyphicon glyphicon-minus-sign text-default' style='font-size:24px'></span>";
			elseif ($d['acc_bimb']==2) $acc="<span class='glyphicon glyphicon-ok-sign text-success' style='font-size:24px'></span>";
			elseif ($d['acc_bimb']==3) $acc="<span class='glyphicon glyphicon-remove-sign text-danger' style='font-size:24px'></span>";
			
			$row = array(
				'nim' => $d['nim'],
				'nm_mhs' => $d['nm_mhs'],
				'p1' => $d['n_p1'],
				'id_progress' => $d['id_progress'],
				'ket_mhs' => $str,
				'tgl_bimb' => $strTgl,
				'jns_bimb' => $d['jns_bimb'],
				'ket_bimb' => $ket,
				'acc_bimb' => $acc,
				'judul_new' => $newTitle,
				'acc_uji' => $d['acc_uji'],
				'nm_tuji' => $d['nm_tuji']
			);							
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_listAjuji($key=null)
	{		
		$hak = $this->session->userdata('hak_thesis');
		if ($hak==1) $data = $this->md_mahasiswa->getAjuji(null,null);
		elseif ($hak==2) $data = $this->md_mahasiswa->getAjuji($key,null);
		elseif ($hak==3) $data = $this->md_mahasiswa->getAjuji(null,$key);
		$ary = array();	$i = 0;
		foreach($data as $d)
		{ 
			$i++;
			$row = array(); 
			$row[] = $d['nim'];
			$row[] = $d['nm_mhs'];					
			if ($d['jenjang']=="S1") $tPS="Sarjana ";			
			elseif ($d['jenjang']=="S2") $tPS="Magister ";			
			elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
			elseif ($d['jenjang']=="S3") $tPS="Doktor ";			
			$tPS .= $d['nm_ps'];
			$row[] = $tPS;	
			$row[] = $d['nm_tuji'];	
			if ($i==1) $tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Verifikasi Berkas' onclick='verf_file(\"".$d['nim']."\")'><i class='glyphicon glyphicon-folder-open'></i></a>";					
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_detAjuji()
	{
		$nim = $this->input->get('nim');
		$data = $this->md_mahasiswa->getDetAjuji($nim);
		$ary = array();
		foreach($data as $d)
		{ 	
			if ($d['jenjang']=="S1") $tPS="Sarjana ";			
			elseif ($d['jenjang']=="S2") $tPS="Magister ";			
			elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
			elseif ($d['jenjang']=="S3") $tPS="Doktor ";	
			$row = array(
				'id_ajuan' => $d['id_ajuan'],
				'id_syarat' => $d['id_syarat'],
				'id_berma' => $d['id_berma'],
				'nm_ps' => $tPS." ".$d['nm_ps'],
				'nm_tuji' => $d['nm_tuji'],
				'nim' => $d['nim'],
				'nm_mhs' => $d['nm_mhs'],
				'ket' => $d['ket'],
				'nm_berkas' => $d['nm_berkas'],			
				'filename' => $d['filename']
			);
			$ary[] = $row;
		}	
							
		$output = array("response" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_listPengesahan($key=null)
	{		
		$hak = $this->session->userdata('hak_thesis');
		if ($hak==1) $data = $this->md_mahasiswa->getPengesahan(null,null);
		elseif ($hak==2) $data = $this->md_mahasiswa->getPengesahan($key,null);
		elseif ($hak==3) $data = $this->md_mahasiswa->getPengesahan(null,$key);
		$ary = array();	$i = 0;
		foreach($data as $d)
		{ 
			$i++;
			$row = array(); 
			$row[] = $d['nim'];
			$row[] = $d['nm_mhs'];					
			if ($d['jenjang']=="S1") $tPS="Sarjana ";			
			elseif ($d['jenjang']=="S2") $tPS="Magister ";			
			elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
			elseif ($d['jenjang']=="S3") $tPS="Doktor ";			
			$tPS .= $d['nm_ps'];
			$row[] = $tPS;	
			if ($i==1) 
			{
				if ($d['tipe_reg']=="2") $tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Set Tim Dosen' onclick='set_tim(\"".$d['nim']."\",\"".$d['p1']."\",\"".$d['p2']."\")'><i class='glyphicon glyphicon-user'></i></a></div>";					
				else $tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Proses SK' onclick='set_sk(\"".$d['nim']."\",\"3\")'><i class='glyphicon glyphicon-file'></i></a></div>";					
			}
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_detMhs()
	{
		$nim = $this->input->get('nim');
		$data = $this->md_mahasiswa->getDetTA($nim);
		$ary = array();
		
		foreach($data as $d)
		{ 	
			if ($d['jenjang']=="S1") $tPS="Sarjana ";			
			elseif ($d['jenjang']=="S2") $tPS="Magister ";			
			elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
			elseif ($d['jenjang']=="S3") $tPS="Doktor ";	
			$sPS = $tPS." ".$d['nm_ps'];
			
			$row = array(
				'nim' => $d['nim'],
				'nm_mhs' => $d['nm_mhs'],
				'id_dospem' => $d['id_dospem'],			
				'jab_dosen' => $d['jab_dosen'],			
				'urut_dosen' => $d['urut_dosen'],			
				'id_dosen' => $d['id_dosen'],
				'gelar1' => $d['gelar1'],
				'gelar2' => $d['gelar2'],
				'nm_dosen' => $d['nm_dosen']
			);
			$ary[] = $row;
		}	
							
		$output = array("response" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_detUji()
	{
		$nim = $this->input->get('nim');
		$data = $this->md_mahasiswa->getDetUji($nim);
		$ary = array();
		foreach($data as $d)
		{ 	
			if ($d['jenjang']=="S1") $tPS="Sarjana ";			
			elseif ($d['jenjang']=="S2") $tPS="Magister ";			
			elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
			elseif ($d['jenjang']=="S3") $tPS="Doktor ";	
			$sPS = $tPS." ".$d['nm_ps'];
			
			$row = array(
				'nim' => $d['nim'],
				'nm_mhs' => $d['nm_mhs'],
				'id_ajuan' => $d['id_ajuan'],			
				'nm_ps' => $sPS,			
				'nm_tuji' => $d['nm_tuji'],			
				'id_dospem' => $d['id_dospem'],			
				'jab_dosen' => $d['jab_dosen'],			
				'urut_dosen' => $d['urut_dosen'],			
				'id_dosen' => $d['id_dosen'],
				'gelar1' => $d['gelar1'],
				'gelar2' => $d['gelar2'],
				'nm_dosen' => $d['nm_dosen'],
				'jenjang' => $d['jenjang'],
				'p1' => $d['p1'],
				'p2' => $d['p2'],
				'filename' => $d['filename']
			);
			$ary[] = $row;
		}	
							
		$output = array("response" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_listAjuan($ps)
	{		
		$data = $this->md_mahasiswa->getAjuan($ps);
		$ary = array();	$i = 0;
		foreach($data as $d)
		{ 
			$i++;
			$row = array(); 
			$row[] = "<b>".$d['nm_mhs']."</b><br>NIM. ".$d['nim'];			
			$row[] = $d['judul_ta'];	
			if ($i==1) $tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-primary' href='javascript:void()' title='Verifikasi Ajuan' onclick='set_dosen(\"".$d['nim']."\",\"2\")'><i class='glyphicon glyphicon-user'></i> Set Tim Dosen</a>";					
			else $tBtn = "";
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	
	public function ajax_detReg()
	{
		$nim = $this->input->get('nim');
		$mhs = $this->md_mahasiswa->getDetil($nim);
		$row = array(
			'nim' => $mhs->nim,
			'nm_mhs' => $mhs->nm_mhs,
			'judul_ta' => $mhs->judul_ta,			
			'nm_ps' => $mhs->nm_ps,			
			'p1' => $mhs->p1,
			'p2' => $mhs->p2
		);
		$output = array("response" => $row);
		echo json_encode($output);
	}
	
	public function ajax_detAjuan()
	{
		$nim = $this->input->get('nim');
		$urut = $this->input->get('urut');
		$data = $this->md_mahasiswa->getDetAjuan($nim,$urut);
		$ary = array();
		foreach($data as $d)
		{ 	
			$row = array(
				'nim' => $d['nim'],
				'nm_mhs' => $d['nm_mhs'],
				'judul_ta' => $d['judul_ta'],			
				'id_dospem' => $d['id_dospem'],			
				'jab_dosen' => $d['jab_dosen'],			
				'urut_dosen' => $d['urut_dosen'],			
				'id_dosen' => $d['id_dosen'],
				'gelar1' => $d['gelar1'],
				'gelar2' => $d['gelar2'],
				'nm_dosen' => $d['nm_dosen'],
				'jenjang' => $d['jenjang'],
				'p1' => $d['p1'],
				'p2' => $d['p2']
			);
			$ary[] = $row;
		}	
							
		$output = array("response" => $ary);
		echo json_encode($output);
	}
	
	public function save_revisi()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$mhs = $this->md_mahasiswa->getDet($id_user);	
		$nim = $mhs->nim;
		$tipeRev = $this->input->post('tpRev');
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
			
		if ($tipeRev==1) 
		{
			$dataRev = $this->md_mahasiswa->getRev($nim,null);
			foreach($dataRev as $d)
			{
				$idRev = $this->md_revisi->getMaxID($th);				
				$data['id_rev'] = $idRev;
				$data['id_det_uji'] = $d['id_det_uji'];
				$data['tgl_rev'] = $this->input->post('tglRev');
				$this->md_revisi->save($data);				
				
				$dtLogRv['id_log_rev'] = $this->md_revisi->getMaxLogID($th);
				$dtLogRv['id_user'] = $id_user;			
				$dtLogRv['dt'] = $tgl." ".$jam;			
				$dtLogRv['act'] = 1;	
				$dtLogRv['id_rev'] = $idRev;
				$dtLogRv['tgl_rev'] = $this->input->post('tglRev');
				$this->md_revisi->saveLog($dtLogRv);
			}	
		}
		elseif ($tipeRev==2) 
		{
			$idRev = $this->md_revisi->getMaxID($th);				
			$data['id_rev'] = $idRev;
			$data['id_det_uji'] = $this->input->post('idDU');
			$data['tgl_rev'] = $this->input->post('tglRev');
			$this->md_revisi->save($data);				
			
			$dtLogRv['id_log_rev'] = $this->md_revisi->getMaxLogID($th);
			$dtLogRv['id_user'] = $id_user;			
			$dtLogRv['dt'] = $tgl." ".$jam;			
			$dtLogRv['act'] = 1;	
			$dtLogRv['id_rev'] = $idRev;
			$dtLogRv['tgl_rev'] = $this->input->post('tglRev');
			$this->md_revisi->saveLog($dtLogRv);
		}		
			
		echo json_encode(array("status"=>true));
	}	
	
	public function save_nilai()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$nim = $this->input->post('nimPU');
		$nRev = $this->input->post("jmlP3");
		$nSkor = $this->input->post("jmlP4");
		$nTim = $this->input->post("nTim");
		$mhs = $this->md_mahasiswa->getDetil($nim);			
		$id_prog = $mhs->id_progress;
		
		$adaRev = false; 
		$revisi = "";
		for ($i=1; $i<=4; $i++)
		{
			$tNM = 'revPU'.$i;
			if (!empty($this->input->post($tNM)) and strlen(trim($this->input->post($tNM)))>10) 
			{
				if (strlen($revisi)>0) $revisi .= "|-|";
				$revisi .= trim($this->input->post($tNM));								
			}			
		}
		if (strlen($revisi)>0) $adaRev = true; 
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$ubah = false;	
		if ($adaRev) 
		{
			$nRev++;
			$dtDetUji['revisi_uji'] = $revisi;	
			if ($id_prog!=310) 
			{
				$id_nextProg = 310;			
				$ubah = true;
			}			
			$dtDP['stat_dospem'] = 3;
			$dtLogDP['stat_dospem'] = 3;			
		}
		else
		{
			$dtDP['stat_dospem'] = 4;
			$dtLogDP['stat_dospem'] = 4;	
		}
		
		/* Ubah Status Dospem */
		$idDP = $this->input->post('idDPU');
		$this->md_dospem->update($dtDP,array('id_dospem'=>$idDP));
		
		$dtLogDP['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
		$dtLogDP['id_user'] = $id_user;			
		$dtLogDP['id_dospem'] = $idDP;			
		$dtLogDP['dt'] = $tgl." ".$jam;			
		$dtLogDP['act'] = 2;		
		$this->md_dospem->saveLog($dtLogDP);
		
		if ($ubah)
		{
			$dtMhs['id_progress'] = $id_nextProg;
			$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));	
			
			$dtMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
			$dtMhs['id_user_log'] = $id_user;			
			$dtMhs['dt'] = $tgl." ".$jam;			
			$dtMhs['act'] = 2;	
			$dtMhs['nim'] = $nim;
			$this->md_mahasiswa->saveLog($dtMhs);
			
			$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
			$dtProgr['id_user'] = $id_user;			
			$dtProgr['dt_progression'] = $tgl." ".$jam;					
			$dtProgr['nim'] = $nim;
			$dtProgr['id_progress'] = $id_prog;
			$this->md_progression->save($dtProgr);
		}
		
		$nNilai = $this->input->post('nPU');
		$idDetUji = $this->input->post('idDU');
		for ($i=0; $i<$nNilai; $i++)  
		{ 			
			$nm1 = "krit".$i;
			$nm2 = "nilai".$i;
			$dtNilai['id_det_uji'] = $idDetUji; 
			$dtNilai['id_kriteria'] = $this->input->post($nm1);
			$dtNilai['nilai'] = $this->input->post($nm2);
			$this->md_nilai->save($dtNilai);
			
			$dtNilai['id_log_nilai'] = $this->md_nilai->getMaxLogID($th);
			$dtNilai['id_user'] = $id_user;			
			$dtNilai['dt'] = $tgl." ".$jam;			
			$dtNilai['act'] = 1;			
			$this->md_nilai->saveLog($dtNilai);
			unset($dtNilai);
		} 		
		
		$nSkor++;
		$dtDetUji['nilai'] = $this->input->post("totNilai");
		$this->md_ujian->updateDet($dtDetUji,array('id_det_uji'=>$idDetUji));	

		$dtDetUji['id_log_det_uji'] = $this->md_ujian->getMaxLogDetID($th); 
		$dtDetUji['id_user'] = $id_user;			
		$dtDetUji['dt'] = $tgl." ".$jam;			
		$dtDetUji['act'] = 2;	
		$this->md_ujian->saveLogDet($dtDetUji);
		
		/* TIDAK ADA REVISI & NILAI SUDAH SEMUA, lanjut Pembimbingan/Lulus */
		if ($nRev==0 and $nSkor==$nTim)
		{
			unset($dtMhs);
			$id_tuji = $mhs->id_tuji;
			$id_tujiAkhir = $mhs->id_tuji_akhir;			
			
			/* LULUS */
			if ($id_tuji==$id_tujiAkhir)
			{
				$stDospem = 5;
				$id_nxProg = $this->md_progress->getData($id_prog)->id_next1;
				$id_nextProg = $this->md_progress->getData($id_nxProg)->id_next1;
			}			
			/* NEXT */
			else
			{
				$stDospem = 1;
				$id_nextProg = $this->md_progress->getData($id_prog)->id_next2;
				$dtMhs['id_tuji'] = $this->md_tuji->getNext($mhs->id_ps,$mhs->urut_tuji);
			}
			
			$dtDospem['stat_dospem'] = $stDospem;
			$this->md_mahasiswa->updateDospem($dtDospem,array('nim'=>$nim,'stat'=>1));		

			$tim = $this->md_mahasiswa->getTim($nim);
			foreach($tim as $tm)  
			{ 
				unset($dtLogDP);
				$dtLogDP['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
				$dtLogDP['id_user'] = $id_user;	
				$dtLogDP['id_dospem'] = $tm['id_dosen'];
				$dtLogDP['stat_dospem'] = $stDospem;
				$dtLogDP['dt'] = $tgl." ".$jam;			
				$dtLogDP['act'] = 2;		
				$this->md_dospem->saveLog($dtLogDP);				
			} 			
			
			$dtMhs['id_progress'] = $id_nextProg;
			$dtMhs['acc_uji'] = 0;			
			$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));	
			
			$dtMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
			$dtMhs['id_user_log'] = $id_user;			
			$dtMhs['dt'] = $tgl." ".$jam;			
			$dtMhs['act'] = 2;	
			$dtMhs['nim'] = $nim;
			$this->md_mahasiswa->saveLog($dtMhs);
			
			$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
			$dtProgr['id_user'] = $id_user;			
			$dtProgr['dt_progression'] = $tgl." ".$jam;					
			$dtProgr['nim'] = $nim;
			$dtProgr['id_progress'] = $id_prog;
			$this->md_progression->save($dtProgr);
			
			$nilai = $this->md_ujian->getNilaiUjian($idDetUji);
			$dtUji['nilai_uji'] = $nilai->nilaiUji; 
			$this->md_ujian->update($dtUji,array('id_uji'=>$nilai->id_uji));

			$dtUji['id_log_uji'] = $this->md_ujian->getMaxLogID($th);
			$dtUji['id_user'] = $id_user;			
			$dtUji['dt'] = $tgl." ".$jam;			
			$dtUji['act'] = 2;				
			$this->md_ujian->saveLog($dtUji);			
		}
		
		echo json_encode(array("status"=>true));
	}
	
	public function save_ujian()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$nim = $this->input->post('nimUji');
		$mhs = $this->md_mahasiswa->getDetil($nim);			
		$id_prog = $mhs->id_progress;
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;			
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$dtMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));				
		
		$dtMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtMhs['id_user_log'] = $id_user;			
		$dtMhs['dt'] = $tgl." ".$jam;			
		$dtMhs['act'] = 2;	
		$dtMhs['nim'] = $nim;
		$this->md_mahasiswa->saveLog($dtMhs);
		
		$dtUji['id_uji'] = $this->md_ujian->getMaxID($th);
		$dtUji['id_ajuan'] = $this->input->post("idAjuan");
		$dtUji['id_ruang'] = $this->input->post("ruangUji");
		$dtUji['tgl_uji'] = $this->input->post("tglUji");
		$dtUji['jam1_uji'] = $this->input->post("jamUji1");
		$dtUji['jam2_uji'] = $this->input->post("jamUji2");
		$dtUji['filename_uji'] = $this->input->post("fnUji");		
		$this->md_ujian->save($dtUji);
		
		$dtUji['id_log_uji'] = $this->md_ujian->getMaxLogID($th);
		$dtUji['id_user'] = $id_user;			
		$dtUji['dt'] = $tgl." ".$jam;			
		$dtUji['act'] = 1;	
		$this->md_ujian->saveLog($dtUji);
		
		$tim = $this->md_mahasiswa->getTim($nim);
		foreach($tim as $tm)  
		{ 			
			$dtDetUji['id_det_uji'] = $this->md_ujian->getMaxDetID($th); 
			$dtDetUji['id_uji'] = $dtUji['id_uji'];
			$dtDetUji['id_dosen'] = $tm['id_dosen'];
			$this->md_ujian->saveDet($dtDetUji);
			
			$dtDetUji['id_log_det_uji'] = $this->md_ujian->getMaxLogDetID($th); 
			$dtDetUji['id_user'] = $id_user;			
			$dtDetUji['dt'] = $tgl." ".$jam;			
			$dtDetUji['act'] = 1;	
			$this->md_ujian->saveLogDet($dtDetUji);
			unset($dtDetUji);
		} 		
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		echo json_encode(array("status"=>true));
	}
	
	public function save_verif($nS)
	{		
		$id_user = $this->session->userdata('id_user_thesis');	
		$nim = $this->input->post('nim');
		$mhs = $this->md_mahasiswa->getDetil($nim);
		$id_prog = $mhs->id_progress;
		$id_nextProg1 = $this->md_progress->getData($id_prog)->id_next1;	
		$id_nextProg2 = $this->md_progress->getData($id_prog)->id_next2;	
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$nY=0;
		for ($i=1; $i<=$nS; $i++)
		{
			$nm1="berkas".$i; 
			$nm2="idAju";		
			$nm3="idSA".$i;			
			$nm4="idBM".$i;			
			$isi = $this->input->post($nm1);
			$idAju = $this->input->post($nm2);
			$idSA = $this->input->post($nm3);
			$idBM = $this->input->post($nm4);			
			if (!$isi) $val=3; else { $val=2; $nY++; }
			
			$dtDA['ket'] = $val;
			$this->md_ajuan->updateDet($dtDA,array('id_ajuan'=>$idAju,'id_syarat'=>$idSA,'id_berma'=>$idBM));
			
			$dtLogDA['id_log_det_ajuan'] = $this->md_ajuan->getMaxLogDetID($th);
			$dtLogDA['id_user'] = $id_user;			
			$dtLogDA['dt'] = $tgl." ".$jam;			
			$dtLogDA['act'] = 2;	
			$dtLogDA['id_ajuan'] = $idAju;
			$dtLogDA['id_syarat'] = $idSA;
			$dtLogDA['id_berma'] = $idBM;
			$dtLogDA['ket'] = $val;
			$this->md_ajuan->saveLogDet($dtLogDA);
		}
		
		if ($nY==$nS)
		{
			$dataAj['tgl_acc'] = $tgl;
			$this->md_ajuan->update($dataAj,array('id_ajuan'=>$idAju));
			
			$dtLogAj['id_log_ajuan'] = $this->md_ajuan->getMaxLogID($th);
			$dtLogAj['id_user'] = $id_user;			
			$dtLogAj['dt'] = $tgl." ".$jam;			
			$dtLogAj['act'] = 2;	
			$dtLogAj['id_ajuan'] = $idAju;
			$dtLogAj['tgl_acc'] = $tgl;
			$this->md_ajuan->saveLog($dtLogAj);
			
			$dtMhs['id_progress'] = $id_nextProg1;			
			$dtLogMhs['id_progress'] = $id_nextProg1;			
		}
		else 
		{
			$dtMhs['id_progress'] = $id_nextProg2;		
			$dtLogMhs['id_progress'] = $id_nextProg2;		
		}
		
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$mhs->nim));
			
		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;			
		$dtLogMhs['dt'] = $tgl." ".$jam;			
		$dtLogMhs['act'] = 2;	
		$dtLogMhs['nim'] = $mhs->nim;
		$this->md_mahasiswa->saveLog($dtLogMhs);
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $mhs->nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		echo json_encode(array("status"=>true));
	}
	
	public function save_revAjuan($nBRA)
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$mhs = $this->md_mahasiswa->getDet($id_user);	
		$id_prog = $mhs->id_progress;
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;	
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$config['upload_path'] = './upload/';			
		$config['overwrite'] = true;
		$config['max_size']  = 20971520;
		$config['allowed_types']='pdf';
		$this->load->library('upload');	
		
		$dtMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$mhs->nim));
		
		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;			
		$dtLogMhs['dt'] = $tgl." ".$jam;			
		$dtLogMhs['act'] = 2;	
		$dtLogMhs['nim'] = $mhs->nim;
		$dtLogMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->saveLog($dtLogMhs);
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $mhs->nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		if ($nBRA>0)
		{
			for ($i=1; $i<=$nBRA; $i++)
			{
				$hash = hash('sha256',$mhs->nim);
				$fn = substr($hash,0,7).uniqid();
				$config['file_name'] = $fn;
				$this->upload->initialize($config);
				$nmBRA = "berkasRA".$i;
				if ($this->upload->do_upload($nmBRA)) 
				{
					$path = './upload/'.$this->input->post("fnBrMa".$i);
					if (file_exists($path)) unlink($path);
					
					$nmBerma = "idBrMa".$i;
					$idBerma = $this->input->post($nmBerma);				
					$dataBr['filename'] = $this->upload->data("file_name");
					$this->md_berma->update($dataBr,array('id_berma'=>$idBerma));	
					
					$dataLogBr['id_log_berma'] = $this->md_berma->getMaxLogID($th);
					$dataLogBr['id_user'] = $id_user;			
					$dataLogBr['dt'] = $tgl." ".$jam;			
					$dataLogBr['act'] = 2;				
					$dataLogBr['id_berma'] = $idBerma;
					$dataLogBr['filename'] = $this->upload->data("file_name");
					$this->md_berma->saveLog($dataLogBr);
					
					$idDeAj = explode("-", $this->input->post("idDeAj".$i));
					$dataDaj['ket'] = 1;
					$this->md_ajuan->updateDet($dataDaj,array('id_ajuan'=>$idDeAj[0],'id_syarat'=>$idDeAj[1],'id_berma'=>$idBerma));
					
					$dataLogDaj['id_log_det_ajuan'] = $this->md_ajuan->getMaxLogDetID($th);
					$dataLogDaj['id_user'] = $id_user;			
					$dataLogDaj['dt'] = $tgl." ".$jam;			
					$dataLogDaj['act'] = 2;
					$dataLogDaj['id_ajuan'] = $idDeAj[0];
					$dataLogDaj['id_syarat'] = $idDeAj[1];
					$dataLogDaj['id_berma'] = $idBerma;
					$dataLogDaj['ket'] = 1;
					$this->md_ajuan->saveLogDet($dataLogDaj);			
				}
				/*else
				{
					$stat = false;
					$err = $this->upload->display_errors();
				}*/
			}
		}		
		echo json_encode(array("status"=>true));
	}
	
	public function save_ajuan($nS)
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$mhs = $this->md_mahasiswa->getDet($id_user);	
		$id_prog = $mhs->id_progress;
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;	
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$config['upload_path'] = './upload/';			
		$config['overwrite'] = true;
		$config['max_size']  = 20971520;
		$config['allowed_types']='pdf';
		$this->load->library('upload');	
		
		$idAjuan = $this->md_ajuan->getMaxID($th);				
		$dataAj['id_ajuan'] = $idAjuan;
		$dataAj['nim'] = $mhs->nim;
		$dataAj['id_tuji'] = $mhs->id_tuji;
		$dataAj['tgl_ajuan'] = $tgl;
		$this->md_ajuan->save($dataAj);				
		
		$dtLogAj['id_log_ajuan'] = $this->md_ajuan->getMaxLogID($th);
		$dtLogAj['id_user'] = $id_user;			
		$dtLogAj['dt'] = $tgl." ".$jam;			
		$dtLogAj['act'] = 1;	
		$dtLogAj['id_ajuan'] = $idAjuan;
		$dtLogAj['nim'] = $mhs->nim;
		$dtLogAj['id_tuji'] = $mhs->id_tuji;
		$dtLogAj['tgl_ajuan'] = $tgl;
		$this->md_ajuan->saveLog($dtLogAj);
		
		$dtMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$mhs->nim));
		
		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;			
		$dtLogMhs['dt'] = $tgl." ".$jam;			
		$dtLogMhs['act'] = 2;	
		$dtLogMhs['nim'] = $mhs->nim;
		$dtLogMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->saveLog($dtLogMhs);
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $mhs->nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		if ($nS>0)
		{
			for ($i=1; $i<=$nS; $i++)
			{
				$nmFN = "idS".$i;
				$hash = hash('sha256',$mhs->nim);
				$fn = substr($hash,0,7).uniqid();
				$config['file_name'] = $fn;
				$this->upload->initialize($config);
				$nmS = "berkas".$i;
				if ($this->upload->do_upload($nmS)) 
				{
					if ($this->input->post("fnB".$i))
					{
						$path = './upload/'.$this->input->post("fnB".$i);
						if (file_exists($path)) unlink($path);
					
						$nmBerma = "idBM".$i;
						$idBerma = $this->input->post($nmBerma);				
						$dataBr['filename'] = $this->upload->data("file_name");
						$this->md_berma->update($dataBr,array('id_berma'=>$idBerma));	
						
						$dataLogBr['act'] = 2;
					}
					else
					{
						$idBerkas = "idB".$i;					
						$idBerma = $this->md_berma->getMaxID($th);				
						$dataBr['id_berma'] = $idBerma;
						$dataBr['nim'] = $mhs->nim;
						$dataBr['id_berkas'] = $this->input->post($idBerkas);
						$dataBr['filename'] = $this->upload->data("file_name");
						$this->md_berma->save($dataBr);	
						
						$dataLogBr['act'] = 1;				
						$dataLogBr['id_berkas'] = $this->input->post($idBerkas);
						$dataLogBr['nim'] = $mhs->nim;
					}
					
					$dataLogBr['id_log_berma'] = $this->md_berma->getMaxLogID($th);
					$dataLogBr['id_user'] = $id_user;			
					$dataLogBr['dt'] = $tgl." ".$jam;								
					$dataLogBr['id_berma'] = $idBerma;										
					$dataLogBr['filename'] = $this->upload->data("file_name");
					$this->md_berma->saveLog($dataLogBr);	
					
					$idSyarat = "idS".$i;
					$dataDaj['id_ajuan'] = $idAjuan;
					$dataDaj['id_syarat'] = $this->input->post($idSyarat);
					$dataDaj['id_berma'] = $idBerma;
					$this->md_ajuan->saveDet($dataDaj);	
					
					$dataLogDaj['id_log_det_ajuan'] = $this->md_ajuan->getMaxLogDetID($th);
					$dataLogDaj['id_user'] = $id_user;			
					$dataLogDaj['dt'] = $tgl." ".$jam;			
					$dataLogDaj['act'] = 1;					
					$dataLogDaj['id_ajuan'] = $idAjuan;
					$dataLogDaj['id_syarat'] = $this->input->post($idSyarat);
					$dataLogDaj['id_berma'] = $idBerma;
					$this->md_ajuan->saveLogDet($dataLogDaj);			
				}
				/*else
				{
					$stat = false;
					$err = $this->upload->display_errors();
				}*/
			}
		}		
		echo json_encode(array("status"=>true));
	}
	
	public function save_bimbingan()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$mhs = $this->md_mahasiswa->getDet($id_user);	
		$id_prog = $mhs->id_progress;
		$urut_prog = $mhs->urut_progress;
		if ($urut_prog==4) $id_nextProg = $this->md_progress->getData($id_prog)->id_next1;	
		$nim = $mhs->nim;
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$idBimb = $this->md_bimbingan->getMaxID($th);				
		$data['id_bimb'] = $idBimb;
		$data['id_dospem'] = $this->input->post('dosenBimb');
		$data['tgl_bimb'] = $this->input->post('tglBimb');
		$data['jns_bimb'] = $this->input->post('jnsBimb');
		if ($this->input->post('jnsBimb')==1)
			$data['ket_bimb'] = $this->input->post('ketBimb');
		elseif ($this->input->post('jnsBimb')==2)
			$data['ket_bimb'] = $this->input->post('nmUji');
		elseif ($this->input->post('jnsBimb')==3) 
			$data['ket_bimb'] = $this->input->post('judulNew');
		$data['acc_bimb'] = 1;
		$this->md_bimbingan->save($data);				
		
		$dtLogBm['id_log_bimb'] = $this->md_bimbingan->getMaxLogID($th);
		$dtLogBm['id_user'] = $id_user;			
		$dtLogBm['dt'] = $tgl." ".$jam;			
		$dtLogBm['act'] = 1;	
		$dtLogBm['id_bimb'] = $idBimb;
		$dtLogBm['id_dospem'] = $this->input->post('dosenBimb');
		$dtLogBm['tgl_bimb'] = $this->input->post('tglBimb');
		$dtLogBm['jns_bimb'] = $this->input->post('jnsBimb');
		if ($this->input->post('jnsBimb')==1)
			$dtLogBm['ket_bimb'] = $this->input->post('ketBimb');
		else $dtLogBm['ket_bimb'] = $this->input->post('nmUji');	
		$dtLogBm['acc_bimb'] = 1;
		$this->md_bimbingan->saveLog($dtLogBm);	
			
		/*if ($id_prog==304) 
		{
			$dtMhs['id_progress'] = $id_nextProg;
			$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));
			
			$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
			$dtLogMhs['id_user_log'] = $id_user;			
			$dtLogMhs['dt'] = $tgl." ".$jam;			
			$dtLogMhs['act'] = 2;	
			$dtLogMhs['nim'] = $mhs->nim;
			$dtLogMhs['id_progress'] = $id_nextProg;
			$this->md_mahasiswa->saveLog($dtLogMhs);
			
			$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
			$dtProgr['id_user'] = $id_user;			
			$dtProgr['dt_progression'] = $tgl." ".$jam;					
			$dtProgr['nim'] = $nim;
			$dtProgr['id_progress'] = $id_prog;
			$this->md_progression->save($dtProgr);
		}*/
		
		echo json_encode(array("status"=>true));
	}	
	
	public function save_pengesahan()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$nim = $this->input->post('nimSah');
		$mhs = $this->md_mahasiswa->getDetil($nim);			
		$id_prog = $mhs->id_progress;
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;			
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$dtMhs['id_progress'] = $id_nextProg;
		$dtMhs['no_sk'] = $this->input->post('noSK');
		$dtMhs['tgl_sk'] = $this->input->post('tglSK');
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));				
		
		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;			
		$dtLogMhs['dt'] = $tgl." ".$jam;			
		$dtLogMhs['act'] = 2;	
		$dtLogMhs['nim'] = $nim;
		$dtLogMhs['no_sk'] = $this->input->post('noSK');
		$dtLogMhs['tgl_sk'] = $this->input->post('tglSK');
		$dtLogMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->saveLog($dtLogMhs);
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		echo json_encode(array("status"=>true));
	}
	
	public function save_dosen()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$nim = $this->input->post('nim');
		$mhs = $this->md_mahasiswa->getDetil($nim);			
		$id_prog = $mhs->id_progress;
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;			
		$nPemb = $this->input->post('nPemb');
		$nPeng = $this->input->post('nPeng');
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		for ($i=1; $i<=$nPemb; $i++)
		{
			$idDP = $this->input->post('idDP'.$i);
			$idPemb0 = $this->input->post('idPemb'.$i);
			$idPemb1 = $this->input->post('pemb'.$i);
			
			if ($idPemb0 != $idPemb1)
			{
				$data['id_dosen'] = $idPemb1;
				$this->md_dospem->update($data,array('id_dospem'=>$idDP)); 
				
				$dtLogDP['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
				$dtLogDP['id_user'] = $id_user;			
				$dtLogDP['dt'] = $tgl." ".$jam;			
				$dtLogDP['act'] = 2;
				$dtLogDP['id_dospem'] = $idDP;
				$dtLogDP['id_dosen'] = $idPemb1;
				$this->md_dospem->saveLog($dtLogDP);
			}
		}
		
		for ($i=1; $i<=$nPeng; $i++)
		{
			$idDospem = $this->md_dospem->getMaxID($th);				
			$data['id_dospem'] = $idDospem;
			$data['id_dosen'] = $this->input->post('peng'.$i);
			$data['nim'] = $nim;
			$data['jab_dosen'] = 2;
			$data['urut_dosen'] = $i;							
			$this->md_dospem->save($data);				
			
			$dtLogDK['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
			$dtLogDK['id_user'] = $id_user;			
			$dtLogDK['dt'] = $tgl." ".$jam;			
			$dtLogDK['act'] = 1;	
			$dtLogDK['id_dospem'] = $idDospem;
			$dtLogDK['id_dosen'] = $this->input->post('peng'.$i);
			$dtLogDK['nim'] = $nim;
			$dtLogDK['jab_dosen'] = 2;
			$dtLogDK['urut_dosen'] = $i;					
			$this->md_dospem->saveLog($dtLogDK);		
		}
		
		$dtMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));				
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		echo json_encode(array("status"=>true));
	}
	
	public function save_registrasi()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$nim = $this->input->post('nimReg');
		$mhs = $this->md_mahasiswa->getDetil($nim);			
		$id_prog = $mhs->id_progress;
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;
		$nPemb = $this->input->post('nPemb');
		$nPeng = $this->input->post('nPeng');		
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		for ($i=1; $i<=$nPemb; $i++)
		{
			$idDospem = $this->md_dospem->getMaxID($th);				
			$data['id_dospem'] = $idDospem;
			$data['id_dosen'] = $this->input->post('pemb'.$i);
			$data['nim'] = $nim;
			$data['jab_dosen'] = 1;
			$data['urut_dosen'] = $i;							
			$this->md_dospem->save($data);				
			
			$dtLogDK['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
			$dtLogDK['id_user'] = $id_user;			
			$dtLogDK['dt'] = $tgl." ".$jam;			
			$dtLogDK['act'] = 1;	
			$dtLogDK['id_dospem'] = $idDospem;
			$dtLogDK['id_dosen'] = $this->input->post('pemb'.$i);
			$dtLogDK['nim'] = $nim;
			$dtLogDK['jab_dosen'] = 1;
			$dtLogDK['urut_dosen'] = $i;					
			$this->md_dospem->saveLog($dtLogDK);				
		}
		
		for ($i=1; $i<=$nPeng; $i++)
		{
			if (!empty($this->input->post('peng'.$i)))
			{
				$idDospem = $this->md_dospem->getMaxID($th);				
				$data['id_dospem'] = $idDospem;
				$data['id_dosen'] = $this->input->post('peng'.$i);
				$data['nim'] = $nim;
				$data['jab_dosen'] = 2;
				$data['urut_dosen'] = $i;							
				$this->md_dospem->save($data);				
				
				$dtLogDK['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
				$dtLogDK['id_user'] = $id_user;			
				$dtLogDK['dt'] = $tgl." ".$jam;			
				$dtLogDK['act'] = 1;	
				$dtLogDK['id_dospem'] = $idDospem;
				$dtLogDK['id_dosen'] = $this->input->post('peng'.$i);
				$dtLogDK['nim'] = $nim;
				$dtLogDK['jab_dosen'] = 2;
				$dtLogDK['urut_dosen'] = $i;					
				$this->md_dospem->saveLog($dtLogDK);		
			}
		}
		
		$dtMhs['id_progress'] = $id_nextProg;
		$dtMhs['no_sk'] = $this->input->post('noSKReg');
		$dtMhs['tgl_sk'] = $this->input->post('tglSKReg');
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));				
		
		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;			
		$dtLogMhs['dt'] = $tgl." ".$jam;			
		$dtLogMhs['act'] = 2;	
		$dtLogMhs['nim'] = $nim;
		$dtLogMhs['no_sk'] = $this->input->post('noSKReg');
		$dtLogMhs['tgl_sk'] = $this->input->post('tglSKReg');
		$dtLogMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->saveLog($dtLogMhs);
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		echo json_encode(array("status"=>true));
	}
	
	public function save_thesis()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$mhs = $this->md_mahasiswa->getDet($id_user);	
		$id_prog = $this->md_mahasiswa->getFirstProg($id_user);	
		$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;	
		$nim = $mhs->nim;
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		if ($mhs->tipe_reg == '1')
		{
			$nP = $this->input->post('nPemb');		
			for ($i=1; $i<=$nP; $i++)
			{
				$idDospem = $this->md_dospem->getMaxID($th);				
				$data['id_dospem'] = $idDospem;
				$data['id_dosen'] = $this->input->post('pemb'.$i);
				$data['nim'] = $nim;
				$data['jab_dosen'] = 1;
				$data['urut_dosen'] = $i;							
				$this->md_dospem->save($data);				
				
				$dtLogDK['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
				$dtLogDK['id_user'] = $id_user;			
				$dtLogDK['dt'] = $tgl." ".$jam;			
				$dtLogDK['act'] = 1;	
				$dtLogDK['id_dospem'] = $idDospem;
				$dtLogDK['id_dosen'] = $this->input->post('pemb'.$i);
				$dtLogDK['nim'] = $nim;
				$dtLogDK['jab_dosen'] = 1;
				$dtLogDK['urut_dosen'] = $i;					
				$this->md_dospem->saveLog($dtLogDK);				
			}
		}
		
		$dtMhs['judul_ta'] = $this->input->post('judul');
		$dtMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));				
		
		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;			
		$dtLogMhs['dt'] = $tgl." ".$jam;			
		$dtLogMhs['act'] = 2;	
		$dtLogMhs['nim'] = $nim;
		$dtLogMhs['judul_ta'] = $this->input->post('judul');
		$dtLogMhs['id_progress'] = $id_nextProg;
		$this->md_mahasiswa->saveLog($dtLogMhs);
		
		$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
		$dtProgr['id_user'] = $id_user;			
		$dtProgr['dt_progression'] = $tgl." ".$jam;					
		$dtProgr['nim'] = $nim;
		$dtProgr['id_progress'] = $id_prog;
		$this->md_progression->save($dtProgr);
		
		echo json_encode(array("status"=>true));
	}
	
	public function expPDF()
	{
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
		$smt = $this->session->userdata('id_smt_jadwal');
		$dtRep = $this->md_kelas->repDataPDF($smt);				
		
		$cNid = "x"; 
		$init = true; /* inisialisasi */
		$dosen = array();
		foreach ($dtRep as $d)
		{
			if (trim($d['glr11'])!="") $strDosen1 = $d['glr11']." ".$d['nama1']; else $strDosen1=$d['nama1'];
			if (trim($d['glr12'])!="") $strDosen1 .= ", ".$d['glr12'];
			$strDosen2="";
			if (trim($d['glr21'])!="") $strDosen2 = $d['glr21']." ".$d['nama2']; else $strDosen2=$d['nama2'];
			if (trim($d['glr22'])!="") $strDosen2 .= ", ".$d['glr22'];
			
			if ($d['jenjang']=='XP') $strPS = "Profesi ".$d['nm_ps'];
			else $strPS = $d['jenjang']." ".$d['nm_ps'];			
			$strMK = $d['nm_mk'];
			$strKls = $d['kode'].chr(64+$d['nm_kls']);			
			$strHari=""; $strSesi=""; $strPlot="";			
			if (!empty($d['hari'])) 
			{
				$strHari = $hr[$d['hari']];
				$strSesi = $d['nm_sesi'];
				$strPlot = $d['hari'].$d['nm_sesi'];
				if (empty($d['nm_ruang'])) $strRuang = "Daring";
				else $strRuang = $d['nm_ruang'];
			}				
			
			if ($d['no_dosen']!=$cNid) /* chekpoint 1 */
			{
				if (!$init) 
				{
					$dt['dataA'] = $detA;
					$tmpStr = explode(" ",$str1);
					if ($strDsn!="" and trim($tmpStr[0])!="S1")
					{
						$tmpB = array($str1, $str2, $str3, $strDsn); 
						$detB[] = $tmpB;
					}
					if (count($detB)>0) $dt['dataB'] = $detB;
					$dosen[] = $dt;
				}
				else $init=false;
				
				$dt = array();
				$detA = array();
				$detB = array();				
				$dt['nama'] = $strDosen1;	
				
				$tmpA = array($strPS, $strMK, $d['sks'], $strKls, $d['nm_jalur'], $strHari, $strSesi, $strRuang);
				if (!in_array($tmpA,$detA))	$detA[]=$tmpA;				
				$cekDsn = array();
				$str1 = $strPS;
				$str2 = $strMK;
				$str3 = $strKls;
				$strDsn = "tes";
				if ($d['jenjang']!='S1')
				{
					$strDsn = "1. ".$strDosen2;
					$cekDsn[] = $strDosen2;
				}
				
				$cNid = $d['no_dosen'];
				$cKls = $d['jenjang'].$strMK.$strKls;						

				$cekKls = array($cKls);
			}
			else
			{
				$kls = $d['jenjang'].$strMK.$strKls;
				
				if ($kls!=$cKls) //chekpoint 2
				{
					$cekDsn = null;
					$tmpA = array($strPS, $strMK, $d['sks'], $strKls, $d['nm_jalur'], $strHari, $strSesi, $strRuang);					
					if (!in_array($tmpA,$detA))	$detA[]=$tmpA;
					if ($d['jenjang']!='S1')
					{
						if (!in_array($kls,$cekKls))
						{
							$cekKls[] = $kls;
							$tmpStr = explode(" ",$str1);
							if (trim($tmpStr[0])!="S1")
							{
								$tmpB = array($str1, $str2, $str3, $strDsn); 
								$detB[] = $tmpB;
							}			
							
							$str1 = $strPS;
							$str2 = $strMK;
							$str3 = $strKls;
							$strDsn = "1. ".$strDosen2;
							$cekDsn[] = $strDosen2;			
						}
					}	

					$cKls = $kls;		
					$cPlot = $strPlot;
				}
				else
				{
					if ($d['jenjang']!='S1')
					{
						if (!in_array($strDosen2,$cekDsn))
						{
							$str1 = $strPS;
							$str2 = $strMK;
							$str3 = $strKls;
							$cekDsn[] = $strDosen2;
							$strDsn .= "\n".count($cekDsn).". ".$strDosen2;
						}
					}
					
					if ($strPlot!=$cPlot) //checkpoint3
					{
						$tmpA = array($strPS, $strMK, $d['sks'], $strKls, $d['nm_jalur'], $strHari, $strSesi, $strRuang);
						if (!in_array($tmpA,$detA))	$detA[]=$tmpA;
						if ($d['jenjang']!='S1')
						{
							if (!in_array($kls,$cekKls))
							{
								$cekKls[] = $kls;
								$tmpB = array($strPS, $strMK, $strKls, ""); 
							}
							//if ($strDosen2!="") $tmpB['dosen']=$strDosen2;
						}
						$cPlot = $strPlot;
					}				
				}
			}
		}
		$dt['dataA'] = $detA;
		$tmpStr = explode(" ",$str1);
		if ($strDsn!="" and trim($tmpStr[0])!="S1")
		{
			$tmpB = array($str1, $str2, $str3, $strDsn); 
			$detB[] = $tmpB;
		}
		if (count($detB)>0) $dt['dataB'] = $detB;
		$dosen[] = $dt;		

		$data['list'] = $dosen;
		$this->load->view('vw_pdf',$data);
	}
	public function expXLS()
	{
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
		$smt = $this->session->userdata('id_smt_jadwal');
		$jur = $this->session->userdata('id_jur_jadwal');
		$dtRep = $this->md_kelas->repDataXLS($smt,$jur);
		
		$this->load->library('Excel');
		$style1 = array('font'=>'Calibri','font-size'=>10,'font-style'=>'bold', 'fill'=>'#ffc800', 'halign'=>'center');
		$headers = array(
			'Jenjang' => 'string', 
			'Program Studi' => 'string', 
			'Kode MK' => 'string', 
			'Nama MK' => 'string', 
			'Thn Mk' => 'integer',
			'Kelas' => 'string',
			'Kode Hari' => 'integer',
			'Hari' => 'string',
			'Jml Max. Peserta' => 'integer',
			'Is Jadwal Lintas?' => 'integer',
			'Sesi' => 'integer',
			'Jam Mulai' => 'string',
			'Jam Selesai' => 'string',
			'Kode Ruang' => 'string',
			'Nama Ruang' => 'string',
			'Kode Gedung' => 'string',
			'Nama Gedung' => 'string',
			'NIP Dosen' => 'string',
			'Nama Dosen' => 'string',
			'NIDN' => 'string',
			'Jabfung' => 'string',
			'Pend. Akhir' => 'string',
			'Asal' => 'string',
			'Homebase' => 'string'
		);
		
		$writer = new Excel();
		$keywords = array('xlsx','MySQL','Codeigniter');
		$writer->setTitle('Penjadwalan FEB-UB');
		$writer->setSubject('Report generated by SI Penjadwalan FEB-UB');
		$writer->setAuthor('Akademik');
		$writer->setCompany('FEB-UB');
		$writer->setKeywords($keywords);
		$writer->setDescription('Export Penjadwalan');
		$writer->setTempDir(sys_get_temp_dir());
		
		$writer->writeSheetHeader('Sheet1', $headers, $style1);
		
		$style2 = array('font'=>'Calibri','font-size'=>10);
		foreach ($dtRep as $d) 
		{
			if ($d['jenjang']=='XP') $strJn = "Profesi";
			else $strJn = $d['jenjang'];
			$hari=""; $strHari=""; $strSesi=""; $strJam1=""; $strJam2="";
			if (!empty($d['hari'])) 
			{
				$hari = $d['hari'];
				$strHari = $hr[$hari];
				$strSesi = $d['nm_sesi']; 
				$strJam1 = $d['jam1'];
				$strJam2 = $d['jam2'];				
			}			
			$strKls = $d['kode'].chr(64+$d['nm_kls']);
			$strDosen = "";
			if (!empty($d['nm_dosen']))
			{
				if (trim($d['gelar1'])!="") $strDosen = $d['gelar1']." ".$d['nm_dosen']; else $strDosen=$d['nm_dosen'];
				if (trim($d['gelar2'])!="") $strDosen .= ", ".$d['gelar2'];
			}
			if ($d['jabfung']==1) $strJab = "Tenaga Pengajar";
			elseif ($d['jabfung']==2) $strJab = "Asisten Ahli";
			elseif ($d['jabfung']==3) $strJab = "Lektor";
			elseif ($d['jabfung']==4) $strJab = "Lektor Kepala";
			else $strJab = "Guru Besar";
			if ($d['pend_akhir']==1) $strPend = "S1";
			elseif ($d['pend_akhir']==2) $strPend = "S2";
			else $strPend = "S3";
			if ($d['jn_hb']==1) $strHB = "S1 ".$d['nm_hb'];
			elseif ($d['jn_hb']==2) $strHB = "S2 ".$d['nm_hb'];
			elseif ($d['jn_hb']==2) $strHB = "S3 ".$d['nm_hb'];
			else $strHB = "Profesi ".$d['nm_hb'];
			
			$writer->writeSheetRow('Sheet1', array( $strJn, $d['nm_ps'], $d['kode_mk'], $d['nm_mk'], $d['thkur'], $strKls, $hari, $strHari, '', '', $d['nm_sesi'], $strJam1, $strJam2, $d['kd_ruang'], $d['nm_ruang'], $d['kd_ged'], $d['nm_ged'], $d['no_dosen'], $strDosen,$d['nidn'], $strJab, $strPend, $d['asal_dosen'], $strHB), $style2);
		}
		
		$fileLocation = 'Export Jadwal FEB.xlsx';		
		$writer->writeToFile($fileLocation);		
		
		header('Content-Description: File Transfer');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=".basename($fileLocation));
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Length: ' . filesize($fileLocation)); //Remove

		ob_clean();
		flush();
		readfile($fileLocation);
		unlink($fileLocation);
		exit(0);
	}
	public function update_data()
	{
		$data = array(
			'nm_mk' => $this->input->post('nama'),
			'n_mhs' => $this->input->post('mhs')
		);
		$this->md_kelas->update($data,array('id_kls' => $this->input->post('idKelas'))); 
		echo json_encode(array("status" => TRUE));
	}	
	public function delete_data($id=null)
	{
		if (!empty($id)) $idDel = $id;
		else $idDel = $this->input->post('idHapus');
		$data['stat'] = 0;
		$this->md_kelas->update($data,array('id_kls' => $idDel)); 
		$this->md_doskel->update($data,array('id_kls' => $idDel)); 
		$this->md_plot->update($data,array('id_kls' => $idDel));

		$user = $this->session->userdata('id_user_jadwal');
		$dtm = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dtm->format('y');
		$tgl = $dtm->format('Y-m-d');
		$jam = $dtm->format('H:i:s');
		$dtLogK['id_log_kls'] = $this->md_kelas->getMaxLogID($th);
		$dtLogK['id_user'] = $user;
		$dtLogK['dt'] = $tgl." ".$jam;	
		$dtLogK['act'] = 3;
		$dtLogP['id_user'] = $user;
		$dtLogP['dt'] = $tgl." ".$jam;	
		$dtLogP['act'] = 3;
		$dtLogD['id_user'] = $user;
		$dtLogD['dt'] = $tgl." ".$jam;	
		$dtLogD['act'] = 3;

		$dtLogK['id_kls'] = $idDel;
		$this->md_kelas->saveLog($dtLogK);			
		
		$jadwalDosen = $this->md_kelas->getJadwalKls($idDel);
		if (count($jadwalDosen)>0)
			foreach($jadwalDosen as $d)
			{
				$dtLogP['id_log_plot'] = $this->md_plot->getMaxLogID($th);
				$dtLogP['id_plot'] = $d['id_plot'];
				$this->md_plot->savePlot($dtLogP);			
			}
		
		$dosenKls = $this->md_kelas->getDosenKls($idDel);
		if (count($dosenKls)>0)
			foreach($dosenKls as $d) 
			{
				$dtLogD['id_log_doskel'] = $this->md_doskel->getMaxLogID($th);
				$dtLogD['id_doskel'] = $d['id_doskel'];
				$this->md_doskel->saveDosen($dtLogD);			
			}
		
		echo json_encode(array("status" => TRUE));
	}			
}
?>