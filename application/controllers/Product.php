<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Product extends RestController {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_Product');
	}

	public function product_get(){ 
		$data = $this->M_Product->get_product();

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

	//add
	public function product_post(){
		$data = array(
			'nama_produk' => $this->post('nama_produk'),
			'jenis_service' => $this->post('jenis_service'),
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

		$check_data = $this->M_Product->delete_product($id_produk);
		if($check_data){
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

	public function product_put(){
		$id_produk = $this->put('id_produk');
		$data = array(
			'nama_produk' => $this->put('nama_produk'),
			'jenis_service' => $this->put('jenis_service'),
			'harga_produk' => $this->put('harga_produk')
		);

		$check_data = $this->M_Product->update_product($id_produk, $data);
		if($check_data){
			$this->db->where('id_produk', $id_produk);
			$this->db->update('produk', $data);
			
			$this->response([
				'status' => true,
				'message' => 'Data berhasil diupdate'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}

}
