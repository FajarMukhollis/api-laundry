<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Transaksi extends CI_Model {

	public function transaksi(){

		return $this->db->get('transaksi')->result();

	}
}
