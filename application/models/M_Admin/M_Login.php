<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Login extends CI_Model
{

	public function transaksi($id_pelanggan)
	{

		$this->db->where('id_pelanggan', $id_pelanggan);
		return $this->db->get('transaksi')->result();
	}

	public function get_pelanggan_with_transaksi($id_pelanggan)
	{
		$this->db->select('*');
		$this->db->from('pelanggan');
		$this->db->where('pelanggan.id_pelanggan', $id_pelanggan);
		$this->db->join('transaksi', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	public function proses_login_admin($username)
	{ //ada

		$query = $this->db->get_where('petugas', array('username' => $username));

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}
}
