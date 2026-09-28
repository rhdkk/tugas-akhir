<?php
class Md_dospem extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM doskel dk, kelas k, dosen d, matkul mk, prodi p, jalps jp ";
		$str.= "WHERE dk.id_dosen=d.id_dosen ";
		$str.= "AND mk.id_mk=k.id_mk ";
		$str.= "AND mk.id_ps=p.id_ps ";
		$str.= "AND k.id_jalur=jp.id_jalur ";
		$str.= "AND jp.id_ps=p.id_ps ";
		$str.= "AND k.id_kls=dk.id_kls AND dk.stat=1 ";
		if (!empty($id)) $str.="AND dk.id_doskel=".$id;
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();		
		else return $data->result_array();		
	}
	public function getMaxID($th)
	{
		$str = "SELECT max(id_dospem) maxID FROM dospem ";
		$str .= "WHERE id_dospem LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."20001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_dospem) maxID FROM log_dospem ";
		$str .= "WHERE id_log_dospem LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."80001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function save($data)
	{
		$this->db->insert('dospem',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_dospem',$data);
		return $this->db->insert_id();
	}
	public function saveDosen($data)
	{
		$this->db->insert('log_dospem',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('dospem',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('dospem',$where);		
	}
}
?>