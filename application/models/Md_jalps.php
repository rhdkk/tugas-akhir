<?php
class Md_jalps extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT jp.id_ps, ps.jenjang, ps.nm_ps, jp.kode kd1, jp1.kode kd2, jp2.kode kd3, jp3.kode kd4 ";
		$str.= "FROM jalps jp, jalps jp1, jalps jp2, jalps jp3, prodi ps ";
		$str.= "WHERE jp.id_jalur=31 AND jp1.id_jalur=32 AND jp2.id_jalur=33 AND jp3.id_jalur=34 AND jp.id_ps=jp1.id_ps AND jp.id_ps=jp2.id_ps AND jp.id_ps=jp3.id_ps AND ps.id_ps=jp.id_ps ";
		if (!empty($id)) { $str.="AND jp.id_ps=".$id; }
		$str.= " ORDER BY jp.id_ps ";		
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();		
		else return $data->result_array();	
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_jalps) maxID FROM log_jalps WHERE id_log_jalps LIKE '".$th."%'";		
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function save($data)
	{
		$this->db->insert('jalps',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_jalps',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('jalps',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('jalps',$where);		
	}
}