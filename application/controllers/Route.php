<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Route extends CI_Controller {

	var $assets;

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
		$this->assets = base_url($this->config->item('assets'));
	}

	public function surat($param='surat_masuk') {
		if ($this->session->has_userdata('user_id')) {
			$this->load->model('select');
			$title = ($param == 'surat_masuk') ? 'Masuk' : 'Keluar';
			$var['top_title'] = "Surat ".$title;
			$var['stylesheet'] = array(
				$this->assets.'plugins/datatables/datatables.css'
			);
			$var['output'] = [
				'param'      => $param,
				'url_tables' => base_url('req/get/surat_list/'.$param)
			];
			view('client/ui_home', $var);
		} else {
			redirect(base_url('login'));
		}
	}

	public function login() {
		if ($this->session->has_userdata('user_id')) {
			redirect(base_url('surat/masuk'));
		} else {
			$var['top_title'] = "Surat Masuk";

			$var['stylesheet'] = array(
				$this->assets.'plugins/datatables/datatables.css'
			);

			view('client/login', $var);
		}
	}

}
