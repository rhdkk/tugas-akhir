<?php
class Md_mahasiswa extends CI_Model {
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_mahasiswa) maxID FROM log_mahasiswa ";
		$str .= "WHERE id_log_mahasiswa LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."60001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getEndUji($nim)
	{
		$str = "SELECT tu.id_tuji, tu.urut_tuji FROM mahasiswa m, prodi p, tahap_ujian tu ";
		$str .= "WHERE m.id_ps=p.id_ps AND tu.id_ps=p.id_ps AND m.nim='".$nim."' ";
		$str .= "ORDER BY tu.urut_tuji DESC LIMIT 1";
		$data = $this->db->query($str);
		return $data->row()->id_tuji;	
	}
	public function getDetUji($nim)
	{
		$str = "SELECT * FROM mahasiswa m, dospem dp, ajuan a, tahap_ujian tu, dosen d, progress p, progression pg, prodi ps, berma bm ";
		$str .= "WHERE m.nim=dp.nim AND d.id_dosen=dp.id_dosen AND a.nim=m.nim AND a.id_tuji=m.id_tuji AND tu.id_tuji=m.id_tuji ";
		$str .= "AND m.id_progress=p.id_progress AND m.nim='".$nim."' AND p.urut_progress=8 AND bm.nim=m.nim AND pg.nim=m.nim AND ps.id_ps=m.id_ps ";
		$str .= "AND pg.id_progression IN (SELECT MAX(pg0.id_progression) FROM progression pg0 WHERE pg0.nim=m.nim) ";
		$str .= "AND bm.id_berma IN (SELECT MAX(bm0.id_berma) FROM berma bm0, berkas b WHERE bm0.nim=m.nim AND bm0.id_berkas=b.id_berkas AND b.naskah=1) ";
		$str.= "ORDER BY dp.jab_dosen, dp.urut_dosen";
		$data = $this->db->query($str);
		return $data->result_array();	
	}
	public function getMhs($ps=null,$jur=null)
	{
		$str = "SELECT * FROM mahasiswa m, prodi p, progress pg, progression ps ";
		$str .= "WHERE m.id_progress=pg.id_progress AND m.id_ps=p.id_ps AND pg.stage<9 ";
		if (!empty($ps)) $str .= "AND p.id_ps=".$ps." ";		
		if (!empty($jur)) $str .= "AND p.id_ps=".$jur." ";		
		$str .= "AND ps.id_progression IN (SELECT MAX(ps0.id_progression) FROM progression ps0 WHERE ps0.nim=m.nim) ";
		$str .= "ORDER BY ps.dt_progression";
		$data = $this->db->query($str);
		return $data->result_array();		
	}
	public function getUji($ps=null,$jur=null)
	{
		$str = "SELECT * FROM mahasiswa m, prodi p, progress pg, progression ps, tahap_ujian t ";
		$str .= "WHERE m.id_progress=pg.id_progress AND m.id_ps=p.id_ps AND pg.urut_progress=8 AND ps.nim=m.nim AND t.id_tuji=m.id_tuji ";
		if (!empty($ps)) $str .= "AND p.id_ps=".$ps." ";
		if (!empty($jur)) $str .= "AND p.id_ps=".$jur." ";		
		$str .= "AND ps.id_progression IN (SELECT MAX(ps0.id_progression) FROM progression ps0 WHERE ps0.nim=m.nim) ";
		$str .= "ORDER BY ps.dt_progression";
		$data = $this->db->query($str);
		return $data->result_array();		
	}
	public function getNextUji($id)
	{
		$str = "SELECT tu2.id_tuji FROM tahap_ujian tu1 ";
		$str .= "JOIN tahap_ujian tu2 ON tu1.id_ps = tu2.id_ps ";
		$str .= "WHERE tu1.id_tuji=".$id." AND tu2.urut_tuji>tu1.urut_tuji ";
		$str .= "ORDER BY tu2.urut_tuji ASC LIMIT 1";
		$data = $this->db->query($str);
		return $data->row()->id_tuji;		
	}
	public function getLastUji($nim)
	{
		$str = "SELECT m.nim, a.id_ajuan, tu.nm_tuji, u.id_uji, u.tgl_uji, u.jam1_uji, u.jam2_uji, r.nm_ruang FROM mahasiswa m ";
		$str .= "JOIN ajuan a ON a.nim=m.nim ";
		$str .= "JOIN tahap_ujian tu ON a.id_tuji=tu.id_tuji ";
		$str .= "LEFT JOIN ujian u ON a.id_ajuan=u.id_ajuan ";
		$str .= "LEFT JOIN ruang r ON u.id_ruang=r.id_ruang ";
		$str .= "WHERE m.nim='".$nim."' ";
		$str .= "ORDER BY a.id_ajuan DESC LIMIT 1";
		$data = $this->db->query($str);
		return $data->row();				
	}
	public function getAllUji($nim)
	{
		$str = "SELECT COUNT(tu.id_tuji) nUji FROM mahasiswa m, tahap_ujian tu  ";
		$str .= "WHERE m.nim='".$nim."' AND m.id_ps=tu.id_ps GROUP BY tu.id_ps";
		$data = $this->db->query($str);
		return $data->row();
	}
	public function getAjuji($ps=null,$jur=null)
	{
		$str = "SELECT * FROM mahasiswa m, prodi p, progress pg, progression ps, tahap_ujian t ";
		$str .= "WHERE m.id_progress=pg.id_progress AND m.id_ps=p.id_ps AND pg.urut_progress=6 AND ps.nim=m.nim AND t.id_tuji=m.id_tuji ";
		if (!empty($ps)) $str .= "AND p.id_ps=".$ps." ";
		if (!empty($jur)) $str .= "AND p.id_ps=".$jur." ";
		$str .= "AND ps.id_progression IN (SELECT MAX(ps0.id_progression) FROM progression ps0 WHERE ps0.nim=m.nim) ";
		$str .= "ORDER BY ps.dt_progression";
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getDetAjuji($nim)
	{
		$str = "SELECT m.nm_mhs, m.nim, a.id_ajuan, da.id_syarat, da.id_berma, da.ket, b.nm_berkas, bm.filename, tu.nm_tuji, p.nm_ps, p.jenjang ";
		$str .= "FROM mahasiswa m, ajuan a, det_ajuan da, berma bm, berkas b, tahap_ujian tu, prodi p ";
		$str .= "WHERE a.nim=m.nim AND a.id_tuji=m.id_tuji AND da.id_ajuan=a.id_ajuan AND bm.id_berma=da.id_berma AND bm.id_berkas=b.id_berkas ";
		$str .= "AND m.id_ps=p.id_ps AND tu.id_tuji=m.id_tuji AND m.nim='".$nim."' ";
		$str .= "ORDER BY b.naskah, b.nm_berkas";
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getBimbingan($nim)
	{
		$str = "SELECT * FROM bimbingan b, dospem dp, dosen d ";
		$str .= "WHERE b.id_dospem=dp.id_dospem AND dp.id_dosen=d.id_dosen AND b.stat=1 AND dp.nim='".$nim."' ";
		$str .= "ORDER BY b.tgl_bimb DESC, b.id_bimb DESC ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getBerkasAjuan($nim)
	{
		$str = "SELECT * FROM ajuan a, det_ajuan da, berma bm, mahasiswa m, berkas b ";
		$str .= "WHERE a.id_ajuan=da.id_ajuan AND da.id_berma=bm.id_berma AND bm.id_berkas=b.id_berkas ";
		$str .= "AND a.nim=m.nim AND a.id_tuji=m.id_tuji AND m.nim='".$nim."' ";
		$str .= "ORDER BY b.naskah, b.nm_berkas ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getPengesahan($ps=null,$jur=null)
	{
		$str = "SELECT * FROM mahasiswa m, prodi p, progress pg, progression ps ";
		$str .= "WHERE m.id_progress=pg.id_progress AND m.id_ps=p.id_ps AND pg.urut_progress=3 AND ps.nim=m.nim ";
		if (!empty($ps)) $str .= "AND p.id_ps=".$ps." ";
		if (!empty($jur)) $str .= "AND p.id_ps=".$jur." ";
		$str .= "AND ps.id_progress IN (SELECT ps0.id_progress FROM progression ps0, progress pg0 WHERE ps0.id_progress=pg0.id_progress AND ((pg0.urut_progress=2 AND m.id_ps=p.id_ps AND p.jenjang<>'S1') OR (pg0.urut_progress=1 AND m.id_ps=p.id_ps AND p.jenjang='S1')) AND ps.nim=m.nim) ";
		$str .= "ORDER BY ps.dt_progression ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getAjuan($ps=null)
	{
		$str = "SELECT * FROM mahasiswa m, prodi p, progress pg, progression ps ";
		$str .= "WHERE m.id_progress=pg.id_progress AND m.id_ps=p.id_ps AND pg.urut_progress=2 AND ps.nim=m.nim ";
		if (!empty($ps)) $str .= "AND m.id_ps=".$ps." ";
		$str .= "ORDER BY ps.dt_progression ";
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getDetAjuan($nim,$urut)
	{
		$str = "SELECT * FROM mahasiswa m ";
		$str.= "LEFT JOIN dospem dp ON m.nim = dp.nim ";
		$str.= "LEFT JOIN dosen d ON d.id_dosen = dp.id_dosen ";
		$str.= "INNER JOIN progress p ON m.id_progress = p.id_progress ";
		$str.= "INNER JOIN prodi ps ON m.id_ps = ps.id_ps ";
		if (!empty($nim)) $str.="WHERE m.nim='".$nim."' ";
		if (!empty($urut)) $str.="AND p.urut_progress=".$urut." ";
		$str.= "ORDER BY dp.jab_dosen, dp.urut_dosen";
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getDetTA($nim)
	{
		$str = "SELECT * FROM mahasiswa m, dosen d, dospem dp ";
		$str .= "WHERE m.nim=dp.nim AND dp.id_dosen=d.id_dosen AND m.nim='".$nim."'";
		$query = $this->db->query($str);
		return $query->row();
	}	
	public function getDet($id)
	{
		$str = "SELECT * FROM mahasiswa m, prodi ps, progress pg, tahap_ujian tu WHERE m.id_user='".$id."' AND ps.id_ps=m.id_ps AND pg.id_progress=m.id_progress AND m.id_tuji=tu.id_tuji";
		$query = $this->db->query($str);
		return $query->row();
	}	
	public function getDetil($nim)
	{
		$str = "SELECT m.*, ps.*, pg.*, tu.nm_tuji, tu.urut_tuji, tu2.id_tuji AS id_tuji_akhir FROM mahasiswa m ";
		$str .= "JOIN prodi ps ON ps.id_ps = m.id_ps ";
		$str .= "JOIN progress pg ON pg.id_progress = m.id_progress ";
		$str .= "JOIN tahap_ujian tu ON tu.id_tuji = m.id_tuji ";
		$str .= "CROSS JOIN (SELECT MAX(id_tuji) AS id_tuji FROM tahap_ujian WHERE stat=1 AND jns_tuji=1) tu2 ";
		$str .= "WHERE m.nim='".$nim."'";
		$query = $this->db->query($str);
		return $query->row();
	}	
	public function getFirstProg($id)
	{
		$str = "SELECT pg.id_progress FROM mahasiswa m, prodi ps, progress pg WHERE m.id_user=".$id." AND ps.tipe_reg=pg.tipe_progress AND pg.urut_progress=1 AND pg.stat=1 AND ps.id_ps=m.id_ps AND pg.id_progress=m.id_progress";
		$query = $this->db->query($str);
		$idProg = $query->row()->id_progress;
		return $idProg;
	}
	
	public function getRev($id, $idD=null)
	{
		$str ="SELECT d.id_dosen, d.nm_dosen, d.gelar1, d.gelar2, dp.jab_dosen, dp.id_dospem, dp.jab_dosen, dp.urut_dosen, du.id_det_uji, tu.nm_tuji, u.tgl_uji, ";
		$str .= "du.revisi_uji, p.tipe_rev FROM dosen d ";
		$str .= "JOIN dospem dp ON d.id_dosen = dp.id_dosen ";
		$str .= "JOIN mahasiswa m ON dp.nim = m.nim ";
		$str .= "JOIN tahap_ujian tu ON m.id_tuji = tu.id_tuji ";
		$str .= "JOIN ajuan a ON m.nim = a.nim ";
		$str .= "JOIN prodi p ON m.id_ps = p.id_ps ";
		$str .= "JOIN ujian u ON a.id_ajuan = u.id_ajuan ";
		$str .= "JOIN det_ujian du ON u.id_uji = du.id_uji ";
		$str .= "LEFT JOIN revisi r ON du.id_det_uji = r.id_det_uji ";
		$str .= "WHERE m.nim = '".$id."' AND dp.nim = m.nim AND a.nim = m.nim AND u.id_ajuan = a.id_ajuan AND du.id_uji = u.id_uji ";
		$str .= "	AND du.id_dosen = dp.id_dosen AND dp.stat_dospem = 3 ";
		$str .= "	AND (r.id_det_uji IS NULL OR (r.tgl_rev = (SELECT MAX(r2.tgl_rev) ";
		$str .= " FROM revisi r2 WHERE r2.id_det_uji = r.id_det_uji) AND r.acc_rev = 3)) ";
		if (!empty($idD)) $str .= "AND d.id_dosen=".$idD." AND m.id_tuji=tu.id_tuji ";
		$str .= "ORDER BY dp.jab_dosen, dp.urut_dosen";
		$query = $this->db->query($str);
		return $query->result_array();
	}
	
	public function getPembBelum($id)
	{
		$str = "SELECT * FROM dospem dp, bimbingan b ";
		$str .= "WHERE dp.jab_dosen=1 AND b.id_dospem=dp.id_dospem AND b.acc_bimb=1 AND dp.stat=1 AND dp.nim='".$id."' ";
		$query = $this->db->query($str);
		return $query->num_rows();
	}
	
	public function getTimBelum($id)
	{
		$str = "SELECT * FROM dospem ";
		$str .= "WHERE (stat_dospem=3 OR (jab_dosen=2 AND stat_dospem=1)) AND stat=1 AND nim='".$id."' ";
		$query = $this->db->query($str);
		return $query->num_rows();
	}
	
	public function getTim($id)
	{
		$str = "SELECT d.*, dp.*, p.stage, b2.id_dospem id_dp, b1.last_bimb, b1.last_id, b2.acc_bimb FROM mahasiswa m ";
		$str .= "JOIN dospem dp ON m.nim = dp.nim AND dp.stat = 1 ";
		$str .= "JOIN dosen d ON dp.id_dosen = d.id_dosen ";
		$str .= "JOIN tahap_ujian tu ON m.id_tuji = tu.id_tuji ";
		$str .= "JOIN progress p ON m.id_progress = p.id_progress ";
		$str .= "LEFT JOIN (SELECT id_dospem, MAX(tgl_bimb) last_bimb, MAX(id_bimb) last_id FROM bimbingan GROUP BY id_dospem) b1 ON dp.id_dospem = b1.id_dospem ";
		$str .= "LEFT JOIN bimbingan b2 ON b2.id_dospem = b1.id_dospem AND b1.last_id = b2.id_bimb ";
		$str .= "WHERE m.nim='".$id."' ";
		$str .= "AND ((dp.jab_dosen = 1 AND dp.urut_dosen <= tu.n_p1) OR (dp.jab_dosen = 2 AND dp.urut_dosen <= tu.n_p2)) ";
		$str .= "GROUP BY dp.id_dospem, d.id_dosen, b1.last_bimb ";
		$str .= "ORDER BY dp.jab_dosen, dp.urut_dosen ";
		$query = $this->db->query($str);
		return $query->result_array();	
	}
	
	public function getHistory($id)
	{
		$str = "SELECT * FROM mahasiswa m, progress p, progression pg WHERE m.id_user='".$id."' AND pg.nim=m.nim AND pg.id_progress=p.id_progress ";
		$str .= "ORDER BY pg.dt_progression DESC";
		$query = $this->db->query($str);
		return $query->result_array();	
	}
	public function getTUjian($id)
	{
		$str = "SELECT * FROM mahasiswa m, tahap_ujian t, ajuan a, ujian u ";
		$str .= "WHERE m.id_user='".$id."' AND a.nim=m.nim AND t.id_tuji=a.id_tuji AND a.id_ajuan=u.id_ajuan AND u.nilai_uji IS NOT NULL ";
		$str .= "ORDER BY u.id_uji DESC";
		$query = $this->db->query($str);		
		return $query->result_array();	
	}
		
	public function save($data)
	{
		$this->db->insert('mahasiswa',$data);
		return $this->db->affected_rows() > 0;
	}
	public function saveLog($data)
	{
		$this->db->insert('log_mahasiswa',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('mahasiswa',$data,$where);
		return $this->db->affected_rows();
	}
	public function updateDospem($data,$where)
	{
		$this->db->update('dospem',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('mahasiswa',$where);
	}
}
?>