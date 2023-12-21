<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Product extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Admin/M_Product');
	}

	public function product_get()
	{
		$data = $this->M_Product->get_product();

		if ($data != null) {
			$this->response([
				'status' => true,
				'message' => 'Data produk ditemukan',
				'data' => $data
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function product_by_category_get($id_kategori)
	{

		if (empty($id_kategori)) {
			$this->response([
				'status'  => false,
				'message' => 'Parameter id_kategori diperlukan',
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$data = $this->M_Product->get_produk_by_idCategory($id_kategori);

		if ($data != null) {
			$this->response([
				'status' => true,
				'message' => 'Data produk ditemukan',
				'data' => $data
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}


	//add
	public function product_post()
	{

		$data = array(
			'id_kategori' => $this->post('id_kategori'),
			'nama_produk' => $this->post('nama_produk'),
			'durasi' => $this->post('durasi'),
			'harga_produk' => $this->post('harga_produk'),
			'satuan' => $this->post('satuan')
		);

		$proses = $this->M_Product->add_product($data);

		if ($proses) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil ditambahkan'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data gagal ditambahkan'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	//delete
	public function product_delete()
	{
		$id_produk = $this->delete('id_produk');
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$id_produk = $data['id_produk'];

		if ($id_produk === null) {
			$this->response([
				'status' => false,
				'message' => 'id produk tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
		} else {
			// Pengecekan apakah produk dengan ID tersebut ada dalam database
			$produk = $this->M_Product->get_produk_by_id($id_produk);
			if ($produk) {
				// Hapus produk
				$this->M_Product->delete_product($id_produk);
				$this->response([
					'status' => true,
					'message' => 'Produk berhasil dihapus'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Gagal menghapus produk'
				], RestController::HTTP_NOT_FOUND);
			}
		}
	}

	public function product_put()
	{
		$id_produk = $this->put('id_produk');
		$id_kategori = $this->put('id_kategori');
		$nama_produk = $this->put('nama_produk');
		$durasi = $this->put('durasi');
		$harga_produk = $this->put('harga_produk');
		$satuan = $this->put('satuan');

		$check_id = $this->M_Product->cek_idProduct($id_produk);

		if ($check_id) {

			$check_data = $this->M_Product->update_product($id_produk, $id_kategori, $nama_produk, $durasi, $harga_produk, $satuan);
			if ($check_data) {
				$this->response([
					'status' => true,
					'message' => 'Data berhasil diubah'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Data gagal diubah'
				], RestController::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'id produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function category_get()
	{
		$data = $this->M_Product->get_category();

		if ($data == null) {
			$this->response([
				'status' => false,
				'message' => 'Data kategori tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		} else {
			$this->response([
				'status' => true,
				'message' => 'Data kategori ditemukan',
				'data' => $data
			], RestController::HTTP_OK);
		}
	}

	//add
	public function category_post()
	{

		$data = array(
			'jenis_kategori' => $this->post('jenis_kategori')
		);

		$proses = $this->M_Product->add_category($data);

		if ($proses) {
			$this->response([
				'status' => true,
				'message' => 'Data Kategori berhasil ditambahkan'
			], RestController::HTTP_CREATED);

			$this->response($data, RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data Kategori gagal ditambahkan'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	//delete
	public function category_delete()
	{
		$id_kategori = $this->delete('id_kategori');
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$id_kategori = $data['id_kategori'];

		if ($id_kategori === null) {
			$this->response([
				'status' => false,
				'message' => 'id kategori tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		} else {
			// Pengecekan apakah produk dengan ID tersebut ada dalam database
			$produk = $this->M_Product->get_category_by_id($id_kategori);
			if ($produk) {
				// Hapus produk
				$this->M_Product->delete_category($id_kategori);
				$this->response([
					'status' => true,
					'message' => 'Kategori berhasil dihapus'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Gagal menghapus kategori'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}

	public function category_put()
	{
		$id_kategori = $this->put('id_kategori');
		$jenis_kategori = $this->put('jenis_kategori');

		$check_id = $this->M_Product->cek_idCategory($id_kategori);

		if ($check_id) {

			$check_data = $this->M_Product->update_category($id_kategori, $jenis_kategori);
			if ($check_data) {
				$this->response([
					'status' => true,
					'message' => 'Data Kategori berhasil diubah'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Data Kategori gagal diubah'
				], RestController::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'id kategori tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}
}
