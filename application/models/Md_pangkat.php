<?php
class Md_pangkat extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM pangkat ORDER BY id_pangkat";		
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function save($data)
	{
		$this->db->insert('pangkat',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('pangkat',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('pangkat',$where);		
	}
}
