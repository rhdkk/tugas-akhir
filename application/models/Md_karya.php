<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class md_karya extends CI_Model {
	public function getData()
	{
		$data = $this->db->query('SELECT * FROM karya_tulis kt, prodi p WHERE kt.id_prodi_karya=p.id_prodi');
		return $data->result_array();		
	}
	public function getByID($id)
	{
		$str = "SELECT * FROM karya_tulis kt, prodi p, konsentrasi k ";
		$str .= "WHERE kt.id_prodi_karya=p.id_prodi AND kt.id_konsentrasi_karya=k.id_konsentrasi AND kt.id_karya=".$id;
		$data = $this->db->query($str);
		return $data->result_array();		
	}
	public function save($data)
	{
		$this->db->insert('karya_tulis',$data);
		return $this->db->insert_id();
	}
	public function update($data,$where)
	{
		$this->db->update('karya_tulis',$data,$where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{
		$this->db->delete('karya_tulis',$where);		
	}
}
