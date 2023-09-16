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

		if ($data == null) {
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		} else {
			$this->response([
				'status' => true,
				'message' => 'Data ditemukan',
				'data' => $data
			], RestController::HTTP_OK);
		}
	}

	//add
	public function product_post()
	{

		$data = array(
			'kategori' => $this->post('kategori'),
			'nama_produk' => $this->post('nama_produk'),
			'jenis_service' => $this->post('jenis_service'),
			'durasi' => $this->post('durasi'),
			'harga_produk' => $this->post('harga_produk'),
			'satuan' => $this->post('satuan')
		);

		$proses = $this->M_Product->add_product($data);

		if ($proses) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil ditambahkan'
			], RestController::HTTP_CREATED);

			$this->response($data, RestController::HTTP_OK);
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
		$kategori = $this->put('kategori');
		$nama_produk = $this->put('nama_produk');
		$jenis_service = $this->put('jenis_service');
		$durasi = $this->put('durasi');
		$harga_produk = $this->put('harga_produk');
		$satuan = $this->put('satuan');

		$check_id = $this->M_Product->cek_idProduct($id_produk);

		if ($check_id) {

			$check_data = $this->M_Product->update_product($id_produk, $kategori, $nama_produk, $jenis_service, $durasi, $harga_produk, $satuan);
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
}
