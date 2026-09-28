<?php
class Md_plot extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT * FROM plot p ";
		$str.= "LEFT JOIN ruang r ON p.id_ruang=r.id_ruang ";
		$str.= "INNER JOIN kelas k ON p.id_kls=k.id_kls ";		
		$str.= "INNER JOIN matkul mk ON k.id_mk=mk.id_mk ";
		$str.= "INNER JOIN prodi ps ON mk.id_ps=ps.id_ps ";
		$str.= "INNER JOIN sesi s ON p.id_sesi=s.id_sesi ";		
		$str.= "INNER JOIN jalps jp ON jp.id_jalur=k.id_jalur AND jp.id_ps=ps.id_ps ";		
		$str.= "WHERE p.stat=1 ";		
		if (!empty($id)) $str.="AND p.id_plot=".$id;
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();		
		else return $data->result_array();		
	}
	public function getMaxID($smt)
	{
		$str = "SELECT max(id_plot) maxID FROM plot p, kelas k ";
		$str .= "WHERE p.id_kls=k.id_kls AND k.id_smt=".$smt;
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $smt."20001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_plot) maxID FROM log_plot ";
		$str .= "WHERE id_log_plot LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."2001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getJadwalKls($kls)
	{
		$str = "SELECT * FROM plot ";
		$str .= "WHERE stat=1 AND id_kls=".$kls;
		$query = $this->db->query($str);
		return $query->num_rows();
	}
	public function cekJmlPlot($data)
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$str = "SELECT * FROM plot p, kelas k, matkul m, prodi ps ";
		$str .= "WHERE p.stat=1 AND p.id_kls=k.id_kls AND k.id_mk=m.id_mk AND m.id_ps=ps.id_ps ";
		$str .= "AND ps.id_ps = ".$data['id_ps']." ";
		$str .= "AND p.hari = ".$data['hari']." ";
		$str .= "AND p.id_sesi = ".$data['id_sesi']." ";		
		$str .= "AND k.id_smt = ".$smt." ";		
		if (!empty($data['id_plot'])) $str .= "AND id_plot <> ".$data['id_plot'];
		$query = $this->db->query($str);
		
		return $query->num_rows();
	}		
	public function cekPlot($data)
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$str = "SELECT * FROM plot p, kelas k, matkul m, prodi ps ";
		$str .= "WHERE p.stat=1 AND p.id_kls=k.id_kls AND k.id_mk=m.id_mk AND m.id_ps=ps.id_ps ";
		$str .= "AND k.id_smt = ".$smt." ";
		$str .= "AND p.id_kls <> ".$data['id_kls']." ";
		$str .= "AND p.hari = ".$data['hari']." ";
		$str .= "AND p.id_sesi = ".$data['id_sesi']." ";
		$str .= "AND p.id_ruang = ".$data['id_ruang']." ";
		if (!empty($data['id_plot'])) $str .= "AND id_plot <> ".$data['id_plot'];
		$query = $this->db->query($str);
		
		if ($query->num_rows()>0) return $query->row()->nm_mk."|".chr(64+$query->row()->nm_kls)."|".$query->row()->nm_ps;				
		else return "x";
	}	
	public function cekDosen($data)
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$str = "SELECT * FROM plot p, sesi s, kelas k, matkul m, prodi ps, doskel dk, dosen d, jalur j, jalps jp ";
		$str .= "WHERE p.stat=1 AND p.id_kls=k.id_kls AND p.id_sesi=s.id_sesi AND k.id_mk=m.id_mk AND m.id_ps=ps.id_ps AND d.id_dosen=dk.id_dosen AND dk.id_kls=k.id_kls ";
		$str .= "AND ps.id_ps=jp.id_ps AND k.id_jalur=jp.id_jalur  ";
		if ($data['jenjang']!='S1') $str .= "AND ps.jenjang = 'S1' ";
		$str .= "AND k.id_smt = ".$smt." ";
		$str .= "AND dk.stat=1 AND dk.id_dosen = ".$data['id_dosen']." ";
		$str .= "AND p.id_kls <> ".$data['id_kls']." ";
		$str .= "AND p.hari = ".$data['hari']." ";
		$str .= "AND p.id_sesi = ".$data['id_sesi']." ";
		if (!empty($data['id_doskel']))
			$str .= "AND dk.id_doskel <> ".$data['id_doskel'];
		$query = $this->db->query($str);
		
		if ($query->num_rows()>0) 
		{
			$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");	
			if ($query->row()->jenjang=="S1") $strata="Sarjana ";
			elseif ($query->row()->jenjang=="S2") $strata="Magister ";
			elseif ($query->row()->jenjang=="S3") $strata="Doktor ";
			else $strata="Profesi ";
			return $query->row()->nm_dosen."|".$query->row()->nm_mk."|".$query->row()->kode.chr(64+$query->row()->nm_kls)."|".$strata.$query->row()->nm_ps."|".$hr[$query->row()->hari]."|".$query->row()->nm_sesi." (".$query->row()->jam1." - ".$query->row()->jam2.")";
		}
		else return "x";
	}
	public function getRekapKuliah($smt)
	{
		$str = "SELECT p.hari, p.id_sesi, s.nm_sesi, s.jam1, s.jam2, COUNT(p.id_kls) kls FROM plot p, kelas k, sesi s ";
		$str .= "WHERE p.stat=1 AND p.id_sesi=s.id_sesi AND p.id_kls = k.id_kls AND k.id_smt = '".$smt."'";		
		$str .= "GROUP BY CONCAT(p.hari, p.id_sesi)";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getFreeRoom($id)
	{
		$smt = $this->session->userdata('id_smt_jadwal');
		$tmp = explode("-",$id);
		$str = "SELECT id_ruang, nm_ruang FROM ruang ";
		$str .= "WHERE id_ruang NOT IN (SELECT p.id_ruang FROM plot p, kelas k ";
		$str .= "WHERE p.id_kls=k.id_kls AND p.hari=".$tmp[0]." AND p.id_sesi=".$tmp[1]." AND k.id_smt=".$smt;
		if ($tmp[2]!="x") $str .= " AND p.id_ruang<>".$tmp[2];
		$str .= " AND p.stat=1) ORDER BY nm_ruang";
		$data = $this->db->query($str);
		return $data->result_array();		
	}
	public function save($data)
	{
		$this->db->insert('plot',$data);
		return $this->db->insert_id();
	}
	public function savePlot($data)
	{
		$this->db->insert('log_plot',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('plot',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('plot',$where);		
	}
}
