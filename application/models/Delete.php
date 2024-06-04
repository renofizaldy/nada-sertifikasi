<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Delete extends CI_Model {

	private $suf;

	function __construct() {
		parent:: __construct();
		$this->config->load('setup');
		$this->suf = $this->config->item('db_suffix');
	}

	public function invoice($a, $b) {
		switch($a) {
			case 'bukti': 
				unlink('./assets/u/'.$b['file']);
				$qry_upd = $this->db->update('marment_invoice', ['bukti_invoice'=>null], ['id'=>$b['id']]);
				if ($qry_upd) {
					return true;
				} return false;
			break;
			default: 
				$this->db->trans_begin();

				$idx   = (int) cleanInput($b);
				$sel_1 = $this->db->get_where('marment_invoice', ['id' => $idx]);
				if ($res_1 = $sel_1->row()) {
					unlink('./assets/u/'.$res_1->bukti_invoice);

					$id_marketplace = $res_1->id_marketplace;

					$sel_2 = $this->db->get_where('marment_invoice_item', ['invoice' => $idx]);
					$res_2 = $sel_2->result_array();
					foreach($res_2 as $res2) {
						$id_produk = $res2['produk'];
						$quantity  = $res2['quantity'];
	
						$sel_3 = $this->db->get_where('marment_produk', ['id' => $id_produk]);
						if ($res_3 = $sel_3->row()) {
							$this->db->update('marment_produk', ['jml_kirim' => $res_3->jml_kirim - $quantity], ['id' => $id_produk]);
	
							$sel_4 = $this->db->get_where('marment_stok', ['produk' => $id_produk, 'marketplace' => $id_marketplace]);
							if ($res_4 = $sel_4->row()) {
								$this->db->update('marment_stok', ['stok' => $res_4->stok + $quantity], ['id' => $res_4->id]);
							}
						}
					}

					$this->db->delete('marment_invoice', ['id' => $idx]);
					$this->db->delete('marment_invoice_item', ['invoice' => $idx]);
				}

				if ($this->db->trans_status() == false) {
					$this->db->trans_rollback();
					return false;
				} else {
					$this->db->trans_commit();
					return true;
				}
			break;
		}
	}

} ?>
