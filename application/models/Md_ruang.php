<?php
class Md_ruang extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM ruang r, gedung g ";
		$str.= "WHERE r.stat=1 AND r.kd_ged=g.kd_ged ";
		if (!empty($id)) $str.="AND r.id_ruang=".$id." ";
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();		
		else return $data->result_array();		
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_ruang) maxID FROM ruang ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "1001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_ruang) maxID FROM log_ruang ";
		$str .= "WHERE id_log_ruang LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."1001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function cekData($id,$field,$val)
	{
		$str = "SELECT * FROM ruang WHERE stat=1 AND ".$field."='".$val."'";
		if (!empty($id)) $str .= " AND id_ruang<>".$id;		
		$query = $this->db->query($str);		
		if ($query->num_rows()>0) return "ada";				
		else return "x";
	}	
	public function save($data)
	{
		$this->db->insert('ruang',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_ruang',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('ruang',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('ruang',$where);		
	}
}

//coba-edit