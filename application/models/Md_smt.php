<?php
class Md_smt extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM semester ";
		$str .= "ORDER BY th_ajar DESC, nm_smt DESC";
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();	
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_smt) maxID FROM semester";
		$query = $this->db->query($str);
		$subTh = substr($query->row()->maxID,0,2);
		$subSem = substr($query->row()->maxID,-1);
		if ($subSem=='1' or $subSem=='2') $max = (int)$query->row()->maxID + 1;
		else
		{
			$th = (int)$subTh + 1;
			$max = $th."1";
		}			
		return $max;
	}
	public function getAktif()
	{
		$str = "SELECT id_smt FROM semester WHERE aktif=1";
		$data = $this->db->query($str);
		return $data->row();	
	}
	public function getMaxSmt()
	{
		$str = "SELECT MAX(id_smt) max_smt FROM semester";
		$data = $this->db->query($str);
		return $data->row();	
	}
	public function save($data)
	{
		$this->db->insert('semester',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where=null)
	{
		$this->db->update('semester',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('semester',$where);		
	}
}
