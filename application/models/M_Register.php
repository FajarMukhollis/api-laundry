<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Register extends CI_Model {

	//Register
	public function cek_email($email){
		
		return $this->db->query("SELECT email FROM pelanggan WHERE email = '$email'");

	}

}
