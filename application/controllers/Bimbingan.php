<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bimbingan extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_bimbingan');				
		$this->load->model('md_mahasiswa');				
		$this->load->model('md_dospem');				
		$this->load->model('md_progress');				
		$this->load->model('md_progression');				
	} 
	
	public function validasi()
	{
		$id_user = $this->session->userdata('id_user_thesis');		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$id = $this->input->post('idBimb');
		$jns = $this->input->post('jnsBimb');
		$val = $this->input->post('accBimb');
		
		$data['acc_bimb']=$val;
		$this->md_bimbingan->update($data,array('id_bimb'=>$id)); 
		
		$dtLog['id_log_bimb'] = $this->md_bimbingan->getMaxLogID($th);
		$dtLog['id_user'] = $id_user;			
		$dtLog['dt'] = $tgl." ".$jam;			
		$dtLog['act'] = 2;	
		$dtLog['id_bimb'] = $id;
		$dtLog['acc_bimb'] = $val;
		$this->md_bimbingan->saveLog($dtLog);	
		
		if ($val==2)
		{
			if ($jns==2)
			{
				$nim = $this->input->post('nimBD');
				$nPemb = $this->input->post('nPb');
				$accU = $this->input->post('accUji') + 1;
				
				$dtMhs['nim'] = $nim;
				$dtMhs['acc_uji'] = $accU;
				$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
				$dtLogMhs['id_user'] = $id_user;			
				$dtLogMhs['dt'] = $tgl." ".$jam;			
				$dtLogMhs['act'] = 2;	
				$dtLogMhs['nim'] = $nim;
				$dtLogMhs['acc_uji'] = $accU;
				
				if ($accU==$nPemb)  
				{
					$id_prog = $this->input->post('idPg');
					$id_nextProg = $this->md_progress->getData($id_prog)->id_next1;
					
					$dtMhs['id_progress'] = $id_nextProg;
					$dtLogMhs['id_progress'] = $id_nextProg;
					
					$dtProgr['id_progression'] = $this->md_progression->getMaxID($th);
					$dtProgr['id_user'] = $id_user;			
					$dtProgr['dt_progression'] = $tgl." ".$jam;					
					$dtProgr['nim'] = $nim;
					$dtProgr['id_progress'] = $id_prog;
					$this->md_progression->save($dtProgr);
				}						
				$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));			
				$this->md_mahasiswa->saveLog($dtLogMhs);
				
				$idDP = $this->input->post('idDP');
				$dtDP['stat_dospem'] = 2;
				$dtLogDP['id_log_dospem'] = $this->md_dospem->getMaxLogID($th);
				$dtLogDP['id_user'] = $id_user;			
				$dtLogDP['id_dospem'] = $idDP;			
				$dtLogDP['dt'] = $tgl." ".$jam;			
				$dtLogDP['act'] = 2;	
				$dtLogDP['stat_dospem'] = 2;						
				$this->md_dospem->update($dtDP,array('id_dospem'=>$idDP));			
				$this->md_dospem->saveLog($dtLogDP);
			}
			elseif ($jns==3)
			{
				$nim = $this->input->post('nimBD');
				$newTitle = $this->input->post('judulNew');
				$dtMhs['judul_ta'] = $newTitle;
				$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
				$dtLogMhs['id_user'] = $id_user;			
				$dtLogMhs['dt'] = $tgl." ".$jam;			
				$dtLogMhs['act'] = 2;	
				$dtLogMhs['nim'] = $nim;
				$dtLogMhs['judul_ta'] = $newTitle;
				$this->md_mahasiswa->update($dtMhs,array('nim'=>$nim));			
				$this->md_mahasiswa->saveLog($dtLogMhs);
			}
		}
		
		echo json_encode(array("status"=>TRUE));
	}
}
?>