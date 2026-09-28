<?php
class Md_progression extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_progression) maxID FROM progression ";
		$str .= "WHERE id_progression LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."70001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
		
	public function save($data)
	{
		$this->db->insert('progression',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('progression',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('progression',$where);		
	}
}
