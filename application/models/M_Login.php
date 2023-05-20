<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Login extends CI_Model {

	//Login
	public function proses_login_user($email, $password){
		
		$this->db->select('*');
		$this->db->from('pelanggan');
		$this->db->where('email', $email);
		$this->db->where('password', MD5($password));

		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}

	public function proses_login_admin($username, $password){
		
		$this->db->select('*');
		$this->db->from('petugas');
		$this->db->where('username', $username);
		$this->db->where('password', MD5($password));

		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}


	public function transaksi($id_pelanggan){

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
}
