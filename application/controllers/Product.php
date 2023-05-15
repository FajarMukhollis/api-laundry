<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Product extends RestController {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_Product');
	}

	public function product_get(){ 

		$data = $this->db->get('produk')->result();

		if($data == null){
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

	public function addproduct_post(){
		$data = array(
			'nama_produk' => $this->post('nama_produk'),
			'harga_produk' => $this->post('harga_produk'),
		);

		$proses = $this->M_Product->add_product($data);

		if($proses){
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

	public function deleteproduct_delete(){
		$id_produk = $this->delete('id_produk');
		$check_data = $this->db->get_where('produk', ['id_produk' => $id_produk])->row_array();

		if($check_data){
			$this->db->where('id_produk', $id_produk);
			$this->db->delete('produk');
			
			$this->response([
				'status' => true,
				'message' => 'Data berhasil dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}

	}

}
