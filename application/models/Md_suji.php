<?php
class Md_suji extends CI_Model {
	public function save($data)
	{
		$this->db->insert('syarat_ujian',$data);
		return $this->db->insert_id();
	}
	public function saveLog($data)
	{
		$this->db->insert('log_syarat_ujian',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('syarat_ujian',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('syarat_ujian',$where);		
	}
}
?>