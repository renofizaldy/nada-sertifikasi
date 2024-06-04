<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Model {

	private $suf;

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
	}

	public function surat($a=null) {
		$this->db->trans_begin();

		$data = [
			'nomor_surat'   => $this->input->post('in_nomorsurat'),
			'nama_pengirim' => $this->input->post('in_namapengirim'),
			'waktu'         => $this->input->post('in_waktu'),
			'lampiran'      => $this->input->post('in_lampiran'),
			'perihal'       => $this->input->post('in_perihal'),
			'nama_penerima' => $this->input->post('in_namapenerima'),
			'isi_surat'     => $this->input->post('in_isisurat'),
			'unit_penerbit' => $this->input->post('in_unitpenerbit'),
			'tempat'        => $this->input->post('in_tempat'),
			'pengesah'      => $this->input->post('in_pengesah'),
			'tembusan'      => $this->input->post('in_tembusan'),
		];
		$this->db->insert('surat_masuk', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

} ?>
