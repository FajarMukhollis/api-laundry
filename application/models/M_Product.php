<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Product extends CI_Model {

	//add new product
	public function add_product(){
		
		$nama_produk = $_POST['nama_produk'];
		$harga_produk = $_POST['harga_produk'];

		return $this->db->query("INSERT INTO produk (nama_produk, harga_produk) VALUES ('$nama_produk', '$harga_produk')");

	}
	

}
