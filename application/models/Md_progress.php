<?php
class Md_progress extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM progress ";
		if (!empty($id)) $str.="WHERE id_progress=".$id." ";
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();
	}	
	
	public function getMaxID($th)
	{
		$str = "SELECT max(id_progress) maxID FROM progress ";
		$str .= "WHERE id_progress LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."50001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}	
	
	public function getFirst($tipe)
	{
		$str = "SELECT id_progress FROM progress WHERE stat=1 AND tipe_progress=".$tipe." ORDER BY urut_progress ASC LIMIT 1";
		$query = $this->db->query($str);
		return $query->row()->id_progress;
	}
	
	public function save($data)
	{
		$this->db->insert('progress',$data);
		return $this->db->insert_id();
	}
	
	public function saveBerkas($data)
	{
		$this->db->insert('berkas_progress',$data);
		return $this->db->insert_id();
	}	
}
