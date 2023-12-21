<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class M_Rules extends CI_Model
{

	public function reset_auto_increment_asosiasi()
	{
		return $this->db->query("ALTER TABLE rules_asosiasi AUTO_INCREMENT = 1");
	}

	public function reset_auto_increment_komplain()
	{
		return $this->db->query("ALTER TABLE rules_komplain AUTO_INCREMENT = 1");
	}


	//asosiasi
	public function cek_idRules_asosiasi($id_rules_asosiasi)
	{
		$this->db->select('id_rules_asosiasi')->from('rules_asosiasi')->where('id_rules_asosiasi', $id_rules_asosiasi);
		$query = $this->db->get();
		$result = $query->row();

		return $result;
	}

	public function get_rules_asosiasi_by_id($id_rules_asosiasi)
	{
		$this->reset_auto_increment_komplain();

		$this->db->where('id_rules_asosiasi', $id_rules_asosiasi);
		return $this->db->get('rules_asosiasi')->result();
	}

	public function get_asosiasi()
	{
		$this->reset_auto_increment_asosiasi();

		return $this->db->get('rules_asosiasi')->result();
	}

	public function add_rules_asosiasi()
	{
		$this->reset_auto_increment_asosiasi();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		// Tetapkan nilai 1 ke $id_petugas
		$id_petugas = 1;
		$aturan = $dataraw['aturan'];

		return $this->db->query("INSERT INTO rules_asosiasi (id_petugas, aturan) VALUES ('$id_petugas', '$aturan')");
	}

	public function delete_rules_asosiasi($id_rules_asosiasi)
	{
		$this->reset_auto_increment_asosiasi();

		return $this->db->query("DELETE FROM rules_asosiasi WHERE id_rules_asosiasi = '$id_rules_asosiasi'");
	}

	public function update_rules_asosiasi($id_rules_asosiasi)
	{
		$this->reset_auto_increment_asosiasi();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		$aturan = $dataraw['aturan'];

		return $this->db->query("UPDATE rules_asosiasi SET aturan = '$aturan' WHERE id_rules_asosiasi = '$id_rules_asosiasi'");
	}

	//komplain
	public function cek_idRules_komplain($id_rules_komplain)
	{
		$this->db->select('id_rules_komplain')->from('rules_komplain')->where('id_rules_komplain', $id_rules_komplain);
		$query = $this->db->get();
		$result = $query->row();

		return $result;
	}

	public function get_rules_komplain_by_id($id_rules_komplain)
	{
		$this->reset_auto_increment_komplain();

		$this->db->where('id_rules_komplain', $id_rules_komplain);
		return $this->db->get('rules_komplain')->result();
	}


	public function get_komplain()
	{
		$this->reset_auto_increment_komplain();

		return $this->db->get('rules_komplain')->result();
	}

	public function add_rules_komplain()
	{
		$this->reset_auto_increment_komplain();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		// Tetapkan nilai 1 ke $id_petugas
		$id_petugas = 1;
		$aturan = $dataraw['aturan'];

		return $this->db->query("INSERT INTO rules_komplain (id_petugas, aturan) VALUES ('$id_petugas', '$aturan')");
	}

	public function delete_rules_komplain($id_rules_komplain)
	{
		$this->reset_auto_increment_komplain();

		$this->db->where('id_rules_komplain', $id_rules_komplain);
		$this->db->delete('rules_komplain');

		return $this->db->affected_rows() > 0;
	}

	public function update_rules_komplain($id_rules_komplain)
	{
		$this->reset_auto_increment_komplain();

		$raw = $this->input->raw_input_stream;
		$dataraw = json_decode($raw, true);

		$aturan = $dataraw['aturan'];

		return $this->db->query("UPDATE rules_komplain SET aturan = '$aturan' WHERE id_rules_komplain = '$id_rules_komplain'");
	}
}
