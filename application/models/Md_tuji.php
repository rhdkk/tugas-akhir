<?php
class Md_tuji extends CI_Model {
	public function getData($id=null,$ps=null)
	{
		$str = "SELECT t.*, p.id_ps, p.jenjang, p.nm_ps, p.p1, p.p2 FROM prodi p ";
		$str .= "LEFT JOIN tahap_ujian t ON p.id_ps = t.id_ps ";
		if (!empty($id)) $str.="WHERE t.id_tuji=".$id." ";		
		if (!empty($ps)) $str.="WHERE p.id_ps=".$ps." ";		
		$data = $this->db->query($str);
		if (empty($id)) return $data->result_array();		
		else return $data->row();	
	}
	public function getMaxID()
	{
		$str = "SELECT max(id_tuji) maxID FROM tahap_ujian ";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = "2001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_tuji) maxID FROM log_tahap_ujian ";
		$str .= "WHERE id_log_tuji LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."4001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getNext($idP,$urut)
	{
		$str = "SELECT id_tuji FROM tahap_ujian ";
		$str .= "WHERE id_ps=".$idP." AND urut_tuji>".$urut." AND stat=1 ";
		$str .= "ORDER BY urut_tuji LIMIT 1";
		$data = $this->db->query($str);
		return $data->row()->id_tuji;		
	}
	public function getSyarat($id,$nim)
	{
		$str = "SELECT b.nm_berkas, s.id_syarat, s.jns_syarat, b.id_berkas, b.naskah, bm.id_berma, ";
		$str .= "CASE WHEN bm.filename IS NOT NULL THEN bm.filename ELSE 'x' END AS filename ";
		$str .= "FROM syarat_ujian s ";
		$str .= "INNER JOIN tahap_ujian tu ON s.id_tuji=tu.id_tuji ";
		$str .= "INNER JOIN berkas b ON s.id_berkas=b.id_berkas ";
		$str .= "LEFT JOIN berma bm ON bm.id_berkas=s.id_berkas AND bm.nim='".$nim."' WHERE tu.id_tuji=".$id." ";
		$str .= "ORDER BY s.jns_syarat, b.naskah, b.nm_berkas ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}	
	public function getFirst($prodi)
	{
		$str = "SELECT id_tuji FROM tahap_ujian WHERE stat=1 AND id_ps=".$prodi." ORDER BY urut_tuji ASC LIMIT 1";
		$query = $this->db->query($str);
		return $query->row()->id_tuji;
	}
	public function getMaxUrut($ps)
	{
		$str = "SELECT max(urut_tuji) maxUrut FROM tahap_ujian ";
		$str .= "WHERE stat=1 AND id_ps=".$ps;
		$query = $this->db->query($str);
		if (empty($query->row()->maxUrut)) $max = 1;
		else $max = (int)$query->row()->maxUrut + 1;
		return $max;
	}
	
	public function save($data)
	{
		$this->db->insert('tahap_Ujian',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_tahap_ujian',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('tahap_ujian',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('tahap_ujian',$where);		
	}
}
