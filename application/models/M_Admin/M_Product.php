<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Product extends CI_Model
{

	public function reset_auto_increment_produk()
	{
		return $this->db->query("ALTER TABLE produk AUTO_INCREMENT = 1");
	}

	//get all product
	public function get_product()
	{

		$this->reset_auto_increment_produk();

		$this->db->select('produk.id_produk, produk.id_kategori, kategori_produk.jenis_kategori, produk.nama_produk, produk.durasi, produk.harga_produk, produk.satuan');
		$this->db->from('produk');
		$this->db->join('kategori_produk', 'kategori_produk.id_kategori = produk.id_kategori');

		$data = $this->db->get()->result();

		return $data;
	}

	public function get_produk_by_idCategory($id_kategori)
	{
		$this->db->select('produk.id_produk, produk.id_kategori, kategori_produk.jenis_kategori, produk.nama_produk, produk.durasi, produk.harga_produk, produk.satuan');
        $this->db->from('produk');
        $this->db->join('kategori_produk', 'kategori_produk.id_kategori = produk.id_kategori');
        $this->db->where('produk.id_kategori', $id_kategori);
        $query = $this->db->get();
        return $query->result_array();
	}
	

	//add new product
	public function add_product()
	{

		$this->reset_auto_increment_produk();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		$id_kategori = $dataraw['id_kategori'];
		$nama_produk = $dataraw['nama_produk'];
		$durasi = $dataraw['durasi'];
		$harga_produk = $dataraw['harga_produk'];
		$satuan = $dataraw['satuan'];

		return $this->db->query("INSERT INTO produk (id_kategori,nama_produk, durasi, harga_produk, satuan ) VALUES ('$id_kategori', '$nama_produk', '$durasi', '$harga_produk', '$satuan')");
	}

	//delete product
	public function delete_product($id_produk)
	{
		$this->reset_auto_increment_produk();

		$this->db->where('id_produk', $id_produk);
		$this->db->delete('produk');

		return $this->db->affected_rows() > 0;
	}


	//get product by id
	public function get_produk_by_id($id_produk)
	{
		$this->reset_auto_increment_produk();

		$this->db->where('id_produk', $id_produk);
		return $this->db->get('produk')->result();
	}

	//update product
	public function update_product($id_produk, $id_kategori, $nama_produk, $durasi, $harga_produk, $satuan)
	{
		$this->reset_auto_increment_produk();

		$query = $this->db->query(
			"UPDATE produk SET id_kategori =?, nama_produk = ?, durasi = ?, harga_produk = ?, satuan = ? WHERE id_produk = ?",
			array($id_kategori, $nama_produk, $durasi, $harga_produk, $satuan, $id_produk)
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

	//kategori
	public function reset_auto_increment_kategori() {
		return $this->db->query("ALTER TABLE kategori_produk AUTO_INCREMENT = 1");
	}
	
	public function cek_idCategory($id_kategori)
	{
		$this->db->select('id_kategori')->from('kategori_produk')->where('id_kategori', $id_kategori);
		$query = $this->db->get();
		$result = $query->row();

		return $result;
	}

	public function get_category()
	{

		$this->reset_auto_increment_kategori();

		return $this->db->get('kategori_produk')->result();
	}

	public function add_category()
	{

		$this->reset_auto_increment_kategori();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		$jenis_kategori = $dataraw['jenis_kategori'];

		return $this->db->query("INSERT INTO kategori_produk (jenis_kategori ) VALUES ('$jenis_kategori')");
	}

	//delete category
	public function delete_category($id_kategori)
	{
		$this->reset_auto_increment_kategori();

		$this->db->where('id_kategori', $id_kategori);
		$this->db->delete('kategori_produk');

		return $this->db->affected_rows() > 0;
	}


	//get category by id
	public function get_category_by_id($id_kategori)
	{
		$this->reset_auto_increment_kategori();

		$this->db->where('id_kategori', $id_kategori);
		return $this->db->get('kategori_produk')->result();
	}

	//update category
	public function update_category($id_kategori, $jenis_kategori)
	{
		$this->reset_auto_increment_kategori();

		$query = $this->db->query(
			"UPDATE kategori_produk SET jenis_kategori = ? WHERE id_kategori = ?",
			array($jenis_kategori, $id_kategori)
		);
		return $query;
	}
}
