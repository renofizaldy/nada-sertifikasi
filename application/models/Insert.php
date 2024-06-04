<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function surat($a=null) {
		$this->db->trans_begin();

		$data = [
			'jenis'         => $this->input->post('in_jenissurat'),
			'nomor_surat'   => cleanInput($this->input->post('in_nomorsurat')),
			'nama_pengirim' => cleanInput($this->input->post('in_namapengirim')),
			'waktu'         => cleanInput($this->input->post('in_waktu')),
			'lampiran'      => cleanInput($this->input->post('in_lampiran')),
			'perihal'       => cleanInput($this->input->post('in_perihal')),
			'nama_penerima' => cleanInput($this->input->post('in_namapenerima')),
			'isi_surat'     => cleanInput($this->input->post('in_isisurat')),
			'unit_penerbit' => cleanInput($this->input->post('in_unitpenerbit')),
			'tempat'        => cleanInput($this->input->post('in_tempat')),
			'pengesah'      => cleanInput($this->input->post('in_pengesah')),
			'tembusan'      => cleanInput($this->input->post('in_tembusan')),
			'by_user'       => 1
		];
		$this->db->insert('surat', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

} ?>
