<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Revisi extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_bimbingan');				
		$this->load->model('md_mahasiswa');				
		$this->load->model('md_dospem');				
		$this->load->model('md_tuji');				
		$this->load->model('md_progress');				
		$this->load->model('md_progression');				
		$this->load->model('md_ujian');				
		$this->load->model('md_revisi');				
	} 
	
	public function validasi()
	{
		$id_user = $this->session->userdata('id_user_thesis');		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$id = $this->input->post('idRev');
		$val = $this->input->post('accRev');
		$idDetUji = $this->input->post('idDURev');
		$revTipe = $this->input->post('tipeRev');		
		$revTipe = $this->input->post('idDPRev');		
		$data['acc_rev']=$val;
		
		if ($revTipe==2)
		{
			$this->md_revisi->update($data,array('id_rev'=>$id)); 
			
			$dtLog['id_log_rev'] = $this->md_revisi->getMaxLogID($th);
			$dtLog['id_user'] = $id_user;			
			$dtLog['dt'] = $tgl." ".$jam;			
			$dtLog['act'] = 2;
			$dtLog['id_rev'] = $id;
			$dtLog['acc_rev'] = $val;
			$this->md_revisi->saveLog($dtLog);			
		}
		else
		{
			$rev = $this->md_revisi->getAllRev($idDetUji);					
			foreach($rev as $rv)  
			{ 			
				$idRev = $rv['id_rev'];
				$this->md_revisi->update($data,array('id_rev'=>$idRev));
				
				$dtLog['id_log_rev'] = $this->md_revisi->getMaxLogID($th);
				$dtLog['id_user'] = $id_user;			
				$dtLog['dt'] = $tgl." ".$jam;			
				$dtLog['act'] = 2;
				$dtLog['id_rev'] = $id;
				$dtLog['acc_rev'] = $val;
				$this->md_revisi->saveLog($dtLog);
				
				unset($dtLog);
			} 		
		}
		
		if ($val==2)
		{
			if ($revTipe==2)
			{
				$idDP = $this->input->post('idDPRev');
				$dtDP['stat_dospem'] = 4;
				$this->md_dospem->update($dtDP,array('id_dospem'=>$idDP));	
				
				$dtDP['id_log_dospem'] = $this->md_dospem-> getMaxLogID($th);
				$dtDP['id_user'] = $id_user;			
				$dtDP['id_dospem'] = $idDP;			
				$dtDP['dt'] = $tgl." ".$jam;			
				$dtDP['act'] = 2;	
				$this->md_dospem->saveLog($dtDP);
				
				$nim = $this->input->post('nimRev');
				$nX = $this->md_mahasiswa->getTimBelum($nim);	
				
				if ($nX==0)
				{
					unset($dtMhs);
					$mhs = $this->md_mahasiswa->getDetil($nim);			
					$id_prog = $mhs->id_progress;				
					
					$id_tuji = $mhs->id_tuji;
					$id_tujiAkhir = $mhs->id_tuji_akhir;			
					
					/*LULUS*/
					if ($id_tuji==$id_tujiAkhir)
					{
						$stDospem = 5;					
						$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;
					}
					/*NEXT*/
					else
					{
						$stDospem = 1;					
						$id_nextProg = $this->md_progress->getData($id_prog)->id_next2;					
						$dtMhs['id_tuji'] = $this->md_tuji->getNext($mhs->id_ps,$mhs->urut_tuji);
					}
					
					$nilai = $this->md_ujian->getNilaiUjian($idDetUji);
					$dtUji['nilai_uji'] = $nilai->nilaiUji; 
					$this->md_ujian->update($dtUji,array('id_uji'=>$nilai->id_uji));
					
					$dtDospem['stat_dospem'] = $stDospem;
					$this->md_mahasiswa->updateDospem($dtDospem,array('nim'=>$nim,'stat'=>1));
				
					$tim = $this->md_mahasiswa->getTim($nim);					
					foreach($tim as $tm)  
					{ 			
						$idDPU = $tm['id_dospem'];
						$dtDPU['stat_dospem'] = $stDospem;					
						$dtDPU['id_log_dospem'] = $this->md_dospem-> getMaxLogID($th);
						$dtDPU['id_user'] = $id_user;			
						$dtDPU['id_dospem'] = $idDPU;			
						$dtDPU['dt'] = $tgl." ".$jam;			
						$dtDPU['act'] = 2;
						$this->md_dospem->saveLog($dtDPU);
						
						unset($dtDPU);
					} 	
					
					$dtMhs['id_progress'] = $id_nextProg;
					$dtMhs['acc_uji'] = 0;
					$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));
					
					$dtMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
					$dtMhs['id_user'] = $id_user;			
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
			}
			else
			{
				$nim = $this->input->post('nimRev');
				$mhs = $this->md_mahasiswa->getDetil($nim);			
				$id_prog = $mhs->id_progress;				
				$id_tuji = $mhs->id_tuji;
				$id_tujiAkhir = $mhs->id_tuji_akhir;			
				unset($dtMhs);
				
				/*LULUS*/
				if ($id_tuji==$id_tujiAkhir)
				{
					$stDospem = 5;					
					$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;
				}
				/*NEXT*/
				else
				{
					$stDospem = 1;					
					$id_nextProg = $this->md_progress->getData($id_prog)->id_next2;					
					$dtMhs['id_tuji'] = $this->md_tuji->getNext($mhs->id_ps,$mhs->urut_tuji);
				}
				
				$nilai = $this->md_ujian->getNilaiUjian($idDetUji);
				$dtUji['nilai_uji'] = $nilai->nilaiUji; 
				$this->md_ujian->update($dtUji,array('id_uji'=>$nilai->id_uji));
				
				$dtDospem['stat_dospem'] = $stDospem;
				$this->md_mahasiswa->updateDospem($dtDospem,array('nim'=>$nim,'stat'=>1));
			
				$tim = $this->md_mahasiswa->getTim($nim);					
				foreach($tim as $tm)  
				{ 			
					$idDPU = $tm['id_dospem'];
					$dtDPU['stat_dospem'] = $stDospem;					
					$dtDPU['id_log_dospem'] = $this->md_dospem-> getMaxLogID($th);
					$dtDPU['id_user'] = $id_user;			
					$dtDPU['id_dospem'] = $idDPU;			
					$dtDPU['dt'] = $tgl." ".$jam;			
					$dtDPU['act'] = 2;
					$this->md_dospem->saveLog($dtDPU);
					
					unset($dtDPU);
				} 	
				
				$dtMhs['id_progress'] = $id_nextProg;
				$dtMhs['acc_uji'] = 0;
				$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));
				
				$dtMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
				$dtMhs['id_user'] = $id_user;			
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
		}
			
		echo json_encode(array("status"=>TRUE));
	}
}
?>