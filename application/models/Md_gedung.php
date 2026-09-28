<?php
class Md_gedung extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM gedung ";
		if (!empty($id)) $str.="WHERE kd_ged='".$id."' ";
		$str .= "ORDER BY nm_ged";		
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_ged) maxID FROM log_gedung ";
		$str .= "WHERE id_log_ged LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."3001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function cekID($id,$kd)
	{
		$str = "SELECT * FROM gedung WHERE stat=1 AND kd_ged='".$kd."'";
		if (!empty($id)) $str .= " AND kd_ged<>'".$id."'";		
		$query = $this->db->query($str);		
		if ($query->num_rows()>0) return "ada";				
		else return "x";
	}	
	public function save($data)
	{
		$this->db->insert('gedung',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_gedung',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('gedung',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('gedung',$where);		
	}
}
