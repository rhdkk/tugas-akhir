<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Md_dosen extends CI_Model {
	public function getRevisi($id)
	{
		$str = "SELECT r.id_rev, u.id_uji, u.tgl_uji, tu.nm_tuji, p.jenjang, p.nm_ps, p.tipe_rev, m.nim, m.nm_mhs, r.tgl_rev, r.acc_rev, dp.id_dospem ";
		$str .= "FROM revisi r ";
		$str .= "JOIN det_ujian du ON du.id_det_uji = r.id_det_uji ";
		$str .= "JOIN ujian u ON u.id_uji = du.id_uji ";
		$str .= "JOIN ajuan a ON a.id_ajuan = u.id_ajuan ";
		$str .= "JOIN mahasiswa m ON m.nim = a.nim ";
		$str .= "JOIN tahap_ujian tu ON tu.id_tuji = a.id_tuji ";
		$str .= "JOIN prodi p ON p.id_ps = tu.id_ps ";
		$str .= "JOIN dospem dp ON dp.nim = m.nim AND dp.stat = 1 ";
		$str .= "JOIN dosen d ON d.id_dosen = dp.id_dosen ";
		$str .= "WHERE r.acc_rev=1 AND r.stat=1 AND d.id_user='".$id."' AND (p.tipe_rev = 2 OR (p.tipe_rev = 1 AND dp.jab_dosen = 1))";
		$str .= "ORDER BY r.tgl_rev ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	
	public function getPenilaian($id)
	{
		$str = "SELECT * FROM ujian u ";
		$str .= "JOIN det_ujian du ON u.id_uji = du.id_uji ";
		$str .= "JOIN ajuan a ON u.id_ajuan = a.id_ajuan ";
		$str .= "JOIN tahap_ujian tu ON a.id_tuji = tu.id_tuji ";
		$str .= "JOIN mahasiswa m ON a.nim = m.nim ";
		$str .= "JOIN dosen d ON d.id_dosen = du.id_dosen ";
		$str .= "JOIN dospem dp ON dp.nim = m.nim AND dp.id_dosen = d.id_dosen ";
		$str .= "WHERE u.stat = 1 AND du.nilai = -1 AND d.id_user = '".$id."' ";		
		$str .= "ORDER BY a.tgl_acc";		
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	
	public function getBimbingan($id)
	{
		$str = "SELECT * FROM bimbingan b, dospem dp, dosen d, mahasiswa m ";
		$str .= "WHERE b.id_dospem=dp.id_dospem AND dp.nim=m.nim AND b.stat=1 AND b.acc_bimb=1 AND dp.id_dosen=d.id_dosen AND d.id_user='".$id."' ";
		$str .= "ORDER BY b.id_bimb ASC ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	
	public function getJmlBimbingan($id) 
	{
		$str = "SELECT COALESCE(COUNT(m.nim),0) as jml FROM dosen d ";
		$str .= "LEFT JOIN dospem dp ";
		$str .= "INNER JOIN mahasiswa m ON dp.nim=m.nim ";
		$str .= "INNER JOIN progress p ON p.id_progress=m.id_progress AND p.urut_progress>3 ";		
		$str .= "ON dp.stat=1 AND dp.jab_dosen=1 AND dp.id_dosen=d.id_dosen ";		
		$str .= "WHERE d.id_dosen=".$id." ";		
		$str .= "GROUP BY d.id_dosen";		
		$data = $this->db->query($str);
		return $data->row();
	}	
	
	public function getJmlBimbPS($id,$ps=null) 
	{
		if (!empty($ps)) 
		{
			$str = "SELECT SUM(IF(m.id_ps=".$ps.", 1, 0)) AS jml1, SUM(IF(m.id_ps<>".$ps.", 1, 0)) AS jml2 ";
			$str .= "FROM (SELECT id_dosen FROM dosen WHERE id_dosen=".$id.") AS d ";
			$str .= "LEFT JOIN dospem dp ";
			$str .= "LEFT JOIN mahasiswa m ON m.nim=dp.nim ";
			$str .= "JOIN progress p ON m.id_progress=p.id_progress AND p.urut_progress>3 ";
			$str .= "ON d.id_dosen=dp.id_dosen AND dp.stat=1 AND dp.jab_dosen=1 ";
			$str .= "GROUP BY d.id_dosen";
			$data = $this->db->query($str);			
			return $data->row();
		}
		else
		{
			$str = "SELECT ps.id_ps, ps.jenjang, ps.nm_ps, COALESCE(COUNT(DISTINCT m.nim),0) jml FROM prodi ps ";
			$str .= "LEFT JOIN mahasiswa m ";
			$str .= "INNER JOIN dospem dp ON dp.nim=m.nim AND dp.id_dosen=".$id." AND dp.stat=1 AND dp.jab_dosen=1 ";
			$str .= "INNER JOIN progress pg ON m.id_progress=pg.id_progress AND pg.urut_progress>3 ";
			$str .= "ON m.id_ps=ps.id_ps ";
			$str .= "GROUP BY ps.id_ps";
			$data = $this->db->query($str);
			return $data->result_array();			
		}		
	}	
	public function getData($id=null)
	{
		$str = "SELECT * FROM dosen d ";
		$str .= "LEFT JOIN pangkat p ON d.id_pangkat=p.id_pangkat ";
		$str .= "LEFT JOIN prodi ps ON d.id_ps=ps.id_ps ";
		$str .= "WHERE d.stat=1 ";
		if (!empty($id)) $str.="AND id_dosen='".$id."' ";
		$str .= "ORDER BY nm_dosen";		
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();
	}	
	public function getDataUser($id)
	{
		$str = "SELECT d.id_dosen, d.nm_dosen, u.id_user, u.id_jur, u.id_ps, p.jenjang, p.nm_ps FROM user u ";
		$str .= "JOIN dosen d ON u.id_user = d.id_user ";
		$str .= "LEFT JOIN prodi p ON u.id_ps = p.id_ps ";
		$str .= "WHERE u.username = '".$id."' AND u.stat=1 ";
		$data = $this->db->query($str);
		return $data->row();
	}
	public function getDet($id)
	{
		$str = "SELECT * FROM dosen WHERE no_dosen=".$id;
		$data = $this->db->query($str);
		return $data->row();
	}
	public function getDetDosen($id)
	{
		$str = "SELECT * FROM dosen WHERE id_dosen=".$id;
		$data = $this->db->query($str);
		return $data->row();
	}
	public function getNoUser()
	{
		$str = "SELECT * FROM dosen ";
		$str .= "WHERE stat=1 AND id_user=0 ";
		$str .= "ORDER BY nm_dosen";		
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_dosen) maxID FROM dosen ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "10001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_dosen) maxID FROM log_dosen ";
		$str .= "WHERE id_log_dosen LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."1001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function cekID($id,$no)
	{
		$str = "SELECT * FROM dosen WHERE stat=1 AND no_dosen=".$no;
		if (!empty($id)) $str .= " AND id_dosen<>".$id;		
		$query = $this->db->query($str);		
		if ($query->num_rows()>0) return "ada";				
		else return "x";
	}	
	public function getRekapAjar($smt, $filter=null)
	{
		if (!empty($filter)) $tmp=explode("|",$filter);		
		$str = "SELECT d.id_dosen, d.no_dosen, d.gelar1, d.gelar2, d.nm_dosen, d.jabfung, p2.id_ps, p2.jenjang jnj_dsn, p2.nm_ps pd_dsn, p1.id_ps, p1.jenjang, p1.nm_ps, SUM(m.sks) jml, ";
		$str .= "SUM(CASE WHEN p1.jenjang = 'S1' then m.sks ELSE 0 END) jml1, ";
		$str .= "SUM(CASE WHEN p1.jenjang = 'S2' then m.sks ELSE 0 END) jml2, ";
		$str .= "SUM(CASE WHEN p1.jenjang = 'S3' then m.sks ELSE 0 END) jml3, ";
		$str .= "SUM(CASE WHEN p1.jenjang = 'XP' then m.sks ELSE 0 END) jml4 ";		
		$str .= "FROM dosen d, prodi p1, prodi p2, kelas k, doskel dk, matkul m ";
		$str .= "WHERE m.id_ps = p1.id_ps AND d.id_ps = p2.id_ps AND dk.id_dosen = d.id_dosen AND k.id_kls = dk.id_kls AND m.id_mk = k.id_mk AND dk.stat=1 AND k.id_smt=".$smt." ";
		if (!empty($filter))
		{
			$str .= "AND d.id_dosen IN (SELECT d.id_dosen FROM dosen d, prodi p, kelas k, doskel dk, matkul m ";
			$str .= "WHERE d.id_ps = p.id_ps AND dk.id_dosen = d.id_dosen	AND k.id_kls = dk.id_kls AND m.id_mk = k.id_mk AND dk.stat=1 ";			
			if ($tmp[0]!="x") $str.="AND p.id_jur=".$tmp[0];		
			if ($tmp[1]!="x") $str.="AND m.id_ps=".$tmp[1];		
			$str .= " AND k.id_smt=".$smt.")" ;		
		}
		$str .= " GROUP BY d.id_dosen";		
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getAjarDosen($id=null, $filter=null)
	{
		if (!empty($filter)) $tmp=explode("|",$filter);		
		$smt = $this->session->userdata('id_smt_jadwal');
		$str = "SELECT d.id_dosen, d.nm_dosen, d.gelar1, d.gelar2, m.nm_mk, k.nm_kls, m.sks, k.id_kls, k.n_mhs, jp.kode, ps1.nm_ps nm_ps_kls, ps1.jenjang jenjang_kls, ps1.nm_ps, ps1.jenjang, p.hari, s.jam1, s.jam2, r.nm_ruang FROM dosen d ";
		$str .= "INNER JOIN doskel dk ON d.id_dosen=dk.id_dosen AND dk.stat=1 "; if (!empty($id)) $str .= " AND dk.id_dosen=".$id." ";		
		$str .= "INNER JOIN kelas k ON dk.id_kls=k.id_kls AND k.id_smt=".$smt." "; 		
		$str .= "INNER JOIN matkul m ON k.id_mk=m.id_mk "; 		
		$str .= "INNER JOIN prodi ps1 ON m.id_ps=ps1.id_ps ";
		$str .= "LEFT JOIN prodi ps2 ON d.id_ps=ps2.id_ps ";
 		if (!empty($filter))
		{
			if ($tmp[0]!="x") $str.=" AND ps.id_jur=".$tmp[0];		
			if ($tmp[1]!="x") $str.=" AND m.id_ps=".$tmp[1];		
		}
		$str .= "INNER JOIN jalps jp ON jp.id_jalur=k.id_jalur AND jp.id_ps=m.id_ps "; 		
		$str .= "LEFT JOIN plot p "; 		
		$str .= "INNER JOIN sesi s ON s.id_sesi=p.id_sesi "; 				
		$str .= "ON p.id_kls=k.id_kls AND p.stat=1 "; 		
		$str .= "LEFT JOIN ruang r ON r.id_ruang=p.id_ruang "; 		
		$str .= "ORDER BY p.hari, s.nm_sesi "; 		
		
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function save($data)
	{
		$this->db->insert('dosen',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_dosen',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('dosen',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('dosen',$where);		
	}
}
