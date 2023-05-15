<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Register extends CI_Model {

	//Register
	public function cek_email($email){
		
		return $this->db->query("SELECT email FROM pelanggan WHERE email = '$email'");

	}

	public function proses_register(){
		
		$nama_pelanggan = $_POST['nama_pelanggan'];
		$notelp = $_POST['no_telp'];
		$alamat = $_POST['alamat'];
		$email = $_POST['email'];
		$password = md5($_POST['password']);

		return $this->db->query("INSERT INTO pelanggan (nama_pelanggan, no_telp, alamat, email, password) VALUES ('$nama_pelanggan', '$notelp', '$alamat', '$email', '$password')");
	}

}
