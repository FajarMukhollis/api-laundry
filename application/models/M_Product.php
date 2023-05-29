<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Product extends CI_Model {

	//get all product
	public function get_product(){
		return $this->db->get('produk')->result();
	}

	//add new product
	public function add_product(){
		
		$nama_produk = $_POST['nama_produk'];
		$jenis_service = $_POST['jenis_service'];
		$harga_produk = $_POST['harga_produk'];

		return $this->db->query("INSERT INTO produk (nama_produk, jenis_service ,harga_produk) VALUES ('$nama_produk', '$jenis_service', '$harga_produk')");

	}

	//delete product
	public function delete_product($id_produk){
		return $this->db->query("DELETE FROM produk WHERE id_produk = '$id_produk'");
	}

	//update product
	public function update_product($id_produk){
		$nama_produk = $_POST['nama_produk'];
		$jenis_service = $_POST['jenis_service'];
		$harga_produk = $_POST['harga_produk'];

		return $this->db->query("UPDATE produk SET nama_produk = '$nama_produk', jenis_service = '$jenis_service', harga_produk = '$harga_produk' WHERE id_produk = '$id_produk'");
	}
	

}
