<?php
class Md_sesi extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM sesi ";
		$str .= "WHERE stat=1 ";
		if (!empty($id)) $str.="AND id_dosen='".$id."' ";
		$str .= "ORDER BY nm_sesi";		
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();
	}
	public function save($data)
	{
		$this->db->insert('sesi',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('sesi',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('sesi',$where);		
	}
}
