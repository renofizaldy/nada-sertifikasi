<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Model {

	private $suf;

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
		$this->suf = $this->config->item('db_suffix');
	}

	public function detail() {
		$this->load->model('select');
		$this->db->trans_begin();

		// UPDATING
		$this->db->update(
			'marment_produk', 
			[
				// 'stok_alokasi' => (int) $this->input->post('upd_alokasi_stok'),
				'diskon'       => (int) $this->input->post('upd_diskon'),
				// 'ongkir'       => (int) $this->input->post('upd_ongkir'),
				'berat'        => (int) $this->input->post('upd_berat'),
				// 'hpp_pusat'    => (int) $this->input->post('upd_hpp_pusat'),
				// 'hpp_cabang'   => (int) $this->input->post('upd_hpp_cabang'),
				// 'price_list'   => (int) $this->input->post('upd_price_list'),
				'harga_netto'  => (int) str_replace([",","."], "", $this->input->post('upd_netto')),
				'status'       => (int) $this->input->post('upd_status')
			], 
			[
				'id' => $this->input->post('upd_produk_id')
			]
		);

		// $this->db->update(
		// 	'marketing_tb_order', 
		// 	[
		// 		'jatah' => (int) $this->input->post('upd_alokasi_stok')
		// 	],
		// 	[
		// 		'id_order' => $this->input->post('upd_id_order')
		// 	]
		// );

		// CHECK STATUS
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function stok() {
		$data = array(
			'id_produk'   	=> $this->input->post('id_produk'),
			'id_stok'     	=> $this->input->post('id_stok'),
			'stok'        	=> $this->input->post('stok'),
			'harga_jual'  	=> $this->input->post('harga_jual'),
			// 'link_produk' 	=> $this->input->post('link_produk'),
			// 'waktu_input' 	=> $this->input->post('waktu_input'),
			// 'waktu_hapus' 	=> $this->input->post('waktu_hapus'),
			// 'status'      	=> $this->input->post('status')
		);

		$this->db->trans_begin();

		foreach($data['id_stok'] as $k=>$v) {
			if (strlen($v) > 0) {
				$this->db->update(
					'marment_stok', 
					array(
						'stok'       	=> $data['stok'][$k],
						'price' 		=> str_replace([",","."], "", $data['harga_jual'][$k]),
						// 'link_produk'	=> $data['link_produk'][$k],
						// 'time_push'		=> $data['waktu_input'][$k],
						// 'time_pull'		=> $data['waktu_hapus'][$k],
						// 'status'     	=> (empty($data['status'][$k])) ? 0 : 1
					), 
					array(
						'id' => $data['id_stok'][$k]
					)
				);
			} else {
				$this->db->insert(
					'marment_stok', 
					array(
						'produk' 		=> $data['id_produk'],
						'marketplace' 	=> $k,
						'stok'       	=> $data['stok'][$k],
						'price' 		=> str_replace([",","."], "", $data['harga_jual'][$k]),
						// 'link_produk'	=> $data['link_produk'][$k],
						// 'time_push'		=> $data['waktu_input'][$k],
						// 'time_pull'		=> $data['waktu_hapus'][$k],
						// 'status'     	=> (empty($data['status'][$k])) ? 0 : 1
					)
				);
			}
		}

		// CHECK STATUS
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function info() {
		$this->db->trans_begin();

		$this->db->update(
			'mp_tb_produk',
			[
				'marment_judul'     => cleanInput((string) filter_input(INPUT_POST, 'judul')),
				'marment_deskripsi' => cleanInput(nl2br((string) filter_input(INPUT_POST, 'deskripsi')))
			],
			[
				'id_produk' => cleanInput((string) filter_input(INPUT_POST, 'id_produk'))
			]
		);

		// CHECK STATUS
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function marplace() {
		$data = array(
			'marketplace' 	=> cleanInput($this->input->post('marketplace')),
			'email'       	=> cleanInput($this->input->post('email')),
			'username'    	=> cleanInput($this->input->post('username')),
			'password'    	=> cleanInput($this->input->post('password')),
			'login'       	=> cleanInput($this->input->post('login_link')),
			'ppn'         	=> $this->input->post('ppn'),
			'fee'         	=> $this->input->post('fee'),
			'fee_type'    	=> $this->input->post('fee_type'),
			'catatan'     	=> cleanInput($this->input->post('informasi')),
		);

		$qry = $this->db->update('marment_marketplace', $data, array('id' => $this->input->post('v_id')));
		if ($qry) {
			return true;
		} return false;
	}

	public function invoice() {
		$this->load->model('select');

		$p_resi   = cleanInput($this->input->post('ip_resi'));
		$p_status = cleanInput($this->input->post('status'));
		if (strlen($p_resi) > 0 AND ($p_status == 0 OR $p_status == 1)) {
			$set_status = 2;
		} else {
			$set_status = $p_status;
		}

		$transaksi_masuk = $this->input->post('rt_subtotal') - ($this->input->post('ai_potongan_harga') + $this->input->post('ai_fee_marketplace'));
		$ppn = ($this->input->post('rt_subtotal') - $this->input->post('ai_potongan_harga')) * (10/100);

		$data = [
			'buyer_nama'   		=> cleanInput($this->input->post('dp_nama')),
			'buyer_email'  		=> cleanInput($this->input->post('dp_email')),
			'buyer_telepon'		=> cleanInput($this->input->post('dp_telepon')),
			'buyer_alamat' 		=> cleanInput(nl2br($this->input->post('dp_alamat'))),
			'buyer_catatan'		=> cleanInput(nl2br($this->input->post('dp_catatan'))),

			'info_kurir'   		=> cleanInput($this->input->post('ip_kurir')),
			'info_resi'    		=> cleanInput($this->input->post('ip_resi')),

			'no_faktur' 		=> cleanInput($this->input->post('no_faktur')),

			'id_marketplace'	=> $this->input->post('ai_marketplace'),
			'marketplace'   	=> $this->select->value(
				'single_column', 
				[
					'table'	 	=> 'marment_marketplace',
					'column' 	=> 'marketplace',
					'where_col' => 'id',
					'where_val' => $this->input->post('ai_marketplace')
				]
			),
			'potongan_harga'	=> (int) cleanInput($this->input->post('ai_potongan_harga')),
			'fee_marketplace'   => (int) cleanInput($this->input->post('ai_fee_marketplace')),
			'biaya_kirim' 		=> (int) cleanInput($this->input->post('ai_biaya_kirim')),
			'ppn' 				=> $ppn,
			'info_catatan' 		=> cleanInput(nl2br($this->input->post('ip_catatan'))),
			'waktu_beli'    	=> cleanInput($this->input->post('ai_waktu_beli')),
			'status' 			=> $set_status,
			'total_harga' 		=> $transaksi_masuk,
			'total_netto' 		=> $transaksi_masuk - ($ppn + $this->input->post('ai_biaya_kirim')),
			'submit'			=> date('Y-m-d H:i:s')
		];

		if (isset($_FILES['userfile'])) {
			$data['bukti_invoice'] = uploader('native', ['file'=>'userfile', 'name'=>'bukti-'.time()]);
		}

		$qry = $this->db->update("marment_invoice", $data, ['id' => $this->input->post('id_invoice')]);
		if ($qry){
			return true;
		} return false;
	}

	public function invoice_status($a) {
		$idx = (int) cleanInput($a);
		$qry = $this->db->update('marment_invoice', ['status' => 3], ['id' => $idx]);
		if ($qry) {
			return true;
		} return false;
	}

	public function invoice_resifaktur($a) {
		$this->load->model('select');
		$idx = (int) cleanInput($a);
		$ppn_val = (int) $this->select->getWhere('ppn', 'marment_invoice', ['id'=>$idx], [], false, true)[0]->ppn;

		if ($this->input->post('biaya_kirim') > 0) {
			$netto_val = (int) $this->select->getWhere('total_harga', 'marment_invoice', ['id'=>$idx], [], false, true)[0]->total_harga - ($ppn_val + (int) $this->input->post('biaya_kirim'));
			$qry = $this->db->update(
				'marment_invoice',
				[
					'no_faktur'   => cleanInput($this->input->post('no_faktur')),
					'info_resi'   => $this->input->post('info_resi'),
					'biaya_kirim' => cleanInput($this->input->post('biaya_kirim')),
					'total_netto' => $netto_val
				], 
				[
					'id' => $idx
				]
			);
		} else {
			$netto_val = (int) $this->select->getWhere('total_harga', 'marment_invoice', ['id'=>$idx], [], false, true)[0]->total_harga - ($ppn_val);
			$qry = $this->db->update(
				'marment_invoice', 
				[
					'no_faktur'   => ($this->input->post('no_faktur')),
					'info_resi'   => $this->input->post('info_resi'),
					'biaya_kirim' => ($this->input->post('biaya_kirim')),
					'total_netto' => $netto_val
				], 
				[
					'id' => $idx
				]
			);
		}

		if (strlen(cleanInput($this->input->post('info_resi'))) > 0) {
			$set_status = 2;
			$qry = $this->db->update(
				'marment_invoice', 
				[
					'status'      => 2
				], 
				[
					'id' => $idx
				]
			);
		}

		if ($qry) {
			return true;
		} return false;
	}

	public function import($a) {
		require_once(APPPATH.'third_party/spreadsheet-reader/php-excel-reader/excel_reader2.php');
		require_once(APPPATH.'third_party/spreadsheet-reader/SpreadsheetReader.php');

		switch($a) {
			case 'replacement':
				$file = uploader('excel', array('file'=>'file_input', 'name'=>'replacement'));
				$data = array();
				$reader = new SpreadsheetReader('./uploads/'.$file);

				foreach($reader as $row) {
					$data[] = $row;
				}

				unset($data[0]);
				$data = array_values($data);

				$truncate = $this->db->truncate("mp_tb_replacement");

				foreach($data as $x=>$y) {
					$input = array(
						'id'                  	=> $y[0],
						'rpl_cabang_id'       	=> (str_length($y[1]) > 0) ? $y[1] : null,
						'rpl_cabang_nama'     	=> (str_length($y[2]) > 0) ? $y[2] : null,
						'rpl_jenis'           	=> (str_length($y[3]) > 0) ? $y[3] : null,
						'rpl_bahan'           	=> (str_length($y[4]) > 0) ? $y[4] : null,
						'rpl_nomor'           	=> (str_length($y[5]) > 0) ? $y[5] : null,
						'rpl_jumlah'          	=> (str_length($y[6]) > 0) ? $y[6] : null,
						'rpl_material_id'     	=> (str_length($y[7]) > 0) ? $y[7] : null,
						'rpl_material_kode'   	=> (str_length($y[8]) > 0) ? $y[8] : null,
						'rpl_material_nama'   	=> (str_length($y[9]) > 0) ? $y[9] : null,
						'rpl_material_satuan' 	=> (str_length($y[10]) > 0) ? $y[10] : null,
						'rpl_material_harga'  	=> (str_length($y[11]) > 0) ? $y[11] : null,
						'rpl_exact_code'      	=> (str_length($y[12]) > 0) ? $y[12] : null,
						'rpl_exact_name'      	=> (str_length($y[13]) > 0) ? $y[13] : null,
						'rpl_komp_id'         	=> (str_length($y[14]) > 0) ? $y[14] : null,
						'rpl_komp_nomor'      	=> (str_length($y[15]) > 0) ? $y[15] : null,
						'rpl_komp_kode'       	=> (str_length($y[16]) > 0) ? $y[16] : null,
						'rpl_komp_nama'       	=> (str_length($y[17]) > 0) ? $y[17] : null,
						'rpl_komp_satuan'     	=> (str_length($y[18]) > 0) ? $y[18] : null,
						'rpl_komp_harga'      	=> (str_length($y[19]) > 0) ? $y[19] : null,
						'rpl_komp_exact_code' 	=> (str_length($y[20]) > 0) ? $y[20] : null,
						'rpl_komp_exact_name' 	=> (str_length($y[21]) > 0) ? $y[21] : null,
						'rpl_tgl_input'       	=> (str_length($y[22]) > 0) ? timepub($y[22], 'sql') : null,
						'rpl_tgl_respon'      	=> (str_length($y[23]) > 0) ? timepub($y[23], 'sql') : null,
						'rpl_tgl_rencana'     	=> (str_length($y[24]) > 0) ? timepub($y[24], 'sql') : null,
						'rpl_tgl_realisasi'   	=> (str_length($y[25]) > 0) ? timepub($y[25], 'sql') : null,
						'rpl_tgl_kirim'       	=> (str_length($y[26]) > 0) ? timepub($y[26], 'sql') : null,
						'rpl_status'          	=> $y[27]
					);
					$this->db->insert('mp_tb_replacement', $input);
				}
			break;
			case 'komponen':
				$file = uploader('excel', array('file'=>'file_input', 'name'=>'komponen'));
				$data = array();
				$reader = new SpreadsheetReader('./uploads/'.$file);

				foreach($reader as $row) {
					$data[] = $row;
				}

				unset($data[0]);
				$data = array_values($data);

				$truncate = $this->db->truncate('mr_tb_rpl_komponen');

				foreach($data as $x=>$y) {
					$input = array(
						'exact_kode' 	=> $y[1],
						'exact_nama' 	=> $y[2],
						'komp_nomor' 	=> $y[3],
						'komp_kode'  	=> $y[4],
						'komp_nama'  	=> $y[5],
						'komp_satuan'	=> $y[6],
						'komp_harga' 	=> $y[7],
					);
					$this->db->insert('mr_tb_rpl_komponen', $input);
				}
			break;
			case 'material':
				$file = uploader('excel', array('file'=>'file_input', 'name'=>'komponen'));
				$data = array();
				$reader = new SpreadsheetReader('./uploads/'.$file);

				foreach($reader as $row) {
					$data[] = $row;
				}

				unset($data[0]);
				$data = array_values($data);

				$truncate = $this->db->truncate('mr_tb_rpl_material');

				$i = 0;
				foreach($data as $x=>$y) {
					$input = array(
						'material_kode'   => $y[1],
						'material_nama'   => $y[2],
						'material_satuan' => $y[3],
						'material_harga'  => $y[4]
					);
					$this->db->insert('mr_tb_rpl_material', $input);
					$i++;
				}
				if ($i == count($data)) {
					return true;
				}
			break;
		}
	}

} ?>
