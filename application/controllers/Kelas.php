<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kelas extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mysql_table');	
		$this->load->library('excel');	
		$this->load->model('md_kelas');				
		$this->load->model('md_dosen');
		$this->load->model('md_ruang');
		$this->load->model('md_matkul');
		$this->load->model('md_sesi');
		$this->load->model('md_prodi');		
		$this->load->model('md_plot');		
		$this->load->model('md_dospem');		
		$this->load->model('md_set');		
		$this->load->model('md_smt');		
		$this->load->model('md_mahasiswa');		
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
	
	public function ajax_jadwal($id)
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$data = $this->md_kelas->getJadwal($id);
		$ary = array();
		foreach($data as $d)
		{ 	
			$row = array(
				'id_kls' => $d['id_kls'],
				'nm_mk' => $d['nm_mk'],
				'nm_kls' => chr(64+$d['nm_kls']),
				'n_mhs' => $d['n_mhs'],			
				'id_plot' => $d['id_plot'],
				'id_sesi' => $d['id_sesi'],
				'id_ruang' => $d['id_ruang'],
				'hari' => $d['hari']
			);
			$ary[] = $row;
		}	
							
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	public function ajax_dosen($id)
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$data = $this->md_kelas->getDosen($id);
		$ary = array();
		foreach($data as $d)
		{ 	
			$row = array(
				'id_kls' => $d['id_kls'],
				'nm_mk' => $d['nm_mk'],
				'nm_kls' => chr(64+$d['nm_kls']),
				'id_doskel' => $d['id_doskel'],
				'id_dosen' => $d['id_dosen']
			);
			$ary[] = $row;
		}	
							
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_doskel($id)
	{
		$data = $this->md_doskel->getData($id);
		$row = array(
			'mk' => $data->nm_mk,
			'kls' => $data->kode.chr(64+$data->nm_kls),		
			'dosen' => $data->nm_dosen
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function ajax_plot($id)
	{
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
		$data = $this->md_plot->getData($id);
		if (!empty($data->nm_ruang)) $ruang=$data->nm_ruang; else $ruang="";
		$row = array(
			'hari' => $hr[$data->hari],
			'sesi' => $data->nm_sesi." (".$data->jam1." - ".$data->jam2.")",								
			'ruang' => $ruang,
			'mk' => $data->nm_mk,
			'kls' => $data->kode.chr(64+$data->nm_kls)
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}	
	
	public function ajax_kelas($id)
	{
		$data = $this->md_kelas->getData(null,$id,null);
		$row = array(
			'nm_mk' => $data->nm_mk,
			'kls' => $data->kode.chr(64+$data->nm_kls)
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}	
	
	public function open_kelas()
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$nMK = $this->input->post('nMK');
		$isi = false; 
		$i = 0;
		while ($i<$nMK)
		{
			$i++;
			$nm = "cb".$i;	
			$CB = $this->input->post($nm);
			if (!empty($CB))
			{
				$sCB = explode("-",$CB);					
				$a1 = (int)$sCB[0];
				$a2 = (int)$sCB[1];	
				if ($a2>0) { $isi=true; $i=$nMK; }
			}
		}
		if ($isi)
		{
			for ($i=1; $i<=$nMK; $i++)
			{
				$nmTX = "tx".$i;
				$nmCB = "cb".$i;
				$nK = $this->input->post($nmCB);
				if (!empty($nK))
				{
					$snK = explode("-",$nK);					
					$ada = (int) $snK[0];
					$baru = (int) $snK[1];					
					if ($baru>0)
					{	
						$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
						$th = $dt->format('y');
						$tgl = $dt->format('Y-m-d');
						$jam = $dt->format('H:i:s');						
						
						for ($j=($ada+1); $j<=($ada+$baru); $j++)
						{
							$idKls = $this->md_kelas->getMaxID($smt);
							$data = array(
								'id_kls' => $idKls,
								'id_mk' => $this->input->post($nmTX),
								'id_jalur' => $this->input->post("jalur"),
								'id_smt' => $smt,
								'nm_kls' => $j
							);
							$dtLog = array(
								'id_user' => $this->session->userdata('id_user_jadwal'),
								'dt' => $tgl." ".$jam,
								'id_log_kls' => $this->md_kelas->getMaxLogID($th),
								'act' => 1,
								'id_kls' => $idKls,
								'id_mk' => $this->input->post($nmTX),
								'id_jalur' => $this->input->post("jalur"),
								'id_smt' => $smt,
								'nm_kls' => $j
							);
							$this->md_kelas->save($data);
							$this->md_kelas->saveLog($dtLog);
						}
					}
				}
			}
		}
		echo json_encode(array("status" => TRUE, "isi" => $isi));
	}
	
	
	public function save_thesis()
	{		
		$id_user = $this->session->userdata('id_user_thesis');		
		$mhs = $this->md_mahasiswa->getDetil($id_user);	
		$nim = $mhs->nim;
		$act = false;
		$nP = $this->input->post('nPemb');
		
		for ($i=1; $i<=$nP; $i++)
		{
			$pemb = $this->input->post('pemb'.$i);
			$aryPemb[$i] = $pemb;
			$idP = $this->input->post('pemb'.$i);						
		}		
		if ($nP>count(array_unique($aryPemb))) $unik=false; else $unik=true;				
		
		if ($unik)
		{
			for ($i=1; $i<=$nP; $i++)
			{
				$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
				$th = $dt->format('y');
				$tgl = $dt->format('Y-m-d');
				$jam = $dt->format('H:i:s');
				//$dtLog['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
				//$dtLog['id_user'] = $user;
				//$dtLog['dt'] = $tgl." ".$jam;			
					
				$idDospem = $this->md_dospem->getMaxID($th);				
				$data['id_dospem'] = $idDospem;
				$data['id_dosen'] = $aryPemb[$i];
				$data['nim'] = $nim;
				$data['urut_pemb'] = $i;
				
				//$this->md_dospem->save($data);				
			}
			$dtMhs['judul_ta'] = $this->input->post('judul');
			$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));				
			$act = true;
		}
		
		echo json_encode(array("status"=>true, "db"=>$act, "unik"=>$unik));
	}
	public function save_jadwal()
	{		
		$smt = $this->session->userdata('id_smt_jadwal');		
		$user = $this->session->userdata('id_user_jadwal');		
		$act1 = false;
		$idKelas = $this->input->post('idKelasP');		
		$dtPS = $this->md_kelas->getDataPS($idKelas);		
		$idPS = $dtPS->id_ps;		
		$nmPS = $dtPS->nm_ps;		
		$maxPlot = $dtPS->plot;		
		$nP = $this->input->post('nPlot');
		/*UPDATE kelas*/
		$mhs0 = $this->input->post('edMhs');
		$mhs1 = $this->input->post('mhs');
		if ($mhs0!=$mhs1)
		{			
			$this->md_kelas->update(array('n_mhs'=>$mhs1), array('id_kls'=>$idKelas));		
			$act1 = true;
		}
		/*Setting Jadwal*/
		$act2 = false;
		$xPS = array();
		$xPlot = array();
		$xDosen = array();
		$stat0 = true;
		$stat1 = true;
		$stat2 = true;
		for ($i=0; $i<$nP; $i++)
		{
			$hari0 = $this->input->post('edHari'.$i);
			$hari1 = $this->input->post('hari'.$i);
			$sesi0 = $this->input->post('edSesi'.$i);
			$sesi1 = $this->input->post('sesi'.$i);
			$ruang0 = $this->input->post('edRuang'.$i);
			$ruang1 = $this->input->post('ruang'.$i);
			if (empty($ruang0)) $ruang0="-"; 
			if (empty($ruang1)) $ruang1="-"; 
			if ($ruang1=="x") $ruang1="0"; 
			$aryPlot0[$i] = $hari0."|".$sesi0."|".$ruang0;
			$aryPlot1[$i] = $hari1."|".$sesi1."|".$ruang1;

			$idP = $this->input->post('idPlot'.$i);
			if (!empty($idP)) $dtPlot['id_plot']=$idP;
			$dtPlot['id_ps'] = $idPS;			
			$dtPlot['id_kls'] = $idKelas;
			$dtPlot['hari'] = $hari1;
			$dtPlot['id_sesi'] = $sesi1;
			$dtPlot['id_ruang'] = $ruang1;
			
			$nPlotPS = $this->md_plot->cekJmlPlot($dtPlot);
			if ($nPlotPS>$maxPlot) 
			{
				$stat0 = false;
				$xPS[] = $i."|".$nmPS."|".$maxPlot; 
			}
			
			if ($ruang1!="-" and $ruang1!="0")
			{
				$cekPlot = $this->md_plot->cekPlot($dtPlot);
				if (strlen($cekPlot)>1)
				{
					$stat1 = false;		
					$xPlot[] = $i."|".$cekPlot; 
				}
			}			

			$dosenKls = $this->md_kelas->getDosenKls($idKelas);
			if (count($dosenKls)>0)
			{
				foreach($dosenKls as $d)
				{ 	
					$dtDosen['jenjang'] = $d['jenjang'];
					$dtDosen['id_dosen'] = $d['id_dosen'];
					$dtDosen['id_kls'] = $idKelas;
					$dtDosen['hari'] = $hari1;
					$dtDosen['id_sesi'] = $sesi1;
					$cekDosen = $this->md_plot->cekDosen($dtDosen);
					if (strlen($cekDosen)>1)
					{
						$stat2 = false;		
						$xDosen[] = $i."|".$cekDosen; 
					}
				}								
			}
		}		
		if ($nP>count(array_unique($aryPlot1))) $unik=false; else $unik=true;				
		
		if ($unik and $stat0 and $stat1 and $stat2)
		{
			for ($i=0; $i<$nP; $i++)
			{
				$idP = $this->input->post('idPlot'.$i);
				$data['id_kls'] = $idKelas;			
				
				$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
				$th = $dt->format('y');
				$tgl = $dt->format('Y-m-d');
				$jam = $dt->format('H:i:s');
				$dtLog['id_log_plot'] = $this->md_plot->getMaxLogID($th);
				$dtLog['id_user'] = $user;
				$dtLog['dt'] = $tgl." ".$jam;			
					
				if (!empty($idP)) 
				{
					if ($aryPlot0[$i]!=$aryPlot1[$i])					
					{
						$dtLog['id_plot'] = $idP;
						$tmp0 = explode("|",$aryPlot0[$i]);
						$tmp1 = explode("|",$aryPlot1[$i]);
						if ($tmp0[0]!=$tmp1[0]) { $data['hari']=$tmp1[0]; $dtLog['hari']=$tmp1[0]; }
						if ($tmp0[1]!=$tmp1[1]) { $data['id_sesi']=$tmp1[1]; $dtLog['id_sesi']=$tmp1[1]; }
						if ($tmp0[2]!=$tmp1[2]) { if ($tmp1[2]!="-") {$data['id_ruang']=$tmp1[2]; $dtLog['id_ruang']=$tmp1[2];} }		
						$dtLog['act'] = 2;
						
						$this->md_plot->update($data,array('id_plot' => $idP));
						$this->md_plot->savePlot($dtLog);
						$act2 = true;
					}						
				}
				else 
				{
					$idPlot = $this->md_plot->getMaxID($smt);
					$dtLog['id_plot'] = $idPlot;
					$dtLog['hari'] = $hari1;
					$dtLog['id_sesi'] = $sesi1;
					if ($ruang1!="-") $dtLog['id_ruang']=$ruang1;					
					$dtLog['act'] = 1;
					
					$data['id_plot'] = $idPlot;
					$data['hari'] = $hari1;
					$data['id_sesi'] = $sesi1;
					if ($ruang1!="-")  $data['id_ruang']=$ruang1;					
					$this->md_plot->save($data);
					$this->md_plot->savePlot($dtLog);
					
					$act2 = true;
				}
			}
		}
		
		if (!$act1 and !$act2) $act=0;
		elseif ($act1 and !$act2) $act=1;
		elseif (!$act1 and $act2) $act=2;
		elseif ($act1 and $act2) $act=3;
		
		echo json_encode(array("status0"=>$stat0, "xPS"=>$xPS, "status1"=>$stat1, "xPlot"=>$xPlot, "status2"=>$stat2, "xDosen"=>$xDosen, "db"=>$act, "unik"=>$unik));
	}
	public function expPDF()
	{
		$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
		$smt = $this->session->userdata('id_smt_jadwal');
		$dtRep = $this->md_kelas->repDataPDF($smt);				
		
		$cNid = "x"; 
		$init = true; //inisialisasi
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
			
			if ($d['no_dosen']!=$cNid) //chekpoint 1
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