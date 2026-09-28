<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Md_nilai extends CI_Model {
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_nilai) maxID FROM log_nilai_ujian ";
		$str .= "WHERE id_log_nilai LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."200001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function saveLog($data)
	{
		$this->db->insert('log_nilai_ujian',$data);
		return $this->db->insert_id();
	}
	public function save($data)
	{
		$this->db->insert('nilai_ujian',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('nilai_ujian',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('nilai_ujian',$where);		
	}
}