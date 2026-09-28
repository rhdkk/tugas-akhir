<?php
class Md_proses extends CI_Model {
	public function getIDProses($idJ, $urut)
	{
		$str = "SELECT id_proses FROM proses WHERE id_jenis=".$idJ." AND urut=".$urut;
		$query = $this->db->query($str);
		return $query->row()->id_proses;
	}
	public function getSyarat($idx)
	{
		$tmp = explode('-',$idx);		
		$str = "SELECT * FROM syarat s, tipe t, jenis j, proses p ";
		$str .= "WHERE j.id_jenis=p.id_jenis AND p.id_proses=s.id_proses AND s.id_tipe=t.id_tipe AND j.id_jenis=".$tmp[0];
		if ($tmp[1]>0) $str .= " AND p.urut=".$tmp[1];
		$str .= " ORDER BY s.urut";
		$data = $this->db->query($str);
		return $data->result_array();		
	}
	public function save($data)
	{
		$this->db->insert('proses',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('proses',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('proses',$where);		
	}
}
