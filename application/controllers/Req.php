<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Req extends CI_Controller {

	public function gen_pass($pass=null) {
		$saved = password_hash($pass, PASSWORD_DEFAULT);
		echo $saved;
	}

	public function login() {
		$this->load->model('select');
		$qry = $this->select->login();
		if ($qry) {
			redirect(base_url('dashboard'));
		} else {
			redirect(base_url('login'));
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}

	public function get($a=null, $b=null, $c=null) {
		$this->load->model('select');
		switch($a) {

			case 'surat_list':
				$data = [
					'draw'            => 1,
					'recordsFiltered' => 1,
					'recordsTotal'    => 1,
					'data'            => []
				];

				$qry = $this->select->getSurat($b);
				if (!empty($qry)) {
					foreach($qry as $row) {
						$uri_detail = base_url('req/get/surat_detail/'.$row['id']);
						$uri_delete = base_url('req/drop/surat/'.$row['id']);

						$data['data'][] = [
							$row['nomor_surat'],
							$row['nama_pengirim'],
							timepub($row['waktu'], 'Y/M/D'),
							$row['tempat'],
							$row['lampiran'],
							$row['perihal'],
							"<a onclick=\"view_detail('{$uri_detail}');\" href='#!' class='btn btn-secondary btn-sm'>Ubah</a>&nbsp;&nbsp;<a onclick=\"redirect('{$uri_delete}');\" href='#!' class='btn btn-primary btn-sm'>Hapus</a>"
						];
            $data['recordsFiltered'] = count($qry);
            $data['recordsTotal'] = count($qry);
					}
				} else {
					$data['recordsFiltered'] = 0;
					$data['recordsTotal'] = 0;
				}

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($data));
			break;

			case 'surat_detail':
				$data = [];
				$id   = (int) $b;
				$qry  = $this->select->getSuratDetail($id);
				if (!empty($qry)) {
					$data = $qry;
				}

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($data));
			break;

			default:
				show_404();
				exit;
			break;
		}
	}

	public function post($a=null, $b=null, $c=null) {
		$this->config->load('setup');
		$this->load->model('insert');
		switch($a) {
			case 'surat':
				$param = $this->input->post('in_jenissurat');
				$qry   = $this->insert->surat();
				redirect(base_url('surat/'.$param));
			break;
			default:
				show_404();
				exit;
			break;
		}
	}

	public function put($a=null, $b=null) {
		$this->config->load('setup');
		$this->load->model('update');
		switch($a) {
			case 'surat':
				$param = $this->input->post('up_jenissurat');
				$qry   = $this->update->surat();
				redirect(base_url('surat/'.$param));
			break;
			default:
				show_404();
				exit;
			break;
		}
	}

	public function drop($a=null, $b=null) {
		$this->config->load('setup');
		$this->load->model('delete');

		switch($a) {
			case 'surat':
				$qry   = $this->delete->surat($b);
				$param = (!empty($qry)) ? $qry : 'surat_masuk';
				redirect(base_url('surat/'.$param));
			break;
			default:
				show_404();
				exit;
			break;
		}
	}

}
