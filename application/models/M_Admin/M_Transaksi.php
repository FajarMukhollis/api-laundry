<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Transaksi extends CI_Model
{

	public function transaksi()
	{
		return $this->db->get('transaksi')->result();
	}

	public function get_transaksi() //ada
	{

		$this->db->select('transaksi.*, pelanggan.nama_pelanggan, produk.nama_produk');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		$this->db->order_by('transaksi.id_transaksi', 'desc');
		$query = $this->db->get();

		// Mengembalikan hasil query
		return $query->result();
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
		$this->db->select('transaksi.*, pelanggan.nama_pelanggan, pelanggan.no_telp, produk.nama_produk');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		$this->db->where('transaksi.id_transaksi', $id_transaksi);
		return $this->db->get()->row();
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
}
