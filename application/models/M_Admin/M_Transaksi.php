<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Transaksi extends CI_Model
{

	public function transaksi()
	{
		return $this->db->get('transaksi')->result();
	}

	public function get_transaksi()
	{
		$this->db->select('transaksi.*, pelanggan.nama_pelanggan, produk.nama_produk, kategori_produk.jenis_kategori');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		$this->db->join('kategori_produk', 'kategori_produk.id_kategori = produk.id_kategori');
		// Tambahkan join lain jika diperlukan

		$this->db->order_by('transaksi.id_transaksi', 'desc');
		$query = $this->db->get();
		$results = $query->result();

		// Loop melalui hasil dan atur jenis_kategori berdasarkan id_kategori
		foreach ($results as $result) {
			$result->jenis_kategori = $this->getJenisKategoriById($result->id_kategori);
		}

		return $results;
	}

	public function delete_transaksi($id_produk)
	{
		$this->reset_auto_increment();

		$this->db->where('id_transaksi', $id_produk);
		$this->db->delete('transaksi');

		return $this->db->affected_rows() > 0;
	}

	public function update_transaksi($id_transaksi, $status_bayar, $status_barang, $tgl_selesai)
	{
		$query = $this->db->query("UPDATE transaksi SET status_bayar = ?, status_barang = ?, tgl_selesai = ? WHERE id_transaksi = ?", array($status_bayar, $status_barang, $tgl_selesai, $id_transaksi));
		return $query;
	}

	public function get_transaksi_by_id($id_transaksi)
	{

		$this->db->where('id_transaksi', $id_transaksi);
		return $this->db->get('transaksi')->result();
	}

	public function getDetailTransaksi($id_transaksi)
	{
		$this->db->select('transaksi.*, pelanggan.nama_pelanggan, pelanggan.no_telp, produk.nama_produk, kategori_produk.jenis_kategori');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		$this->db->join('kategori_produk', 'kategori_produk.id_kategori = produk.id_kategori');
		// Tambahkan join lain jika diperlukan

		$this->db->where('transaksi.id_transaksi', $id_transaksi);
		$query = $this->db->get();
		$result = $query->row();

		// Jika jenis_kategori yang sesuai tidak ditemukan, atur jenis_kategori menjadi NULL
		if ($result) {
			$result->jenis_kategori = $this->getJenisKategoriById($result->id_kategori);
		}

		return $result;
	}



	public function get_transaksi_by_one_week()
	{
		$end_date = date('Y-m-d');
		$start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));

		$this->db->select('transaksi.*, pelanggan.nama_pelanggan, produk.nama_produk');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'transaksi.id_pelanggan = pelanggan.id_pelanggan', 'inner');
		$this->db->join('produk', 'transaksi.id_produk = produk.id_produk', 'inner');
		$this->db->where('tgl_order >=', $start_date);
		$this->db->where('tgl_order <=', $end_date);
		$this->db->order_by('transaksi.id_transaksi', 'desc');
		$query = $this->db->get();

		return $query->result();
	}


	public function get_trasaksi_by_one_month()
	{
		$end_date = date('Y-m-d');
		$start_date = date('Y-m-d', strtotime('-30 days', strtotime($end_date)));

		$this->db->select('transaksi.*, pelanggan.nama_pelanggan, produk.nama_produk');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'transaksi.id_pelanggan = pelanggan.id_pelanggan', 'inner');
		$this->db->join('produk', 'transaksi.id_produk = produk.id_produk', 'inner');
		$this->db->where('tgl_order >=', $start_date);
		$this->db->where('tgl_order <=', $end_date);
		$this->db->order_by('transaksi.id_transaksi', 'desc');
		$query = $this->db->get();

		return $query->result();
	}

	public function reset_auto_increment()
	{
		return $this->db->query("ALTER TABLE transaksi AUTO_INCREMENT = 1");
	}

	private function getJenisKategoriById($id_kategori)
	{
		$this->db->select('jenis_kategori');
		$this->db->from('kategori_produk');
		$this->db->where('id_kategori', $id_kategori);
		$query = $this->db->get();
		$result = $query->row();

		return $result ? $result->jenis_kategori : NULL;
	}
}
