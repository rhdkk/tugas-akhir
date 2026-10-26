<?php
class Md_suji extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT s.*, t.id_tuji, t.nm_tuji FROM syarat_ujian s ";
		$str .= "LEFT JOIN tahap_ujian t ON t.id_tuji = s.id_tuji ";
		if (!empty($id)) $str.="WHERE t.id_tuji=".$id." ";				
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();	
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_syarat) maxID FROM tahap_ujian ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "2001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_tuji) maxID FROM log_tahap_ujian ";
		$str .= "WHERE id_log_tuji LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."4001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function save($data)
	{
		$this->db->insert('syarat_ujian',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_syarat_ujian',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('syarat_ujian',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('syarat_ujian',$where);		
	}
}
?>