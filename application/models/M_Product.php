<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Product extends CI_Model {

	public function reset_auto_increment(){
		return $this->db->query("ALTER TABLE produk AUTO_INCREMENT = 1");
	}

	//get all product
	public function get_product(){

		$this->reset_auto_increment();

		return $this->db->get('produk')->result();
	}

	//add new product
	public function add_product(){

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		$nama_produk = $dataraw['nama_produk'];
		$jenis_service = $dataraw['jenis_service'];
		$harga_produk = $dataraw['harga_produk'];
		$this->reset_auto_increment();

		return $this->db->query("INSERT INTO produk (nama_produk, jenis_service ,harga_produk) VALUES ('$nama_produk', '$jenis_service', '$harga_produk')");

	}

	//delete product
	public function delete_product($id_produk){
		$this->reset_auto_increment();

		$this->db->where('id_produk', $id_produk);
        $this->db->delete('produk');
		
		return $this->db->affected_rows() > 0;
	}


	//get product by id
	public function get_produk_by_id($id_produk) {

		$this->db->where('id_produk', $id_produk);
        return $this->db->get('produk')->result();

    }

	//update product
	public function update_product($id_produk, $nama_produk, $jenis_service, $harga_produk)
	{
		$query = $this->db->query("UPDATE produk SET nama_produk = ?, jenis_service = ?, harga_produk = ? WHERE id_produk = ?", array($nama_produk, $jenis_service, $harga_produk, $id_produk));
		return $query;
	}
	


}
