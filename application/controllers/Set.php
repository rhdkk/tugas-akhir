<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Set extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_set');				
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hak_jadwal');
		if (!empty($hak))
		{
			$this->load->view('vw_set');		
		} else redirect('login','refresh');
	}
	
	public function ajax_list()
	{
		$data = $this->md_set->getData();
		foreach($data as $d)
		{
			$chk=""; if ($d['stat']==1) $chk="checked";
			echo "<tr><td>".$d['var']."</td><td class='text-right'><input id='ed".$d['id_set']."' name='ed".$d['id_set']."' type='hidden' value='".$d['stat']."'><input type='checkbox' name='set".$d['id_set']."' ".$chk." data-toggle='toggle' data-on='ON' data-off='OFF' data-onstyle='success' data-offstyle='danger' data-size='mini'></td></tr>";
		}
	}
	
	public function update_data($num)
	{		
		$save = false;
		for ($i=1; $i<=$num; $i++)
		{
			$nm1="ed".$i; $nm2="set".$i;			
			$isi = $this->input->post($nm2);
			if (!$isi) $val=2; else $val=1;
			$arySet[$i] = $val;
			if ($val!=$this->input->post($nm1))
			{
				$save = true;
				$data = array('stat'=>$val);
				$this->md_set->update($data,array('id_set'=>$i)); 
			}
		}
		echo json_encode(array("status"=>TRUE, "db"=>$save, "sets"=>$arySet));
	}	
}