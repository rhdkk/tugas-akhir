<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class md_konsentrasi extends CI_Model {
	public function getData()
	{
		$data = $this->db->query('SELECT * FROM konsentrasi ORDER BY id_konsentrasi');
		return $data->result_array();		
	}
}
