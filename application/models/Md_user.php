<?php
class Md_user extends CI_Model {
	public function getData($id=null)
	{
		$str = "SELECT u.id_user, u.username, u.nm_user, u.hak, p.id_ps, p.jenjang, p.nm_ps, u.pass FROM user u ";
		$str.= "LEFT JOIN akses a ON a.id_user=u.id_user AND a.stat=1 ";
		$str.= "LEFT JOIN prodi p ON p.id_ps=a.id_ps ";		
		if (!empty($id)) $str.="WHERE u.id_user=".$id." AND u.stat=1 ";
		else $str .= "WHERE u.stat=1 ";
		$str .= "ORDER BY id_user, id_ps ";
		$data = $this->db->query($str);
		if (!empty($id)) return $data->row();	
		else return $data->result_array();				
	}
	public function getDet($username)
	{
		$str = "SELECT u.id_user, u.username, u.nm_user, u.hak, p.id_ps, p.jenjang, p.nm_ps, u.pass FROM user u ";
		$str.= "LEFT JOIN prodi p ON p.id_ps=u.id_ps ";		
		$str.="WHERE u.username='".$username."' AND u.stat=1 ";
		$str .= "ORDER BY id_user, id_ps ";
		$data = $this->db->query($str);
		return $data->row();			
	}
	public function getDetAdmin($id)
	{
		$str = "SELECT u.id_user, u.username, u.nm_user, u.hak, p.id_ps, p.jenjang, p.nm_ps, j.id_jur, j.nm_jur, u.pass FROM user u ";
		$str.= "LEFT JOIN prodi p ON p.id_ps=u.id_ps ";
		$str.= "LEFT JOIN jurusan j ON u.id_jur=j.id_jur ";		
		$str.="WHERE u.username='".$id."' AND u.stat=1 ";		
		$data = $this->db->query($str);
		return $data->row();			
	}
	
	public function getUserMhs()
	{
		$str = "SELECT u.id_user, u.username, u.nm_user, m.nim, m.nm_mhs, p.jenjang, p.nm_ps, m.jalur FROM user u ";
		$str.= "LEFT JOIN mahasiswa m ON m.id_user=u.id_user AND m.stat=1 ";
		$str.= "LEFT JOIN prodi p ON p.id_ps=m.id_ps ";		
		$str .= "WHERE u.stat=1 AND u.hak=6 ";
		$str .= "ORDER BY nim ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getUserDosen()
	{
		$str = "SELECT u.id_user, u.username, u.nm_user, d.no_dosen, d.nm_dosen, ";
		$str.= "CASE WHEN u.id_ps IS NOT NULL THEN p.jenjang ELSE '' END AS jenjang, ";
		$str.= "CASE WHEN u.id_ps IS NOT NULL THEN p.nm_ps ELSE '' END AS nm_ps, ";		
		$str.= "CASE WHEN u.id_ps IS NOT NULL THEN j_prodi.nm_jur WHEN u.id_jur IS NOT NULL THEN j_jur.nm_jur ELSE '' END AS nm_jur ";		
		$str.= "FROM user u ";		
		$str .= "LEFT JOIN dosen d ON d.id_user = u.id_user AND d.stat = 1 ";
		$str .= "LEFT JOIN prodi p ON u.id_ps = p.id_ps ";
		$str .= "LEFT JOIN jurusan j_prodi ON p.id_jur = j_prodi.id_jur ";
		$str .= "LEFT JOIN jurusan j_jur ON u.id_jur = j_jur.id_jur ";
		$str .= "WHERE u.hak IN (4, 5, 7) AND u.stat=1 ";
		$str .= "ORDER BY u.id_user ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getUserAdmin()
	{
		$str = "SELECT u.id_user, u.username, u.nm_user, ";
		$str.= "CASE WHEN u.id_ps IS NOT NULL THEN p.jenjang ELSE '' END AS jenjang, ";
		$str.= "CASE WHEN u.id_ps IS NOT NULL THEN p.nm_ps ELSE '' END AS nm_ps, ";		
		$str.= "CASE WHEN u.id_ps IS NOT NULL THEN j_prodi.nm_jur WHEN u.id_jur IS NOT NULL THEN j_jur.nm_jur ELSE '' END AS nm_jur ";		
		$str.= "FROM user u ";		
		$str .= "LEFT JOIN prodi p ON u.id_ps = p.id_ps ";
		$str .= "LEFT JOIN jurusan j_prodi ON p.id_jur = j_prodi.id_jur ";
		$str .= "LEFT JOIN jurusan j_jur ON u.id_jur = j_jur.id_jur ";
		$str .= "WHERE u.hak IN (1, 2, 3) AND u.stat=1 ";
		$str .= "ORDER BY u.id_user ";
		$data = $this->db->query($str);
		return $data->result_array();				
	}
	public function getMaxID($th)
	{
		$str = "SELECT max(id_user) maxID FROM user ";
		$str .= "WHERE id_user LIKE '".$th."%'";
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."10001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogID($th)
	{
		$str = "SELECT max(id_log_user) maxID FROM log_user WHERE id_log_user LIKE '".$th."%'";		
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."5001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	public function getMaxLogAksesID($th)
	{
		$str = "SELECT max(id_log_akses) maxID FROM log_akses WHERE id_log_akses LIKE '".$th."%'";		
		$query = $this->db->query($str);
		if (empty($query->row()->maxID)) $max = $th."6001";
		else $max = (int)$query->row()->maxID + 1;
		return $max;
	}
	function getAkses($id)
	{
		$tmp = explode("-",$id);
		$str = "SELECT u.nm_user, p.nm_ps FROM user u, prodi p, akses a";
		$str .= " WHERE a.id_user=".$tmp[0]." AND a.id_ps=".$tmp[1]." AND u.id_user=a.id_user AND p.id_ps=a.id_ps";
		$query = $this->db->query($str);
		return $query->row();	
	}
	function cekUser($username, $password)
	{
		$str = "SELECT * FROM user WHERE username='".$username."' AND stat=1 LIMIT 1";
		$query = $this->db->query($str);		
		if ($query->num_rows() == 1) {
			$user = $query->row_array();			
			if (password_verify($password, $user['pass'])) {
				unset($user['pass']); 
				return $user;
			}
		}
    
    return false;
	}
	function cekUserExist($username)
	{
		$str = "SELECT * FROM user WHERE username='".$username."' AND stat=1 LIMIT 1";
		$query = $this->db->query($str);		
		if ($query->num_rows()==1) return true;
		else return false;
	}
	public function save($data)
	{
		$this->db->insert('user',$data);
		return $this->db->affected_rows() > 0;
	}
	public function update($data,$where)
	{
		$this->db->update('user',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('user',$where);		
	}
	public function saveAkses($data)
	{
		$this->db->insert('akses',$data);
		return $this->db->insert_id();
	}
	public function updateAkses($data,$where)
	{
		$this->db->update('akses',$data,$where);
		return $this->db->affected_rows();
	}
	public function deleteAkses($where)
	{
		$this->db->delete('akses',$where);		
	}
	public function saveLog($data)
	{
		$this->db->insert('log_user',$data);
		return $this->db->insert_id();
	}
	public function saveLogAkses($data)
	{
		$this->db->insert('log_akses',$data);
		return $this->db->insert_id();
	}	
}
