<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function surat() {
		$this->db->trans_begin();

		$this->db->update('surat', [
			'nomor_surat'   => cleanInput($this->input->post('up_nomorsurat')),
			'nama_pengirim' => cleanInput($this->input->post('up_namapengirim')),
			'waktu'         => cleanInput($this->input->post('up_waktu')),
			'lampiran'      => cleanInput($this->input->post('up_lampiran')),
			'perihal'       => cleanInput($this->input->post('up_perihal')),
			'nama_penerima' => cleanInput($this->input->post('up_namapenerima')),
			'isi_surat'     => cleanInput($this->input->post('up_isisurat')),
			'unit_penerbit' => cleanInput($this->input->post('up_unitpenerbit')),
			'tempat'        => cleanInput($this->input->post('up_tempat')),
			'pengesah'      => cleanInput($this->input->post('up_pengesah')),
			'tembusan'      => cleanInput($this->input->post('up_tembusan')),
		], [
			'id' => $this->input->post('data_id')
		]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

} ?>
