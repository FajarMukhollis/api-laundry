<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Login extends CI_Model {

	//Login
	public function proses_login($email, $pass){

		$this->db->select('*');
		$this->db->from('pelanggan');
		$this->db->where('email', $email);
		$this->db->where('password', MD5($pass));
		$this->db->join('transaksi', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
        return $this->db->get()->result();
		
		//return $this->db->query("SELECT * FROM pelanggan WHERE email = '$email' AND password = MD5('$pass')");

	}

	public function transaksi($id_pelanggan){

		$this->db->where('id_pelanggan', $id_pelanggan);
        return $this->db->get('transaksi')->result();

	}

	public function get_pelanggan_with_transaksi()
    {
        $this->db->select('*');
        $this->db->from('pelanggan');
		//$this->db->where('pelanggan.id_pelanggan', $id_pelanggan);
        $this->db->join('transaksi', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
        $query = $this->db->get();
        return $query->result();
    }
}
