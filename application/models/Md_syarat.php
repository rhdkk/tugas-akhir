<?php
class Md_syarat extends CI_Model {
	public function getData($tu=null,$id=null)
	{
		$str = "SELECT s.*, t.id_tuji, t.nm_tuji, b.nm_berkas FROM syarat_ujian s ";
		$str .= "LEFT JOIN tahap_ujian t ON t.id_tuji = s.id_tuji ";
		$str .= "LEFT JOIN berkas b ON b.id_berkas = s.id_berkas ";
		if (!empty($tu)) $str.="WHERE t.id_tuji=".$tu." ";				
		if (!empty($id)) $str.="WHERE s.id_syarat=".$id." ";				
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();	
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_syarat) maxID FROM syarat_ujian ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "3001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_syarat) maxID FROM log_syarat_ujian ";
		$str .= "WHERE id_log_syarat LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."5001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxUrut($id)
	{
		$str = "SELECT max(urut_syarat) maxUrut FROM syarat_ujian ";
		$str .= "WHERE stat=1 AND id_tuji=".$id;
		$query = $this->db->query($str);
		if (empty($query->row()->maxUrut)) $max = 1;
		else $max = (int)$query->row()->maxUrut + 1;
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