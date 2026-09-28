<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ujian extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mysql_table');	
		$this->load->library('excel');					
		$this->load->model('md_ujian');			
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