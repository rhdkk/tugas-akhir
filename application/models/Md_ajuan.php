<?php
class Md_ajuan extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_ajuan) maxID FROM ajuan ";
		$str .= "WHERE id_ajuan LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."30001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_ajuan) maxID FROM log_ajuan ";
		$str .= "WHERE id_log_ajuan LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."10001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogDetID($th)
	{
		$str = "SELECT max(id_log_det_ajuan) maxID FROM log_det_ajuan ";
		$str .= "WHERE id_log_det_ajuan LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."40001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getData($idU,$hak,$dept)
	{
		$str = "SELECT a.id_ajuan, a.step, u.nim, u.nm_user, j.nm_jenis, j.id_jenis, MIN(pg.dt) dt, ps.hak, t.form, t.nm_tahap, u.dept, ps.id_proses ";
		$str .= "FROM user u, ajuan a, jenis j, progress pg, tahap t, proses ps, akses ak ";
		$str .= "WHERE a.id_mhs = u.id_user AND a.id_jenis = j.id_jenis AND pg.id_ajuan = a.id_ajuan AND a.id_jenis = ps.id_jenis AND a.step = ps.urut ";
		$str .= "AND ps.id_tahap = t.id_tahap ";
		if ($hak==2) $str.="AND a.id_mhs=".$idU;		
		else if ($hak==3) $str.="AND u.dept=".$dept." AND ak.id_user=".$idU." AND ps.hak=".$hak." AND ak.id_proses=ps.id_proses AND a.hasil=1 ";	
		else if ($hak==4) $str.="AND ak.id_user=".$idU." AND ak.id_proses=ps.id_proses AND a.hasil=1 ";
		else if ($hak==6) $str.="AND ak.id_user=".$idU." AND ps.hak=".$hak." AND ak.id_proses=ps.id_proses AND a.hasil=1 ";
		$str .= " GROUP BY a.id_mhs, j.id_jenis";
		
		$data = $this->db->query($str);
		return $data->result_array();
	}	
	public function getUjian($idU)
	{
		$str = "SELECT u.nim, u.nm_user, j.nm_jenis, u.prodi, a.dt_ujian ";
		$str .= "FROM ajuan a, user u, ";
		$str .= "WHERE a.id_mhs = u.id_user AND a.id_jenis = j.id_jenis AND pg.id_ajuan = a.id_ajuan AND a.id_jenis = ps.id_jenis AND a.step = ps.urut ";
		$str .= "AND m.id_dosen=".$idU;
		if ($hak==2) $str.="AND a.id_mhs=".$idU;		
		else if ($hak==3) $str.="AND u.dept=".$dept." AND ak.id_user=".$idU." AND ps.hak=".$hak." AND ak.id_proses=ps.id_proses AND a.hasil=1 ";	
		else if ($hak==4) $str.="AND ak.id_user=".$idU." AND ak.id_proses=ps.id_proses AND a.hasil=1 ";
		else if ($hak==6) $str.="AND ak.id_user=".$idU." AND ps.hak=".$hak." AND ak.id_proses=ps.id_proses AND a.hasil=1 ";
		$str .= " GROUP BY a.id_mhs, j.id_jenis";
		
		$data = $this->db->query($str);
		return $data->result_array();
	}	
	public function viewData($id)
	{
		$str = "SELECT a.id_ajuan, a.judul, a.dt_ujian, u.nim, u.nm_user, j.nm_jenis ";
		$str .= "FROM user u, ajuan a, jenis j ";
		$str .= "WHERE a.id_mhs = u.id_user AND a.id_jenis = j.id_jenis AND a.id_ajuan=".$id;
		$data = $this->db->query($str);
		return $data->row();	
	}	
	public function getBerkas($idA,$idJ,$step)
	{
		$str = "SELECT nm_berkas FROM berkas b, ajuan a, proses p, progress pg, berkas_progress bp ";
		$str .= "WHERE a.id_jenis=p.id_jenis AND b.id_berkas=bp.id_berkas AND p.id_proses=pg.id_proses AND pg.id_progress=bp.id_progress ";
		$str .= "AND a.id_ajuan=".$idA." AND p.id_jenis=".$idJ." AND p.urut=".$step;
		$data = $this->db->query($str);
		return $data->result_array();
	}	
	public function save($data)
	{
		$this->db->insert('ajuan',$data);
		return $this->db->insert_id();
	}
	public function saveDet($data)
	{
		$this->db->insert('det_ajuan',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_ajuan',$data);
		return $this->db->insert_id();
	}
	public function saveLogDet($data)
	{
		$this->db->insert('log_det_ajuan',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('ajuan',$data,$where);
		return $this->db->affected_rows();
	}
	public function updateDet($data,$where)
	{
		$this->db->update('det_ajuan',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('ajuan',$where);		
	}
}
