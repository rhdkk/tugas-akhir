<?php
class Md_kriteria extends CI_Model {
	public function getData($tu=null,$id=null)
	{
		$str = "SELECT k.*, t.id_tuji, t.nm_tuji FROM kriteria_nilai k ";
		$str .= "LEFT JOIN tahap_ujian t ON t.id_tuji = k.id_tuji ";
		if (!empty($tu)) $str.="WHERE t.id_tuji=".$tu." ";				
		if (!empty($id)) $str.="WHERE k.id_kriteria=".$id." ";				
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();	
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_kriteria) maxID FROM kriteria_nilai ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "4001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_kriteria) maxID FROM log_kriteria_nilai ";
		$str .= "WHERE id_log_kriteria LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."6001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxUrut($id)
	{
		$str = "SELECT max(urut_kriteria) maxUrut FROM kriteria_nilai ";
		$str .= "WHERE stat=1 AND id_tuji=".$id;
		$query = $this->db->query($str);
		if (empty($query->row()->maxUrut)) $max = 1;
		else $max = (int)$query->row()->maxUrut + 1;
		return $max;
	}
	public function save($data)
	{
		$this->db->insert('kriteria_nilai',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_kriteria_nilai',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('kriteria_nilai',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('kriteria_nilai',$where);		
	}
}
?>