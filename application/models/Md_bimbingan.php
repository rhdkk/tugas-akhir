<?php
class Md_bimbingan extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_bimb) maxID FROM bimbingan ";
		$str .= "WHERE id_bimb LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."50001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_bimb) maxID FROM log_bimbingan ";
		$str .= "WHERE id_log_bimb LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."000001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getBimbingan($id)
	{
		$str = "SELECT * FROM bimbingan b, dospem d, mahasiswa m, prodi p, tahap_ujian tu ";
		$str .= "WHERE b.id_dospem=d.id_dospem AND d.nim=m.nim AND m.id_ps=p.id_ps AND b.stat=1 AND m.id_tuji=tu.id_tuji AND b.id_dospem=".$id." ";
		$str .= "ORDER BY b.id_bimb DESC ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}	
	
	public function save($data)
	{
		$this->db->insert('bimbingan',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_bimbingan',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('bimbingan',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('bimbingan',$where);		
	}
}
?>