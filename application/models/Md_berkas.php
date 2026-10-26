<?php
class Md_berkas extends CI_Model {
	public function getMaxID()
	{
		$str = "SELECT max(id_berkas) maxID FROM berkas ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "401";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_berkas) maxID FROM log_berkas ";
		$str .= "WHERE id_log_berkas LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."6001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function save($data)
	{
		$this->db->insert('berkas',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_berkas',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('berkas',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('berkas',$where);		
	}
}
