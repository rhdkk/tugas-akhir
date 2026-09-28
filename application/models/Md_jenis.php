<?php
class Md_jenis extends CI_Model {
	public function getData($id=null,$ps=null)
	{
		$str = "SELECT * FROM jenis WHERE ";
		if (!empty($id)) $str.="id_jenis=".$id." AND ";
		if (!empty($ps)) $str.="prodi=".$ps." AND ";		
		$str .= "stat=1";
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();		
		else return $data->result_array();		
	}
	public function getMaxUjian($ps,$mhs)
	{
		$str = "SELECT MAX(j.urut) max FROM jenis j, ajuan a WHERE j.klp=1 AND ";
		$str .= "j.prodi=".$ps." AND a.id_mhs=".$mhs." AND ";	
		$str .= "j.id_jenis=a.id_jenis AND a.hasil<3 AND j.stat=1";
		$data = $this->db->query($str);
		if (empty($data->row()->maxID)) $max = 1;
		else $max = (int)$query->row()->max + 1;
		return $max;
	}
	public function getUjian($ps,$urut)
	{
		$str = "SELECT * FROM jenis WHERE klp=1 AND prodi=".$ps." AND urut=".$urut." AND stat=1";
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getLayanan($ps=null)
	{
		$str = "SELECT * FROM jenis WHERE klp=2 AND ";
		if (!empty($ps)) $str.="prodi=".$ps." AND stat=1";
		$data = $this->db->query($str);
		return $data->result_array();		
	}		
	public function getMaxID()
	{
		$str = "SELECT max(id_jenis) maxID FROM jenis";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "1001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}	
	public function getAttr($id)
	{
		$str = "SELECT title, kons FROM jenis WHERE id_jenis=".$id;
		$query = $this->db->query($str);		
		return $query->row();
	}
	public function save($data)
	{
		$this->db->insert('jenis',$data);
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
