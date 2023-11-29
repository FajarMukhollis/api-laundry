<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Product extends CI_Model
{

	public function reset_auto_increment()
	{
		return $this->db->query("ALTER TABLE produk AUTO_INCREMENT = 1");
	}

	//get all product
	public function get_product()
	{

		$this->reset_auto_increment();

		return $this->db->get('produk')->result();
	}

	//add new product
	public function add_product()
	{

		$this->reset_auto_increment();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		$nama_produk = $dataraw['nama_produk'];
		$kategori = $dataraw['kategori'];
		$jenis_service = $dataraw['jenis_service'];
		$durasi = $dataraw['durasi'];
		$harga_produk = $dataraw['harga_produk'];
		$satuan = $dataraw['satuan'];

		return $this->db->query("INSERT INTO produk (nama_produk, kategori, jenis_service, durasi, harga_produk, satuan ) VALUES ('$nama_produk', '$kategori','$jenis_service', '$durasi', '$harga_produk', '$satuan')");
	}

	//delete product
	public function delete_product($id_produk)
	{
		$this->reset_auto_increment();

		$this->db->where('id_produk', $id_produk);
		$this->db->delete('produk');

		return $this->db->affected_rows() > 0;
	}


	//get product by id
	public function get_produk_by_id($id_produk)
	{
		$this->reset_auto_increment();

		$this->db->where('id_produk', $id_produk);
		return $this->db->get('produk')->result();
	}

	//update product
	public function update_product($id_produk, $kategori, $nama_produk, $jenis_service, $durasi, $harga_produk, $satuan)
	{
		$query = $this->db->query(
			"UPDATE produk SET kategori =?, nama_produk = ?, jenis_service = ?, durasi = ?, harga_produk = ?, satuan = ? WHERE id_produk = ?",
			array($kategori, $nama_produk, $jenis_service, $durasi, $harga_produk, $satuan, $id_produk)
		);
		return $query;
	}
	public function cek_idProduct($id_produk)
	{
		$this->db->select('id_produk')->from('produk')->where('id_produk', $id_produk);
		$query = $this->db->get();
		$result = $query->row();

		return $result;
	}
}
