<?php
class Md_berma extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_berma) maxID FROM berma ";
		$str .= "WHERE id_berma LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."90001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_berma) maxID FROM log_berma ";
		$str .= "WHERE id_log_berma LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."20001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function save($data)
	{
		$this->db->insert('berma',$data);
		return $this->db->insert_id();
	}
	
	public function saveLog($data)
	{
		$this->db->insert('log_berma',$data);
		return $this->db->insert_id();
	}
	
	public function update($data,$where)
	{
		$this->db->update('berma',$data,$where);
		return $this->db->affected_rows();
	}
	
	public function delete($where)
	{
		$this->db->delete('berma',$where);		
	}
}
