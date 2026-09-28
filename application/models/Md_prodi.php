<?php
class Md_prodi extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT p.id_ps, p.id_jur, p.nm_ps, p.jenjang, p.p1, p.p2, p.tipe_reg, p.tipe_rev, p.stat, ";
		$str.= "j.nm_jur, COUNT(CASE WHEN t.stat=1 THEN t.id_ps END) AS n_uji FROM prodi p ";
		$str.= "LEFT JOIN jurusan j ON p.id_jur = j.id_jur ";
		$str.= "LEFT JOIN tahap_ujian t ON p.id_ps = t.id_ps ";
		if (!empty($id)) $str.="WHERE p.id_ps='".$id."' ";
		$str.= "GROUP BY p.id_ps, p.id_jur, p.nm_ps, p.jenjang, p.p1, p.p2, p.tipe_reg, p.tipe_rev, p.stat, j.nm_jur ORDER BY p.id_ps ";
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();      
		else return $data->row();  
	}
	public function getJurusan()
	{
		$str = "SELECT * FROM jurusan ORDER BY nm_jur";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getAkses($id)
	{
		$str = "SELECT * FROM user WHERE id_user='".$id."'";
		$query = $this->db->query($str);
		$hak = $query->row()->hak;
		if ($hak==1) $strPS = "SELECT * FROM prodi ORDER BY id_ps";			
		else if ($hak==2 or $hak==4 or $hak==6) $strPS = "SELECT * FROM prodi WHERE id_ps=".$query->row()->id_ps;
		else if ($hak==3 or $hak==7) $strPS = "SELECT * FROM prodi WHERE id_jur=".$query->row()->id_jur;
		$data = $this->db->query($strPS);
		return $data->result_array();		
	}
	public function getJalur($id=null)
	{
		$str = "SELECT DISTINCT j.id_jalur, j.nm_jalur FROM jalur j, jalps jp, prodi p WHERE p.id_ps=jp.id_ps AND j.id_jalur=jp.id_jalur AND jp.stat=1 ";
		if (!empty($id)) $str .= "AND  p.id_ps=".$id;
		$data = $this->db->query($str);
		return $data->result_array();		
	}
	public function cekData($id,$jur,$jen,$ps)
	{
		$str = "SELECT * FROM prodi WHERE stat=1 AND id_jur=".$jur." AND jenjang='".$jen."' AND UPPER(nm_ps)='".trim(strtoupper($ps))."'";
		if (!empty($id)) $str .= " AND id_ps<>".$id;		
		$query = $this->db->query($str);		
		if ($query->num_rows()>0) return "ada";				
		else return "x";
	}	
	public function getMaxID()
	{
		$str = "SELECT max(id_ps) maxID FROM prodi";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "201";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_ps) maxID FROM log_prodi ";
		$str .= "WHERE id_log_ps LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."501";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}	
	function getDataPS($jenjang,$prodi)
	{
		$str = "SELECT id_ps, tipe_reg FROM prodi WHERE jenjang='".$jenjang."' AND UPPER(nm_ps)=UPPER('".$prodi."') LIMIT 1";
		$query = $this->db->query($str);		
		return $query->row();
	}
	public function save($data)
	{
		$this->db->insert('prodi',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_prodi',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('prodi',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('prodi',$where);		
	}
}
