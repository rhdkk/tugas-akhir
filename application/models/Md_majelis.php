<?php
class Md_majelis extends CI_Model {
	public function getPenguji($id)
	{
		$str = "SELECT a.id_ajuan, a.judul, u.nm_user, m.posisi, m.urut  ";
		$str .= "FROM user u, pengajuan a, majelis m ";
		$str .= "WHERE a.id_ajuan = m.id_ajuan AND m.id_dosen = u.id_user AND a.id_ajuan=".$id;
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getMaxID($th)
	{
		$str = "SELECT max(id_majelis) maxID FROM majelis WHERE id_majelis LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "2040001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}	
	public function getTitle($id)
	{
		$str = "SELECT title FROM jenis WHERE id_jenis=".$id;
		$query = $this->db->query($str);		
		return $query->row()->title;
	}
	public function save($data)
	{
		$this->db->insert('majelis',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('majelis',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('jenis',$where);		
	}
}
