<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Akun extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_user');				
		$this->load->model('md_prodi');		
		$this->load->model('md_sesi');		
		$this->load->model('md_dosen');		
		$this->load->model('md_ruang');		
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hakJadwal');
		$id_user = $this->session->userdata('id_user_jadwal');
		$data['sesi'] = $this->md_sesi->getData();
		$data['prodi'] = $this->md_prodi->getData();
		$data['dosen'] = $this->md_dosen->getData();
		$data['ruang'] = $this->md_ruang->getData();
		$data['akses'] = $this->md_prodi->getAkses($id_user);
		$this->load->view('vw_akun',$data);		
	}	
	public function ajax_list($ps=null)
	{		
		$id = null;
		$data = $this->md_user->getData($id);
		$ary = array();		
		$IDt = "x";
		$num = 0;
		$aryAkses = array(); 
		foreach($data as $d)
		{ 
			$num++;
			if ($IDt==$d['id_user'])
			{
				$tAkses="";
				if (!empty($d['id_ps'])) 
					$tAkses = $d['id_user']."|".$d['id_ps']."|".$d['nm_ps'];
				if (!in_array($tAkses, $aryAkses)) $aryAkses[]=$tAkses;					
			}
			if ($IDt!=$d['id_user'] or $num==count($data))
			{
				if ($IDt!="x")
				{
					$row = array();
					$row[] = $tUser;			
					$row[] = $tNama;					
					$row[] = $tJenis;					
					$akses = "<ul class='list-group list-group-table'>"; $i=0;
					foreach ($aryAkses as $tA) 
					{
						if ($tA!="") 
						{
							$tmp = explode("|",$tA);
							$akses.="<li class='list-group-item'>".$tmp[2]." <a class='del-list' href='javascript:void()' title='Hapus Akses Prodi' onclick='hapus_akses(\"".$tmp[0]."-".$tmp[1]."\")'><i class='glyphicon glyphicon-remove'></i></a></li>";
						}
						$i++;
					}					
					$row[] = $akses."</ul>";										
					$aryAkses = array();					 		
					$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_akun(\"".$IDt."\")'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='hapus_akun(\"".$IDt."\")'><i class='glyphicon glyphicon-trash'></i></a></div>";
					$ary[] = $row;
				}
				
				$tUser = $d['username'];
				$tNama = $d['nm_user'];
				if ($d['hak']==1) $tJenis = 'Super Admin';	
				elseif ($d['hak']==2) $tJenis = 'Admin';	
				elseif ($d['hak']==3) $tJenis = 'Pimpinan';	
				elseif ($d['hak']==4) $tJenis = 'Admin PS';	
				
				$akses="<ul class='list-group list-group-table'>"; 
				$tAkses="";
				if (!empty($d['id_ps'])) 
					$tAkses = $d['id_user']."|".$d['id_ps']."|".$d['nm_ps'];
				if (!in_array($tAkses, $aryAkses)) $aryAkses[]=$tAkses;
				
				$IDt = $d['id_user'];
				
				if ($num==count($data) and $IDt!=$d['id_user'])
				{
					$row = array();
					$row[] = $tUser;			
					$row[] = $tNama;					
					$row[] = $tJenis;		
					$dosen="<ul class='list-group'>"; 
					foreach ($aryAkses as $tA) 
					{
						if ($tA!="") 
						{
							$tmp = explode("|",$tA);
							$akses.="<li class='list-group-item'>".$tmp[2]." <a class='del-list' href='javascript:void()' title='Hapus Akses Prodi' onclick='hapus_akses(\"".$tmp[0]."-".$tmp[1]."\")'><i class='glyphicon glyphicon-remove'></i></a></li>";
						}
						$i++;
					}
					$plot = "<ul class='list-group'>"; $i=0;
					$plot="<ul class='list-group'>"; 
					
					$row[] = $akses."</ul>";
					$aryAkses = array();					 		
					$row[] = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Edit' onclick='edit_akun(\"".$IDt."\")'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='hapus_akun(\"".$IDt."\")'><i class='glyphicon glyphicon-trash'></i></a></div>";
					$ary[] = $row;
				}
			}
		}
		$output = array("data" => $ary);
		echo json_encode($output);		
	}
	public function ajax_akun($id)
	{
		$data = $this->md_user->getData($id);
		$row = array(
			'username' => $data[0]['username'],
			'nm_user' => $data[0]['nm_user'],
			'hak' => $data[0]['hak']
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}		
	public function ajax_edit($id)
	{
		$data = $this->md_user->getData($id);
		$ary = array();
		foreach($data as $d)
		{ 	
			$row = array(
				'id_user' => $d['id_user'],
				'username' => $d['username'],
				'nm_user' => $d['nm_user'],
				'hak' => $d['hak'],			
				'id_ps' => $d['id_ps'],
				'nm_ps' => $d['nm_ps']
			);
			$ary[] = $row;
		}	
							
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	public function ajax_akses($id)
	{
		$data = $this->md_user->getAkses($id);
		$row = array(
			'nm_ps' => $data->nm_ps,
			'nm_user' => $data->nm_user
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	public function add_data()
	{		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
	
		$idUser = $this->md_user->getMaxID();
		$data = array(
			'id_user' => $idUser,
			'username' => $this->input->post('user'),			
			'nm_user' => $this->input->post('nama'),			
			'pass' => '91fa402654f2ae72861fbe16980d279c',
			'hak' => $this->input->post('jenis')			
		);
		$this->md_user->save($data);
		
		$maxIDLog = $this->md_user->getMaxLogID();
		$data['id_log_user'] = $maxIDLog;
		$data['id_opt'] = $this->session->userdata('id_user_jadwal');
		$data['dt'] = $tgl." ".$jam;
		$data['act'] = 1;
		$this->md_user->saveLog($data);		
		
		if ($this->input->post('jenis')==4)
		{
			$nA = $this->input->post('nProdi');
			for ($i=0; $i<$nA; $i++)
			{
				$dtAkses['id_user'] = $idUser;
				$dtAkses['id_ps'] = $this->input->post('prodi'.$i);
				$this->md_user->saveAkses($dtAkses);
				
				$dtAkses['id_log_user'] = $maxIDLog;
				$dtAkses['id_opt'] = $this->session->userdata('id_user_jadwal');
				$dtAkses['dt'] = $tgl." ".$jam;
				$dtAkses['act'] = 1;
				$this->md_user->saveLogAkses($dtAkses);
			}
		}		
		
		echo json_encode(array("status" => TRUE));
	}
	public function update_data()
	{
		$n = 0; 
		$lbEdit = array ('edUser','edNama','edJenis','edProdi');
		$lbForm = array ('user','nama','jenis','prodi');
		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		
		$save = false;
		$n=0;
		for ($i=0; $i<3; $i++)
		{
			$dtAkun[$lbForm[$i]] = null;
			$dtLog1[$lbForm[$i]] = null;
			if (trim($this->input->post($lbEdit[$i]))!=trim($this->input->post($lbForm[$i]))) 
			{ 
				$dtAkun[$lbForm[$i]] = trim($this->input->post($lbForm[$i]));
				$dtLog1[$lbForm[$i]] = trim($this->input->post($lbForm[$i]));						
				$n++;
			} 
		}
		if ($n>0)
		{ 
			$dtLog1['id_user'] = $this->input->post('idAkun');
			$dtLog1['id_log_user'] = $this->md_user->getMaxLogID($th);
			$dtLog1['id_opt'] = $this->session->userdata('id_user_jadwal');;
			$dtLog1['dt'] = $tgl." ".$jam;
			$dtLog1['act'] = 2;
		
			$this->md_user->update($dtAkun, array('id_user' => $this->input->post('idAkun'))); 
			$this->md_user->saveLog($dtLog1);
			$save = true;
		}
		
		$nData0 = $this->input->post('edNP');
		$nData1 = $this->input->post('nProdi');
		if ($nData1>0)
		{
			$dtAkses['id_user'] = $this->input->post('idAkun');
			
			$dtLog2['id_user'] = $this->input->post('idAkun');			
			$dtLog2['id_log_akses'] = $this->md_user->getMaxLogAksesID($th);
			$dtLog2['id_opt'] = $this->session->userdata('id_user_jadwal');;
			$dtLog2['dt'] = $tgl." ".$jam;
			$dtLog2['act'] = 2;
			for ($i=0; $i<$nData1; $i++)
			{
				if (trim($this->input->post('edProdi'.$i))!=trim($this->input->post('prodi'.$i)))
				{
					$dtAkses['id_ps'] = trim($this->input->post('prodi'.$i));
					$dtLog2['id_ps'] = trim($this->input->post('prodi'.$i));		

					if ($i<$nData0)	$this->md_user->updateAkses($dtAkses, array('id_user' => $this->input->post('idAkun'))); 
					else $this->md_user->saveAkses($dtAkses); 
					$this->md_user->saveLogAkses($dtLog2);
					$save = true;
				}				
			}
		}
		
		echo json_encode(array("status" => TRUE, "save" => $save));
	}	
	public function delete_akses()
	{
		$tmp = explode("-",$this->input->post('idHapus'));
		$data['stat'] = 0;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		$dtLog['id_opt'] = $tmp[0];
		$dtLog['id_ps'] = $tmp[1];
		$dtLog['id_log_akses'] = $this->md_user->getMaxLogAksesID($th);
		$dtLog['id_user'] = $this->session->userdata('id_user_jadwal');;
		$dtLog['dt'] = $tgl." ".$jam;
		$dtLog['act'] = 3;		
		
		$this->md_user->updateAkses($data, array('id_user'=>$tmp[0],'id_ps'=>$tmp[1]));
		$this->md_user->saveLogAkses($dtLog);
		echo json_encode(array("status" => TRUE));
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