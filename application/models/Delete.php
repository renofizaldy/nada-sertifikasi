<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Delete extends CI_Model {

	function __construct() {
		parent:: __construct();
	}

	public function surat($id) {
		$qry = $this->db->get_where('surat', ['id' => (int) $id]);
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
			$this->db->delete('surat', ['id' => (int) $id]);
			return $row->jenis;
		} else {
			return false;
		}
	}

} ?>
