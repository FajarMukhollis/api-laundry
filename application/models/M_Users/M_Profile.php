<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Profile extends CI_Model
{
	public function get_pelanggan_by_id($id_pelanggan)
	{
		$this->db->where('id_pelanggan', $id_pelanggan);
		return $this->db->get('pelanggan')->row();
	}

	public function change_password($id_pelanggan, $new_password_hash)
	{
		$this->db->where('id_pelanggan', $id_pelanggan);
		$this->db->update('pelanggan', array('password' => $new_password_hash));
	}
}
