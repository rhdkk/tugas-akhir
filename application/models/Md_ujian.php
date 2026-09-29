<?php
class Md_ujian extends CI_Model {
	public function getMaxID($th)
	{
		$str = "SELECT max(id_uji) maxID FROM ujian ";
		$str .= "WHERE id_uji LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."40001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getMaxDetID($th)
	{
		$str = "SELECT max(id_det_uji) maxID FROM det_ujian ";
		$str .= "WHERE id_det_uji LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."00001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_uji) maxID FROM log_ujian ";
		$str .= "WHERE id_log_uji LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."70001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getMaxLogDetID($th)
	{
		$str = "SELECT max(id_log_det_uji) maxID FROM log_det_ujian ";
		$str .= "WHERE id_log_det_uji LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."90001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	
	public function getKriteriaNilai($id)
	{
		$str = "SELECT k.*, u.id_uji, u.filename_uji, m.nim, m.nm_mhs, tu.nm_tuji, tu.nilai_p1, tu.nilai_p2, ";
		$str .= "p.jenjang, p.nm_ps, p.p1, p.p2, tu.n_p1, tu.n_p2, d.id_dospem, (tu.n_p1+tu.n_p2) n_tim, ";
		$str .= "(SELECT COUNT(*) FROM dospem dp WHERE dp.nim=m.nim AND dp.stat_dospem=3 AND dp.stat=1) jml3, ";
		$str .= "(SELECT COUNT(*) FROM dospem dp WHERE dp.nim=m.nim AND dp.stat_dospem=4 AND dp.stat=1) jml4, ";
		$str .= "(SELECT AVG(du1.nilai) FROM det_ujian du1 JOIN dospem dp1 ON du1.id_dosen = dp1.id_dosen ";
		$str .= "WHERE dp1.nim = m.nim AND dp1.jab_dosen = 1 AND du1.id_uji = u.id_uji) AS rata_pemb, ";		
		$str .= "(SELECT AVG(du1.nilai) FROM det_ujian du1 JOIN dospem dp1 ON du1.id_dosen = dp1.id_dosen ";
		$str .= "WHERE dp1.nim = m.nim AND dp1.jab_dosen = 2 AND du1.id_uji = u.id_uji) AS rata_peng ";		
		$str .= "FROM mahasiswa m ";
		$str .= "JOIN dospem d ON d.nim = m.nim ";
		$str .= "LEFT JOIN ajuan a ON a.nim=m.nim ";
		$str .= "LEFT JOIN ujian u ON u.id_ajuan=a.id_ajuan ";
		$str .= "LEFT JOIN det_ujian du ON du.id_uji=u.id_uji ";
		$str .= "LEFT JOIN prodi p ON m.id_ps=p.id_ps ";
		$str .= "LEFT JOIN tahap_ujian tu ON m.id_tuji=tu.id_tuji ";
		$str .= "LEFT JOIN kriteria_nilai k ON m.id_tuji=k.id_tuji ";
		$str .= "JOIN dosen ds ON ds.id_dosen=du.id_dosen AND d.id_dosen=ds.id_dosen ";
		$str .= "WHERE m.nim IN (SELECT a1.nim FROM ajuan a1, ujian u1, det_ujian du1 WHERE a.id_ajuan=u.id_ajuan AND u.id_uji=du.id_uji AND du.id_det_uji=".$id.") ";
		$str .= "GROUP BY k.id_kriteria, m.nim, u.id_uji, u.filename_uji, m.nm_mhs, tu.nm_tuji, tu.nilai_p1, tu.nilai_p2, p.jenjang, p.nm_ps, p.p1, p.p2, tu.n_p1, ";
		$str .= "tu.n_p2, d.id_dospem, tu.id_tuji, k.id_tuji, p.id_ps ";
		$str .= "ORDER BY k.nm_kriteria";
		$query = $this->db->query($str);		
		return $query->result_array();
	}
	
	public function getNilaiUjian($id)
	{
		$str = "SELECT (COALESCE(AVG(CASE WHEN dp.jab_dosen = '1' THEN du.nilai END), 0) * (tu.nilai_p1 / 100) + ";
		$str .= "COALESCE(AVG(CASE WHEN dp.jab_dosen = '2' THEN du.nilai END), 0) * (tu.nilai_p2 / 100)) nilaiUji, du.id_uji ";
		$str .= "FROM det_ujian du ";
		$str .= "JOIN ujian u ON du.id_uji = u.id_uji ";
		$str .= "JOIN ajuan a ON u.id_ajuan = a.id_ajuan ";
		$str .= "JOIN tahap_ujian tu ON a.id_tuji = tu.id_tuji ";
		$str .= "JOIN dospem dp ON dp.id_dosen = du.id_dosen AND dp.nim = a.nim ";
		$str .= "WHERE du.id_uji = (SELECT id_uji FROM det_ujian WHERE id_det_uji='".$id."') ";
		$str .= "GROUP BY du.id_uji, tu.nilai_p1, tu.nilai_p2";
		$query = $this->db->query($str);		
		return $query->row();
	}

	public function getDetUji($id)
	{
		$str = "SELECT u.tgl_uji, u.jam1_uji, u.jam2_uji, u.nilai_uji, m.nm_mhs, m.nim, ps.nm_ps, ps.jenjang, m.judul_ta, du.revisi_uji, d.no_dosen, d.nm_dosen, d.gelar1, d.gelar2, tu.nm_tuji, tu.urut_tuji, j.nm_jur, r.nm_ruang, ";
		$str .= "(SELECT COUNT(u3.id_uji) FROM ajuan a3 INNER JOIN ujian u3 ON a3.id_ajuan = u3.id_ajuan WHERE a3.nim = m.nim AND a3.id_tuji = tu.id_tuji) AS jml_uji, dp.jab_dosen, dp.urut_dosen ";
		$str .= "FROM ujian u ";
		$str .= "INNER JOIN ajuan a ON u.id_ajuan = a.id_ajuan ";
		$str .= "INNER JOIN mahasiswa m ON a.nim = m.nim ";
		$str .= "INNER JOIN prodi ps ON m.id_ps = ps.id_ps ";
		$str .= "INNER JOIN jurusan j ON ps.id_jur = j.id_jur ";
		$str .= "INNER JOIN det_ujian du ON u.id_uji = du.id_uji ";
		$str .= "INNER JOIN dosen d ON du.id_dosen = d.id_dosen ";
		$str .= "INNER JOIN tahap_ujian tu ON a.id_tuji = tu.id_tuji ";
		$str .= "INNER JOIN ruang r ON u.id_ruang = r.id_ruang ";
		$str .= "LEFT JOIN dospem dp ON dp.nim = m.nim AND dp.id_dosen = du.id_dosen ";
		$str .= "WHERE u.id_uji = '".$id."' AND u.nilai_uji IS NOT NULL ";
		$str .= "ORDER BY dp.jab_dosen, dp.urut_dosen";
		
		$query = $this->db->query($str);		
		return $query->result_array();
	}
	
	public function getDetNilai($id)
	{
		$str = "SELECT u.tgl_uji, u.jam1_uji, u.jam2_uji, u.nilai_uji, m.nm_mhs, m.nim, ps.nm_ps, ps.jenjang, m.judul_ta, d.no_dosen, d.nm_dosen, d.gelar1, d.gelar2, ";
		$str .= "tu.urut_tuji, tu.nm_tuji, j.nm_jur, r.nm_ruang, du.revisi_uji, ";
		$str .= "(SELECT COUNT(u3.id_uji) FROM ajuan a3 INNER JOIN ujian u3 ON a3.id_ajuan = u3.id_ajuan WHERE a3.nim = m.nim AND a3.id_tuji = tu.id_tuji) AS jml_uji, ";
    $str .= "dp.jab_dosen, dp.urut_dosen, tu.nilai_p1, tu.nilai_p2, tu.n_p1, tu.n_p2, k.id_kriteria, k.nm_kriteria, k.pers_nilai, nu.nilai ";
		$str .= "FROM ujian u ";
		$str .= "INNER JOIN ajuan a ON u.id_ajuan = a.id_ajuan ";
		$str .= "INNER JOIN ruang r ON u.id_ruang = r.id_ruang ";
		$str .= "INNER JOIN mahasiswa m ON a.nim = m.nim ";
		$str .= "INNER JOIN prodi ps ON m.id_ps = ps.id_ps ";
		$str .= "INNER JOIN jurusan j ON ps.id_jur = j.id_jur ";
		$str .= "INNER JOIN det_ujian du ON u.id_uji = du.id_uji ";
		$str .= "INNER JOIN dosen d ON du.id_dosen = d.id_dosen ";
		$str .= "INNER JOIN tahap_ujian tu ON a.id_tuji = tu.id_tuji ";
		$str .= "LEFT JOIN dospem dp ON dp.nim = m.nim AND dp.id_dosen = du.id_dosen ";
		$str .= "INNER JOIN nilai_ujian nu ON du.id_det_uji = nu.id_det_uji ";
		$str .= "INNER JOIN kriteria_nilai k ON nu.id_kriteria = k.id_kriteria ";
		$str .= "WHERE u.id_uji = '".$id."' AND u.nilai_uji IS NOT NULL ";
		$str .= "ORDER BY dp.jab_dosen, dp.urut_dosen, k.id_kriteria";
		
		$query = $this->db->query($str);		
		return $query->result_array();
	}
	
	public function getHistNilai($id)
	{
		$str = "SELECT tu2.urut_tuji, tu2.nm_tuji, u2.nilai_uji, tu2.pers_tuji, tu2.urut_tuji ";
		$str .= "FROM mahasiswa m ";
		$str .= "INNER JOIN prodi ps ON m.id_ps = ps.id_ps ";
		$str .= "INNER JOIN jurusan j ON ps.id_jur = j.id_jur ";
		$str .= "INNER JOIN dospem dp ON dp.nim = m.nim AND dp.jab_dosen=1 AND dp.urut_dosen=1 AND dp.stat=1 ";
		$str .= "INNER JOIN dosen d ON d.id_dosen = dp.id_dosen ";
		$str .= "INNER JOIN ajuan a ON a.nim = m.nim ";
		$str .= "INNER JOIN ujian u ON u.id_ajuan = a.id_ajuan ";
		$str .= "INNER JOIN tahap_ujian tu ON a.id_tuji = tu.id_tuji ";
		$str .= "INNER JOIN ajuan a2 ON a2.nim = m.nim ";
		$str .= "INNER JOIN tahap_ujian tu2 ON a2.id_tuji = tu2.id_tuji AND tu2.jns_tuji=1 ";
		$str .= "LEFT JOIN ujian u2 ON u2.id_ajuan = a2.id_ajuan ";
		$str .= "WHERE u.id_uji = '".$id."' AND u.nilai_uji IS NOT NULL AND tu2.urut_tuji<=tu.urut_tuji ";
		$str .= "ORDER BY tu2.urut_tuji";
		
		$query = $this->db->query($str);		
		return $query->result_array();
	}
	
	public function save($data)
	{
		$this->db->insert('ujian',$data);
		return $this->db->insert_id();
	}
	
	public function saveDet($data)
	{
		$this->db->insert('det_ujian',$data);
		return $this->db->insert_id();
	}
	
	public function saveLog($data)
	{
		$this->db->insert('log_ujian',$data);
		return $this->db->insert_id();
	}
	
	public function saveLogDet($data)
	{
		$this->db->insert('log_det_ujian',$data);
		return $this->db->insert_id();
	}
	
	public function update($data,$where)
	{
		$this->db->update('ujian',$data,$where);
		return $this->db->affected_rows();
	}
	
	public function updateDet($data,$where)
	{
		$this->db->update('det_ujian',$data,$where);
		return $this->db->affected_rows();
	}
	
	public function delete($where)
	{
		$this->db->delete('ujian',$where);		
	}
}
