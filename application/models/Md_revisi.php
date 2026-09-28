<?php
class Md_revisi extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_rev) maxID FROM revisi ";
		$str .= "WHERE id_rev LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."60001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_rev) maxID FROM log_revisi ";
		$str .= "WHERE id_log_rev LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."300001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getRevUji($nim)
	{
		$str =" SELECT * FROM revisi r, det_ujian du, ujian u, ajuan a, tahap_ujian tu, dosen d ";
		$str .= "WHERE r.id_det_uji=du.id_det_uji AND du.id_uji=u.id_uji AND u.id_ajuan=a.id_ajuan AND ";
		$str .= "du.id_dosen=d.id_dosen AND a.id_tuji=tu.id_tuji AND a.nim='".$nim."' ";
		$str .= "ORDER BY r.id_rev DESC ";
		$data = $this->db->query($str);	
		return $data->result_array();
	}
	
	public function getRevisi($id,$tipeR)
	{
		if ($tipeR==2)
		{
			$str = "SELECT * FROM revisi r, det_ujian du, ujian u, ajuan a, tahap_ujian tu, dospem dp, mahasiswa m, prodi p ";
			$str .= "WHERE dp.id_dosen=du.id_dosen AND u.id_uji=du.id_uji AND a.id_ajuan=u.id_ajuan AND a.id_tuji=tu.id_tuji AND ";
			$str .= "m.nim=a.nim AND r.id_det_uji=du.id_det_uji AND p.id_ps=tu.id_ps AND dp.id_dospem=".$id." ";
			$str .= "ORDER BY r.tgl_rev DESC ";
			$data = $this->db->query($str);	
		}
		else if ($tipeR==1)
		{
			$str = "SELECT p.jenjang, p.nm_ps, dp.id_dospem, dp.nim, dp.id_dosen, dp.jab_dosen, dp.urut_dosen, m.nm_mhs, du.id_det_uji, du.revisi_uji, du.nilai, u.id_uji, u.tgl_uji, ";
			$str .= "tu.nm_tuji, r.tgl_rev, r.acc_rev, d.nm_dosen, d.gelar1, d.gelar2 ";
			$str .= "FROM dospem dp ";
			$str .= "INNER JOIN mahasiswa m ON dp.nim = m.nim ";
			$str .= "INNER JOIN prodi p ON m.id_ps = p.id_ps ";
			$str .= "INNER JOIN ajuan a ON m.nim = a.nim ";
			$str .= "INNER JOIN ujian u ON a.id_ajuan = u.id_ajuan ";
			$str .= "INNER JOIN det_ujian du ON u.id_uji = du.id_uji ";
			$str .= "INNER JOIN dosen d ON d.id_dosen = du.id_dosen ";
			$str .= "INNER JOIN revisi r ON r.id_det_uji = du.id_det_uji ";
			$str .= "INNER JOIN tahap_ujian tu ON a.id_tuji = tu.id_tuji ";
			$str .= "WHERE dp.nim = (SELECT nim FROM dospem WHERE id_dospem = '".$id."') ";
			$str .= "AND dp.id_dosen = du.id_dosen AND du.revisi_uji IS NOT NULL AND du.revisi_uji != ''" ;
			$str .= "ORDER BY u.id_uji DESC, r.tgl_rev DESC, dp.jab_dosen, dp.urut_dosen";			
			$data = $this->db->query($str);	
		}
		return $data->result_array();
	}
	
	public function getAllRev($id)
	{
		$str = "SELECT r.id_rev, r.id_det_uji, du.id_uji FROM revisi r ";
		$str .= "INNER JOIN det_ujian du ON r.id_det_uji = du.id_det_uji ";
		$str .= "INNER JOIN ujian u ON du.id_uji = u.id_uji ";
		$str .= "WHERE du.id_uji = (SELECT id_uji FROM det_ujian WHERE id_det_uji = '".$id."') AND r.acc_rev = 1";
		$data = $this->db->query($str);	
			
		return $data->result_array();
	}
	
	public function save($data)
	{
		$this->db->insert('revisi',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_revisi',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('revisi',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('revisi',$where);		
	}
}
