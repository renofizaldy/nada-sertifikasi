<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Route extends CI_Controller {

	var $img_host;
	var $assets;

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
		$this->img_host = $this->config->item('site_img');
		$this->assets   = base_url($this->config->item('assets'));
	}

	public function sesi($id) {
		session_start();
		switch($id) {
			case 'pusat':
				$_SESSION['level_os'] = 1;
				$_SESSION['id_cabang'] = "";
			break;
			case 'cabang':
				$_SESSION['level_os'] = 8;
				$_SESSION['id_cabang'] = "CB12";
			break;
			case 'view':
				$_SESSION['level_os'] = 5;
				$_SESSION['id_cabang'] = "";
			break;
		}
		redirect(base_url('route'));
	}

	public function index() {
		session_start();

		$this->load->model('select');
		$var['top_title'] = "Marketplace Management";

		$var['stylesheet'] = array(
			$this->assets.'plugins/datatables/datatables.css'
		);

		$var['output'] = array(
			'level_os'     => 5,
			'set_status'   => $this->config->item('status'),
			'res_exact'    => $this->select->get('list_exact'),
			'res_marplace' => $this->select->get('list_marketplace', ['tipe_akun' => 1]),
			'url_tables'   => base_url('req/get/produk/list/')
		);

		view('client/ui_home', $var);
	}

	public function marplace() {
		session_start();
		if (!isset($_SESSION['level_os'])) {
			show_404();
			exit();
		}
		if ($_SESSION['level_os'] == 5) {
			redirect(base_url('invoice'));
		}

		$this->load->model('select');
		$var['top_title'] = "Marketplace Management";

		$var['stylesheet'] = array(
			$this->assets.'plugins/datatables/datatables.css'
		);

		$var['output'] = array(
			'level_os'       => $_SESSION['level_os'],
			'set_status'     => $this->config->item('status'),
			'res_marplace'	 => $this->select->get('list_marketplace'),
			'url_tables' 	 => base_url('req/get/produk/list/')
		);

		view('client/ui_marplace', $var);
	}

}
