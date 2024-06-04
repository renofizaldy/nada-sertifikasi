<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Route extends CI_Controller {

	var $assets;

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
		$this->assets = base_url($this->config->item('assets'));
	}

	public function dashboard() {
		if ($this->session->has_userdata('user_id')) {
			$this->load->model('select');
			$var['top_title']  = "Dashboard";
			$var['stylesheet'] = array(
				$this->assets.'plugins/datatables/datatables.css'
			);
			$var['output'] = [
				'res_total_masuk' => count($this->select->getSurat('surat_masuk')),
				'res_total_keluar' => count($this->select->getSurat('surat_keluar'))
			];
			view('client/ui_dashboard', $var);
		} else {
			redirect(base_url('login'));
		}
	}

	public function surat($param='surat_masuk') {
		if ($this->session->has_userdata('user_id')) {
			$this->load->model('select');
			$title             = ($param == 'surat_masuk') ? 'Masuk' : 'Keluar';
			$var['top_title']  = "Surat ".$title;
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

	public function print($param='surat_masuk') {
		if ($this->session->has_userdata('user_id')) {
			$this->load->model('select');
			$title             = ($param == 'surat_masuk') ? 'Masuk' : 'Keluar';
			$var['top_title']  = "Surat ".$title;
			$var['stylesheet'] = [
				$this->assets.'css/normalize.min.css',
				$this->assets.'css/paper.css'
			];
			$var['output'] = [
				'param'     => $param,
				'res_items' => $this->select->getSurat($param)
			];
			view('client/print_surat', $var);
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
