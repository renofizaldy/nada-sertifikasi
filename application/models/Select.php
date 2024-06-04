<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Select extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function login() {
		$query = $this->db->get_where('account', [
			'username' => trim($this->input->post('username'))
		]);
		if ($query->num_rows() == 1) {
			$row = $query->row();
			$cek = password_verify(trim($this->input->post('password')), $row->password);
			if ($cek) {
				$data = [
					'user_id'        => $row->id,
					'user_name'      => htmlspecialchars_decode($row->fullname),
					'user_username'  => htmlspecialchars_decode($row->username),
					'user_role'      => $row->role,
				];
				$this->session->set_userdata($data);
				return true;
			}
		} return false;
	}

	public function getSurat($jenis) {
		$query = $this->db->get_where('surat', ['jenis' => $jenis]);
		return $query->result_array();
	}

	public function getSuratDetail($id) {
		$query = $this->db->get_where('surat', ['id' => $id]);
		return $query->result_array();
	}

} ?>
