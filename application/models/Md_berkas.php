<?php
class Md_berma extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_berma) maxID FROM berkas ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "401";
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
		$this->db->insert('log_jenis',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('jenis',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('jenis',$where);		
	}
}
