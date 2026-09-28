<?php
class Md_set extends CI_Model {
	public function getData()
	{
		$str = "SELECT * FROM settings ORDER BY id_set";		
		$data = $this->db->query($str);
		return $data->result_array();				
	}	
	public function update($data,$where=null)
	{
		$this->db->update('settings',$data,$where);
		return $this->db->affected_rows();
	}	
}
