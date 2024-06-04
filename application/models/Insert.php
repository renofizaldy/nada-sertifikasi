<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Model {

	private $suf;

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
		$this->suf = $this->config->item('db_suffix');
	}

	public function produk($a=null) {
		$this->db->trans_begin();
		$data = array(
			'id_order'     => 1,
			'status'       => (int) $this->input->post('info_status'),
			'exact_name'   => cleanInput($this->input->post('info_tipe_produk_name')),
			'exact_code'   => cleanInput($this->input->post('info_tipe_produk')),
			'hpp_pusat'    => (int) $this->input->post('info_hpp_pusat'),
			'hpp_cabang'   => (int) $this->input->post('info_hpp_cabang'),
			'price_list'   => (int) $this->input->post('info_price_list'),
			'diskon'       => (int) $this->input->post('info_diskon'),
			'berat'        => $this->input->post('info_berat'),
			'ongkir'       => (int) $this->input->post('info_berat') * 5000,
			'harga_netto'  => (int) $this->input->post('info_netto'),
			'stok_alokasi' => (int) $this->input->post('info_alokasi_stok'),
			'create_at'    => date('Y-m-d H:i:s')
		);

		$this->db->insert('marment_produk', $data);
		// $this->db->update(
		// 	'marketing_tb_order', 
		// 	array(
		// 		'status_marketplace' => 'y'
		// 	),
		// 	array(
		// 		'id_order' => $this->input->post('info_id_order')
		// 	)
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

	public function marplace($a=null) {
		$data = array(
			'marketplace'	=> cleanInput($this->input->post('marketplace')),
			'username'   	=> cleanInput($this->input->post('username')),
			'email'      	=> cleanInput($this->input->post('email')),
			'password'   	=> cleanInput($this->input->post('password')),
			'login' 		=> cleanInput($this->input->post('login_link')),
			'ppn'        	=> $this->input->post('ppn'),
			'fee'        	=> $this->input->post('fee'),
			'fee_type'   	=> $this->input->post('fee_type'),
			'catatan'  		=> cleanInput($this->input->post('informasi')),
		);
		$qry = $this->db->insert('marment_marketplace', $data);
		if ($qry) {
			return true;
		} return false;
	}

	public function invoice($a=null) {
		$this->load->model('select');
		$this->db->trans_begin();

		$cart  = (isJson($this->input->post('_cart'))) ? json_decode(json_decode(json_encode($this->input->post('_cart'))), TRUE) : false;
		$items = array();
		for ($i=0; $i<count($cart); $i++) {
			$items[$i] = array(
				'id'   	=> $cart[$i]['id'],
				'order' => $cart[$i]['order'],
				'exact' => $cart[$i]['exact'],
				'name' 	=> $cart[$i]['name'],
				'qty'  	=> (int) cleanInput($cart[$i]['quantity']),
				'price'	=> $cart[$i]['price'],
				'total'	=> $cart[$i]['price'],
				'weight'=> $cart[$i]['weight']
			);
		}

		$calculate = array(
			'subqty'    => array_sum(array_map(function($item) { return $item['qty']; }, $items)),
			'subtotal'  => array_sum(array_map(function($item) { return $item['total']; }, $items)),
			'subweight' => array_sum(array_map(function($item) { return $item['weight']; }, $items))
		);

		$transaksi_masuk = $calculate['subtotal'] - ($this->input->post('ai_potongan_harga') + $this->input->post('ai_fee_marketplace'));
		$ppn = ($calculate['subtotal'] - $this->input->post('ai_potongan_harga')) * (10/100);

		// INSERT INVOICE
			$dat_invoice = array(
				'status' 			=> $this->input->post('status'),
				'buyer_nama'   		=> cleanInput($this->input->post('dp_nama')),
				'buyer_email'  		=> cleanInput($this->input->post('dp_email')),
				'buyer_telepon'		=> cleanInput($this->input->post('dp_telepon')),
				'buyer_alamat' 		=> cleanInput(nl2br($this->input->post('dp_alamat'))),
				'buyer_catatan'		=> cleanInput(nl2br($this->input->post('dp_catatan'))),

				'buyer_id_provinsi' => $this->input->post('dp_provinsi'),
				'buyer_provinsi'	=> ucwords(strtolower($this->select->value(
					'single_column', 
					array(
						'table'	 	=> 'marment_wai_provinsi',
						'column' 	=> 'name',
						'where_col' => 'id',
						'where_val' => $this->input->post('dp_provinsi')
					)
				))),
				'buyer_id_kota' 	=> $this->input->post('dp_kota'),
				'buyer_kota'    	=> ucwords(strtolower($this->select->value(
					'single_column', 
					array(
						'table'	 	=> 'marment_wai_kota',
						'column' 	=> 'name',
						'where_col' => 'id',
						'where_val' => $this->input->post('dp_kota')
					)
				))),

				'info_kurir'   		=> cleanInput($this->input->post('ip_kurir')),
				'info_resi'    		=> cleanInput($this->input->post('ip_resi')),

				'no_faktur' 		=> cleanInput($this->input->post('no_faktur')),

				'id_marketplace'	=> $this->input->post('ai_marketplace'),
				'marketplace'   	=> $this->select->value(
					'single_column', 
					array(
						'table'	 	=> 'marment_marketplace',
						'column' 	=> 'marketplace',
						'where_col' => 'id',
						'where_val' => $this->input->post('ai_marketplace')
					)
				),
				'potongan_harga'	=> (int) cleanInput($this->input->post('ai_potongan_harga')),
				'fee_marketplace' 	=> (int) cleanInput($this->input->post('ai_fee_marketplace')),
				'biaya_kirim' 		=> (int) cleanInput($this->input->post('ai_biaya_kirim')),
				'ppn' 				=> $ppn,
				'info_catatan' 		=> cleanInput(nl2br($this->input->post('ip_catatan'))),
				'waktu_beli'    	=> cleanInput($this->input->post('ai_waktu_beli')),
				'status' 			=> cleanInput($this->input->post('status')),
				'total_item' 		=> $calculate['subqty'],
				'total_berat' 		=> $calculate['subweight'],
				'total_harga' 		=> $transaksi_masuk,
				'total_netto' 		=> $transaksi_masuk - ($ppn + $this->input->post('ai_biaya_kirim')),
				'bukti_invoice' 	=> uploader('native', ['file'=>'userfile', 'name'=>'bukti-'.time()]),
				'submit'			=> date('Y-m-d H:i:s')
			);
			$qry_invoice = $this->db->insert("marment_invoice", $dat_invoice);
			$idx_invoice = $this->db->insert_id();

		// INSERT ITEMS
			for ($i=0; $i<count($items); $i++) {
				$dat_items = array(
					'invoice' 		=> $idx_invoice,
					'produk'       	=> $items[$i]['id'],
					'exact_code'   	=> $items[$i]['exact'],
					'exact_name'   	=> $items[$i]['name'],
					'quantity' 		=> $items[$i]['qty'],
					'harga_satuan' 	=> $items[$i]['total'] / $items[$i]['qty'],
					'harga_total'  	=> $items[$i]['total'],
					'berat'        	=> $items[$i]['weight'],
					'catatan' 		=> ""
				);
				$qry_items = $this->db->insert('marment_invoice_item', $dat_items);
			}


		foreach($items as $k=>$v) {
			$this->db->update(
				'marment_produk',
				[
					'jml_kirim' => ($this->db->get_where('marment_produk', ['id'=>$v['id']])->row()->jml_kirim + $v['qty'])
				],
				[
					'id' => $v['id']
				]
			);

			$this->db->update(
				'marment_stok', 
				[
					'stok' => (
						$this->db->get_where(
							'marment_stok', [
								'produk'      => $v['id'],
								'marketplace' => $this->input->post('ai_marketplace')
							]
						)->row()->stok - $v['qty']
					)
				],
				[
					'produk'      => $v['id'],
					'marketplace' => $this->input->post('ai_marketplace')
				]
			);
		}

		if ($this->db->trans_status() == false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function biaya_iklan($a=null) {
		$this->load->model('select');
		$this->db->trans_begin();

		$bi_marketplace = $this->input->post('marketplace');
		$bi_biaya_iklan = $this->input->post('biaya_iklan');
		$bi_tahun       = substr($this->input->post('bulan_tahun'), 0, 4);
		$bi_bulan       = substr($this->input->post('bulan_tahun'), 5, 2);

		// CHECK, IF EXIST DROP IT
		$check = $this->db->query("SELECT id FROM marment_invoice_iklan WHERE marketplace={$bi_marketplace} AND YEAR(period)={$bi_tahun} AND MONTH(period)={$bi_bulan}");
		$check_res = $check->result_array();
		if (count($check_res) > 0) {
			foreach($check_res as $res) {
				$this->db->delete('marment_invoice_iklan', ['id'=>$res['id']]);
			}
		}

		// INSERT IT
		$days = getDaysInYearMonth((int) $bi_tahun, (int) $bi_bulan, 'Y-m-d');

		$cost_per_day = $bi_biaya_iklan / count($days);

		foreach($days as $day) {
			$this->db->insert('marment_invoice_iklan', [
				'marketplace'      => $bi_marketplace,
				'marketplace_name' => $this->select->getWhere('marketplace', 'marment_marketplace', ['id'=>$bi_marketplace], '', true, false)[0]['marketplace'],
				'ammount'          => $cost_per_day,
				'period'           => $day
			]);
		}

		if ($this->db->trans_status() == false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

} ?>
