<?php
class Md_matkul extends CI_Model {
	public function getData($id=null,$ps=null)
	{
		$str = "SELECT * FROM matkul mk, prodi ps, jurusan jur ";
		$str.= "WHERE mk.id_ps=ps.id_ps ";
		$str.= "AND ps.id_jur = jur.id_jur AND mk.stat=1 ";
		if (!empty($id)) $str.="AND mk.id_mk=".$id." ";
		if (!empty($ps)) $str.="AND ps.id_ps=".$ps." ";
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();		
		else return $data->result_array();		
	}
	public function getDataKls($smt,$ps)
	{
		$tmp = explode("-",$ps);
		$str = "SELECT m.id_mk, m.kode_mk, m.nm_mk, m.twr, m.smt, COUNT(k.id_kls) mx, SUM(k.stat) ct FROM matkul m ";
		$str.= "LEFT JOIN kelas k ON k.id_mk=m.id_mk AND k.id_jalur=".$tmp[1]." AND k.id_smt=".$smt." ";		
		$str.= "WHERE m.id_ps=".$tmp[0]." AND m.stat=1 ";		
		$str.= "GROUP BY m.id_mk ";
		$str.= "ORDER BY m.nm_mk ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_mk) maxID FROM matkul mk";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "50001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_mk) maxID FROM log_matkul ";
		$str .= "WHERE id_log_mk LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."50001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function save($data)
	{
		$this->db->insert('matkul',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_matkul',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('matkul',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('matkul',$where);		
	}
}