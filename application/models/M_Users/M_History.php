<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_History extends CI_Model
{

	public function rules()
	{
		return [
			// [
			//     'field' => 'id_petugas',
			//     'label' => 'ID Petugas',
			//     'rules' => 'required|numeric'
			// ],
			[
				'field' => 'id_produk',
				'label' => 'ID Produk',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'service',
				'label' => 'service',
				'rules' => 'required'
			],
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'alamat_pelanggan',
				'label' => 'alamat_pelanggan',
				'rules' => 'required'
			],
			[
				'field' => 'total_harga',
				'label' => 'Total Harga',
				'rules' => 'required'
			],
			// [
			//     'field' => 'status_bayar',
			//     'label' => 'Status Bayar',
			//     'rules' => 'required'
			// ],
			// [
			//     'field' => 'status_barang',
			//     'label' => 'Status Barang',
			//     'rules' => 'required'
			// ],
			// [
			//     'field' => 'tgl_order',
			//     'label' => 'Tanggal Order',
			//     'rules' => 'required'
			// ],
			// [
			//     'field' => 'tgl_selesai',
			//     'label' => 'Tanggal Selesai',
			//     'rules' => 'required'
			// ]
		];
	}

	public function reset_auto_increment_transaksi()
	{
		return $this->db->query("ALTER TABLE transaksi AUTO_INCREMENT = 1");
	}

	public function history_byid($id_pelanggan)
	{

		$this->db->where('id_pelanggan', $id_pelanggan);
		return $this->db->get('transaksi')->result();
	}

	public function get_all_history()
	{
		// Mengambil semua data history transaksi dari database
		$this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan');
		$this->db->from('transaksi');
		$this->db->join('pelanggan', 'pelanggan.id = transaksi.pelanggan_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function userhistory()
	{
		return $this->db->query("SELECT * FROM pelanggan LEFT JOIN transaksi ON pelanggan.id_pelanggan = transaksi.id_pelanggan;");
	}

	public function get_history($id_pelanggan)
	{
		$this->db->select('*');
		$this->db->from('pelanggan');
		$this->db->where('pelanggan.id_pelanggan', $id_pelanggan);
		$this->db->join('transaksi', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	public function tambah_transaksi($idPelanggan, $data)
	{
		$this->reset_auto_increment_transaksi();
		// Tentukan status bayar berdasarkan keberadaan bukti bayar
		if (!isset($data['bukti_bayar']) || $data['bukti_bayar'] === null) {
			$statusBayar = 'Belum Lunas';
		} else {
			$statusBayar = 'Lunas';
		}

		$statusBarang = 'Menunggu Konfirmasi';
		$order_number = $this->generate_random_order_number();

		// Tambahkan data transaksi ke dalam tabel transaksi
		$this->db->insert('transaksi', [
			'id_pelanggan' => $idPelanggan,
			'id_petugas' => 1,
			'id_kategori' => $data['id_kategori'],
			'id_produk' => $data['id_produk'],
			'no_pesanan' => $order_number,
			'service' => $data['service'],
			'berat' => $data['berat'],
			'alamat_pelanggan' => $data['alamat_pelanggan'],
			'total_harga' => $data['total_harga'],
			'status_bayar' => $statusBayar,
			'status_barang' => $statusBarang,
			'tgl_order' => date('Y-m-d'),
			'komplen' => 'Tidak Ada Komplen',
			// 'tgl_selesai' => $data['tgl_selesai'],
		]);
	}

	private function generate_random_order_number() {
		$prefix = 'PL';
		$random_number = mt_rand(0, 999999); // Ganti sesuai kebutuhan
		$order_number = $prefix . str_pad($random_number, 6, '0', STR_PAD_LEFT);
	
		return $order_number;
	}

	public function check_transaction($id_pelanggan, $id_transaksi)
	{
		$this->db->where('id_pelanggan', $id_pelanggan);
		$this->db->where('id_transaksi', $id_transaksi);
		$query = $this->db->get('transaksi');

		return $query->num_rows() > 0;
	}

	public function post_complaint($id_pelanggan, $id_transaksi, $komplen)
	{
		$this->db->set('komplen', $komplen);
		$this->db->where('id_pelanggan', $id_pelanggan);
		$this->db->where('id_transaksi', $id_transaksi);
		$this->db->update('transaksi');

		// Mengembalikan status update (berhasil/gagal)
		return $this->db->affected_rows() > 0;
	}

	public function getTransaksiByPelanggan($id_pelanggan)
	{
		$this->db->select('transaksi.*, produk.nama_produk, kategori_produk.jenis_kategori');
		$this->db->from('transaksi');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		$this->db->join('kategori_produk', 'kategori_produk.id_kategori = produk.id_kategori');
		// Tambahkan join lain jika diperlukan

		$this->db->where('transaksi.id_pelanggan', $id_pelanggan);
		$this->db->order_by('transaksi.id_transaksi', 'desc'); // Urutkan berdasarkan id_transaksi secara descending

		$query = $this->db->get();
		$results = $query->result();

		// Loop melalui hasil dan atur jenis_kategori berdasarkan id_kategori
		foreach ($results as $result) {
			$result->jenis_kategori = $this->getJenisKategoriById($result->id_kategori);
		}

		return $results;
	}

	public function detail_transaction($id_pelanggan, $id_transaksi)
	{
		$this->db->select('transaksi.*, produk.nama_produk, kategori_produk.jenis_kategori');
		$this->db->from('transaksi');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		$this->db->join('kategori_produk', 'kategori_produk.id_kategori = produk.id_kategori');
		// Add more joins if needed

		$this->db->where('transaksi.id_pelanggan', $id_pelanggan);
		$this->db->where('transaksi.id_transaksi', $id_transaksi);

		$query = $this->db->get();
		$result = $query->row();

		if ($result) {
			$result->jenis_kategori = $this->getJenisKategoriById($result->id_kategori);
		}

		return $result;
	}


	public function postConfirmPayment($id_transaksi, $bukti_bayar)
	{
		$this->db->where('id_transaksi', $id_transaksi);
		$this->db->update('transaksi', array(
			'bukti_bayar' => $bukti_bayar,
			'status_barang' => 'Menunggu Konfirmasi'
		));
		$this->db->affected_rows();
	}

	public function get_id_pelanggan($id_pelanggan)
	{

		$this->db->where('id_pelanggan', $id_pelanggan);
		return $this->db->get('pelanggan')->result();
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
