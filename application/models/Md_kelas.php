<?php 
class Md_kelas extends CI_Model { 
	public function getData($smt,$id=null,$ps=null) { 
		$str = "SELECT m.nm_mk, j.nm_jalur, m.twr, k.id_kls, k.nm_kls, k.n_mhs, jp.kode, dk.id_doskel, dk.posisi, d.nm_dosen, d.jabfung, d.pend_akhir, p.id_plot, p.hari, s.nm_sesi, r.nm_ruang, s.jam1, s.jam2, ps.jenjang FROM kelas k "; 
		$str .= "INNER JOIN matkul m ON m.id_mk = k.id_mk "; 
		if (!empty($ps)) $str.=" AND m.id_ps=".$ps." "; 
		$str .= "INNER JOIN prodi ps ON m.id_ps = ps.id_ps "; 
		$str .= "INNER JOIN jalps jp ON jp.id_ps = ps.id_ps AND k.id_jalur=jp.id_jalur "; 
		$str .= "INNER JOIN jalur j ON k.id_jalur = j.id_jalur "; 
		$str .= "LEFT JOIN doskel dk ON k.id_kls = dk.id_kls AND dk.stat=1 "; 
		$str .= "LEFT JOIN dosen d ON d.id_dosen = dk.id_dosen "; 
		$str .= "LEFT JOIN plot p ON k.id_kls = p.id_kls AND p.stat=1 "; 
		$str .= "LEFT JOIN sesi s ON s.id_sesi = p.id_sesi "; 
		$str .= "LEFT JOIN ruang r ON p.id_ruang = r.id_ruang "; 
		$str .= "WHERE k.stat=1 "; 
		if (!empty($smt)) $str.=" AND k.id_smt=".$smt; 
		if (!empty($id)) $str.=" AND k.id_kls=".$id; 
		$str .= " ORDER BY k.id_kls, dk.posisi, m.nm_mk, k.nm_kls, p.hari, s.nm_sesi"; 
		$data = $this->db->query($str); 
		if (empty($id)) return $data->result_array(); 
		else return $data->row(); 
	} 
	public function repDataPDF($smt) 
	{ 
		$str = "SELECT dk.id_kls, d.no_dosen, d.nm_dosen nama1, d.gelar1 glr11, d.gelar2 glr12, d2.nm_dosen nama2, d2.gelar1 glr21, d2.gelar2 glr22, dk2.posisi, m.nm_mk, m.sks, jp.kode, k.nm_kls, j.nm_jalur, ps.jenjang, ps.nm_ps, p.hari, s.nm_sesi, r.nm_ruang FROM dosen d "; 
		$str .= "INNER JOIN doskel dk ON dk.id_dosen=d.id_dosen AND dk.stat=1 "; 
		$str .= "INNER JOIN kelas k ON dk.id_kls=k.id_kls AND k.stat=1 AND id_smt=".$smt." "; 
		$str .= "INNER JOIN matkul m ON k.id_mk=m.id_mk "; 
		$str .= "INNER JOIN prodi ps ON ps.id_ps=m.id_ps "; 
		$str .= "INNER JOIN jalps jp ON jp.id_ps = ps.id_ps AND k.id_jalur = jp.id_jalur ";
		$str .= "INNER JOIN jalur j ON j.id_jalur = jp.id_jalur "; 
		$str .= "LEFT JOIN plot p ON k.id_kls=p.id_kls AND p.stat=1 "; 		
		$str .= "LEFT JOIN sesi s ON s.id_sesi=p.id_sesi "; 
		$str .= "LEFT JOIN ruang r ON p.id_ruang = r.id_ruang "; 
		$str .= "LEFT JOIN doskel dk2 "; 
		$str .= "INNER JOIN dosen d2 ON dk2.id_dosen=d2.id_dosen ";
		$str .= "ON dk2.id_kls=k.id_kls AND dk2.stat=1 ";
		$str .= "ORDER BY d.nm_dosen, d.no_dosen, ps.jenjang, ps.nm_ps, m.nm_mk, jp.kode, k.nm_kls, p.hari, s.jam1, dk2.posisi";
		$data = $this->db->query($str);
		return $data->result_array(); 
	}
	public function repDataXLS($smt,$jur)
	{
		$str = "SELECT ps.jenjang, ps.nm_ps, p.hari, k.nm_kls, jp.kode, m.kode_mk, m.thkur, m.nm_mk, s.nm_sesi, s.jam1, s.jam2, r.kd_ruang, r.nm_ruang, r.kd_ged, g.nm_ged, d.no_dosen, d.nm_dosen, d.gelar1, d.gelar2, d.nidn, d.jabfung, d.pend_akhir, d.asal_dosen, ps2.nm_ps nm_hb, ps2.jenjang jn_hb FROM kelas k ";
		$str .= "INNER JOIN matkul m ON m.id_mk = k.id_mk ";
		$str .= "INNER JOIN prodi ps ON m.id_ps = ps.id_ps ";
		if ($jur!='x') { $str .= "AND ps.id_jur = ".$jur." "; }
		$str .= "INNER JOIN jalps jp ON jp.id_ps = ps.id_ps AND k.id_jalur=jp.id_jalur ";
		$str .= "INNER JOIN jalur j ON k.id_jalur = j.id_jalur ";		
		$str .= "LEFT JOIN doskel dk ON k.id_kls = dk.id_kls AND dk.stat=1 ";
		$str .= "LEFT JOIN dosen d ON d.id_dosen = dk.id_dosen ";
		$str .= "LEFT JOIN prodi ps2 ON d.id_ps = ps2.id_ps ";
		$str .= "LEFT JOIN plot p ON k.id_kls = p.id_kls AND p.stat=1 ";
		$str .= "LEFT JOIN sesi s ON s.id_sesi = p.id_sesi ";
		$str .= "LEFT JOIN ruang r ON p.id_ruang = r.id_ruang ";
		$str .= "LEFT JOIN gedung g ON r.kd_ged = g.kd_ged ";
		$str .= "WHERE k.stat=1 AND k.id_smt=".$smt;
		$str .= " ORDER BY k.id_kls, dk.posisi, m.nm_mk, k.nm_kls, p.hari, s.nm_sesi";
		$data = $this->db->query($str);
		return $data->result_array();
	}
	public function getDataPS($kelas)
	{
		$str = "SELECT ps.id_ps, ps.nm_ps, ps.plot FROM kelas k, matkul mk, prodi ps ";
		$str .= "WHERE k.id_mk=mk.id_mk AND mk.id_ps=ps.id_ps AND k.id_kls=".$kelas;
		$query = $this->db->query($str);
		return $query->row();				
	}
	public function getMaxID($smt)
	{
		$str = "SELECT max(id_kls) maxID FROM kelas ";
		$str .= "WHERE id_smt=".$smt;
		$query = $this->db->query($str);
		if (empty($query->row()->maxID))
		$max = $smt."10001";
		else $max = (int)$query->row()->maxID + 1;
		return $max; 
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_kls) maxID FROM log_kelas ";
		$str .= "WHERE id_log_kls LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."100001";
		else $max = (int)$query->row()->maxID + 1;
		return $max; 
	}
	public function getJadwal($kls)
	{
		$str = "SELECT k.id_kls, k.nm_kls, m.nm_mk, k.n_mhs, p.id_plot, p.id_sesi, p.id_ruang, p.hari FROM kelas k ";
		$str .= "INNER JOIN matkul m ON m.id_mk = k.id_mk ";
		$str .= "LEFT JOIN plot p ON p.id_kls = k.id_kls AND p.stat=1 ";
		$str .= "WHERE k.id_kls=".$kls;
		$str .= " ORDER BY hari, id_sesi";
		$query = $this->db->query($str);
		return $query->result_array(); 
	}
	public function getDosen($kls)
	{
		$str = "SELECT k.id_kls, k.nm_kls, m.nm_mk, dk.id_doskel, dk.id_dosen FROM kelas k ";
		$str .= "INNER JOIN matkul m ON m.id_mk = k.id_mk ";
		$str .= "LEFT JOIN doskel dk ON dk.id_kls = k.id_kls AND dk.stat=1 ";
		$str .= "WHERE k.id_kls=".$kls;
		$str .= " ORDER BY posisi";
		$query = $this->db->query($str);
		return $query->result_array();
	}
	public function getDosenKls($kls)
	{
		$str = "SELECT k.id_kls, dk.id_doskel, dk.posisi, dk.id_dosen, ps.jenjang ";
		$str .= "FROM kelas k, doskel dk, prodi ps, matkul m ";
		$str .= "WHERE dk.id_kls=k.id_kls AND k.id_mk=m.id_mk AND m.id_ps=ps.id_ps AND dk.stat=1 AND k.id_kls=".$kls;
		$str .= " ORDER BY dk.posisi, dk.id_dosen";
		$query = $this->db->query($str);
		return $query->result_array();
	}
	public function getJadwalKls($kls)
	{
		$str = "SELECT k.id_kls, p.id_plot, p.hari, p.id_sesi, p.id_ruang, ps.jenjang ";
		$str .= "FROM kelas k, plot p, matkul mk, prodi ps ";
		$str .= "WHERE p.id_kls=k.id_kls AND k.id_mk=mk.id_mk AND ps.id_ps=mk.id_ps AND p.stat=1 AND k.id_kls=".$kls;
		$query = $this->db->query($str);
		return $query->result_array();
	}
	public function save($data)
	{
		$this->db->insert('kelas',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_kelas',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('kelas',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('kelas',$where);
	}
} 