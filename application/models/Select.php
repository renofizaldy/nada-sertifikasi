<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Select extends CI_Model {

	private $suf;

	private $tb_marketing = "marketing_tb_";
	private $tb_mp = "mp_tb_";

	function __construct() {
		parent::__construct();
		$this->config->load('setup');
		$this->suf = $this->config->item('db_suffix');
	}

	public function login() {
		$this->load->library('Privates');
		$db_main = "u4730166_qrc1.";

		$data =  array(
			'team_username' => $this->security->xss_clean(trim($this->input->post('username'))),
			'team_password' => $this->privates->hash(
				$this->enc_opt['salt1'],
				$this->security->xss_clean(trim($this->input->post('password'))),
				$this->enc_opt['salt2'])
		);

		$query = $this->db->get_where($db_main.'pd_team', $data);

		if ($query->num_rows() == 1) {
			$row  = $query->row();

			if (array_key_exists($row->id_team, $this->config->item('list_kantor'))) {

				$data = array(
					'user_id'        => $row->id_team,
					'user_name'  	 => htmlspecialchars_decode($row->team_name),
					'user_username'  => htmlspecialchars_decode($row->team_username),
					'user_role' 	 => $row->team_privilege,
					'user_validated' => TRUE
				);
				$this->session->set_userdata($data);
				return true;

			} return false;

		} return false;
	}

	public function get($a, $b=null) {
		switch($a) {
			case 'list_exact':
				$sql = "
					SELECT
						{$this->tb_mp}produk.exact_code,
						{$this->tb_mp}produk.exact_name
					FROM
						{$this->tb_mp}produk
					LEFT JOIN
						{$this->tb_marketing}harga
						ON {$this->tb_mp}produk.exact_code = {$this->tb_marketing}harga.exact_code
					LEFT JOIN
						{$this->tb_marketing}periode
						ON {$this->tb_marketing}harga.tgl_periode = {$this->tb_marketing}periode.tgl_periode
					WHERE 
						NOT EXISTS (
							SELECT 
								{$this->tb_mp}produk.exact_code, {$this->tb_mp}produk.exact_name
							FROM
								marment_produk b
							WHERE 
								{$this->tb_mp}produk.exact_code = b.exact_code
						)
					ORDER BY 
						{$this->tb_mp}produk.exact_name ASC
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_exact_new':
				$sql = "SELECT 
						a.id_order, 
						a.exact_code, 
						b.exact_name, 
						a.jatah,
						a.jml_kirim, 
						(a.jatah-a.jml_kirim) as sisa_jatah
					FROM 
						marketing_tb_order a
					LEFT JOIN 
						mp_tb_produk b 
						ON a.exact_code = b.exact_code
					WHERE 
						a.id_cabang = 'CB32' 
						AND a.status_order = 'y' 
						AND a.status_marketplace = 'n'
					ORDER BY b.exact_name ASC
				";
				$sql = "SELECT 
						a.exact_code, a.exact_name
					FROM 
						mp_tb_produk a
					WHERE 
						NOT EXISTS (
							SELECT 
								a.exact_code, a.exact_name
							FROM
								marment_produk b
							WHERE 
								a.exact_code = b.exact_code
						)
						AND a.status = 'PRODUK'
					ORDER BY a.exact_name ASC
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_hpp_by_exact':
				$exc = $this->input->get('exact');
				$sql = "
					SELECT
						{$this->tb_marketing}harga.hpp,
						{$this->tb_marketing}harga.harga_agen,
						{$this->tb_marketing}harga.pricelist,
						{$this->tb_mp}produk.total_gw
					FROM
						{$this->tb_marketing}harga
					INNER JOIN
						{$this->tb_marketing}periode
						ON {$this->tb_marketing}harga.tgl_periode = {$this->tb_marketing}periode.tgl_periode
					LEFT JOIN
						{$this->tb_mp}produk
						ON {$this->tb_mp}produk.exact_code = '{$exc}'
					WHERE
						{$this->tb_marketing}harga.exact_code = '{$exc}'
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_stok_by_produk':
				$sql = "
					SELECT
						SUM(stok) as stok
					FROM
						marment_stok
					WHERE
						marment_stok.produk = {$b}
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->row()->stok;
				} return false;
			break;

			case 'list_stok_grup_marketplace':
				$sql = "SELECT marketplace as idm, SUM(stok) as stok FROM marment_stok WHERE produk = {$b} GROUP BY marketplace";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_produk':
				$sql = "SELECT * FROM marment_produk WHERE jml_order != stok_alokasi";
				$qry = $this->db->get("marment_produk");
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_marketplace':
				$where = "";

				if (!is_null($b) && is_array($b) && array_key_exists('tipe_akun', $b)) {
					$where .= " WHERE marment_marketplace.tipe_akun = {$b['tipe_akun']}";
				}

				$sql = "
					SELECT
						marment_marketplace.*,
						(
							SELECT 
								COUNT(*) 
							FROM 
								marment_stok
							WHERE 
								marment_stok.marketplace = marment_marketplace.id
								AND marment_stok.stok != 0
						) AS stok 
					FROM
						marment_marketplace
					{$where}
					ORDER BY marment_marketplace.sort_order ASC
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_invoice':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marment_invoice.marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " marment_invoice.status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " marment_invoice.waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT 
							marment_invoice.*, 
							marment_invoice_item.exact_name as tipe_produk,
							marment_invoice_item.exact_code as exact_produk,
							(SELECT SUM(marment_invoice_item.harga_total)) as harga_produk,
							COUNT(*) as qty,
							SUM(marment_produk.hpp_pusat * marment_invoice_item.quantity) as hpp_pusat_total,
							SUM(marment_produk.hpp_cabang * marment_invoice_item.quantity) as hpp_cabang_total 
						FROM 
							marment_invoice 
						LEFT JOIN 
							marment_invoice_item 
							ON marment_invoice.id = marment_invoice_item.invoice 
						LEFT JOIN
							marment_produk
							ON marment_invoice_item.produk = marment_produk.id
						{$where} 
						GROUP BY
							marment_invoice.id
						ORDER BY 
							waktu_beli DESC";

				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_invoice_item':
				$sql = "SELECT
							marment_invoice_item.*,
							mp_tb_produk.koli,
							marment_produk.hpp_pusat,
							marment_produk.berat,
							marment_produk.ongkir
						FROM
							marment_invoice_item
						JOIN
							mp_tb_produk
							ON mp_tb_produk.exact_code = marment_invoice_item.exact_code
						LEFT JOIN
							marment_produk
							ON marment_invoice_item.produk = marment_produk.id
						WHERE
							marment_invoice_item.invoice = {$b}
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_province':
				$qry = $this->db->get('marment_wai_provinsi');
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'list_city':
				$qry = $this->db->get_where('marment_wai_kota', array('province_id'=>$b));
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'detail_invoice':
				$sql = "SELECT
					marment_invoice.*,
					(
						SELECT
							SUM(marment_produk.hpp_pusat) as hpp_pusat_total
						FROM
							marment_invoice
						INNER JOIN
							marment_invoice_item
							ON marment_invoice_item.invoice = marment_invoice.id
						INNER JOIN
							marment_produk
							ON marment_produk.id = marment_invoice_item.produk
						WHERE
							marment_invoice.id = {$b}
					) as hpp_pusat
					FROM
						marment_invoice
					WHERE
						marment_invoice.id = {$b}
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'detail_marketplace':
				$qry = $this->db->get_where("marment_marketplace", array('id'=>$b));
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'detail_produk':
				$qry = $this->db->get_where("marment_produk", array('id'=>$b));
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'detail_stok':
				$qry = $this->db->get_where("marment_stok", array('produk'=>$b));
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'detail_exact':
				$sql = "
					SELECT
						id_produk, 
						marment_judul, 
						marment_deskripsi, 
						exact_code, 
						exact_name,
						koli,
						total_nw,
						total_gw,
						nama_warna,
						nama_finishing,
						dimensi_panjang,
						dimensi_lebar,
						dimensi_tinggi,
						box1_panjang,
						box1_lebar,
						box1_tinggi,
						box2_panjang,
						box2_lebar,
						box2_tinggi
					FROM
						mp_tb_produk
					WHERE
						exact_code = '{$b}'
				";
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'detail_exact_img':
				$this->db->trans_begin();

				$qry1 = $this->getWhere('alamat_gambar', 'mp_tb_produk', ['id_produk' => $b], "", false, true);
				$cov = $qry1[0]->alamat_gambar;
				$qry2 = $this->getWhere('pho_file', 'mp_tb_produk_foto', ['pho_pro' => $b], "", true, false);

				$result = [$cov];
				foreach($qry2 as $row) {
					$result[] = $row['pho_file'];
				}

				// CHECK STATUS
				if ($this->db->trans_status() === false) {
					$this->db->trans_rollback();
					return false;
				} else {
					$this->db->trans_commit();
					return $result;
				}
			break;

			case 'stat_summary':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marment_invoice.marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " marment_invoice.status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " marment_invoice.waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						(
							SELECT
								SUM(marment_invoice_item.harga_total) as gross
							FROM
								marment_invoice
							INNER JOIN
								marment_invoice_item
								ON marment_invoice_item.invoice = marment_invoice.id
							{$where}
						) as gross,
						SUM(marment_invoice.total_harga) as transaksi_masuk,
						SUM(marment_invoice.total_netto) as netto
					FROM
						marment_invoice
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_summary_hpp':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marment_invoice.marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " marment_invoice.status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " marment_invoice.waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT 
						SUM(marment_produk.hpp_pusat * marment_invoice_item.quantity) as hpp_pusat_total,
						SUM(marment_produk.hpp_cabang * marment_invoice_item.quantity) as hpp_cabang_total
					FROM 
						marment_invoice 
					LEFT JOIN 
						marment_invoice_item 
						ON marment_invoice.id = marment_invoice_item.invoice 
					LEFT JOIN
						marment_produk
						ON marment_invoice_item.produk = marment_produk.id
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_paling_dibeli':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marment_invoice.marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " marment_invoice.status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " marment_invoice.waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice_item.exact_code,
						marment_invoice_item.exact_name,
						COUNT(*) as count
					FROM
						marment_invoice
					INNER JOIN
						marment_invoice_item
						ON marment_invoice_item.invoice = marment_invoice.id
					{$where}
					GROUP BY
						exact_code
					ORDER BY
						count DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_location_provinsi':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.buyer_provinsi,
						COUNT(*) as count
					FROM
						marment_invoice
					{$where}
					GROUP BY
						buyer_id_provinsi
					ORDER BY
						count DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_location_kota':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.buyer_kota,
						COUNT(*) as count
					FROM
						marment_invoice
					{$where}
					GROUP BY
						buyer_id_kota
					ORDER BY
						count DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_fee_marketplace':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						SUM(fee_marketplace) as total
					FROM
						marment_invoice
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_voucher':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						SUM(potongan_harga) as total
					FROM
						marment_invoice
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_biaya_kirim':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						SUM(biaya_kirim) as total
					FROM
						marment_invoice
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_ppn':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if ($b['mplace'] !== 'all') {
						$w_mplace = " marketplace = '{$b['mplace']}'";
					}
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace." AND ".$w_status;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_status) < 1 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						SUM(ppn) as total
					FROM
						marment_invoice
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'stat_biaya_iklan':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_range  = "";

					if ($b['mplace'] !== 'all') {
						$mplace = $this->select->getWhere('id', 'marment_marketplace', ['marketplace'=>$b['mplace']], '', true, false)[0]['id'];
						$w_mplace = " marketplace = '{$mplace}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " period BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_mplace) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_mplace." AND ".$w_range;
					}
					if (strlen($w_mplace) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_mplace;
					}
					if (strlen($w_mplace) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						SUM(ammount) as total
					FROM
						marment_invoice_iklan
					{$where}
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'sort_gross':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if (is_numeric($b['status'])) {
						$w_status = " marment_invoice.status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " marment_invoice.waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.marketplace,
						SUM(marment_invoice_item.harga_total) as gross
					FROM
						marment_invoice
					INNER JOIN
						marment_invoice_item
						ON marment_invoice_item.invoice = marment_invoice.id
					{$where}
					GROUP BY
						marment_invoice.id_marketplace
					ORDER BY
						gross DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'sort_netto':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if (is_numeric($b['status'])) {
						$w_status = " marment_invoice.status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " marment_invoice.waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.marketplace,
						SUM(marment_invoice.total_netto) as netto
					FROM
						marment_invoice
					{$where}
					GROUP BY
						marment_invoice.id_marketplace
					ORDER BY
						netto DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'sort_fee':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.marketplace,
						SUM(marment_invoice.fee_marketplace) as fee
					FROM
						marment_invoice
					{$where}
					GROUP BY
						marment_invoice.id_marketplace
					ORDER BY
						fee DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'sort_voucher':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.marketplace,
						SUM(marment_invoice.potongan_harga) as voucher
					FROM
						marment_invoice
					{$where}
					GROUP BY
						marment_invoice.id_marketplace
					ORDER BY
						voucher DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'sort_ongkir':
				$where = "";
				if (is_array($b)) {
					$w_mplace = "";
					$w_status = "";
					$w_range  = "";
					
					if (is_numeric($b['status'])) {
						$w_status = " status = '{$b['status']}'";
					}
					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " waktu_beli BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_status) > 0 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_status." AND ".$w_range;
					}
					if (strlen($w_status) > 0 AND strlen($w_range) < 1) {
						$where .= " WHERE ".$w_status;
					}
					if (strlen($w_status) < 1 AND strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice.marketplace,
						SUM(marment_invoice.biaya_kirim) as ongkir
					FROM
						marment_invoice
					{$where}
					GROUP BY
						marment_invoice.id_marketplace
					ORDER BY
						ongkir DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;

			case 'sort_iklan':
				$where = "";
				if (is_array($b)) {
					$w_range  = "";

					if ($b['range'] AND is_array($b['range'])) {
						$w_range  = " period BETWEEN '{$b['range'][0]} 00:00:00' AND '{$b['range'][1]} 23:59:00'";
					}

					if (strlen($w_range) > 0) {
						$where .= " WHERE ".$w_range;
					}
				}

				$sql = "SELECT
						marment_invoice_iklan.marketplace_name as marketplace,
						SUM(marment_invoice_iklan.ammount) as ammount
					FROM
						marment_invoice_iklan
					{$where}
					GROUP BY
						marment_invoice_iklan.marketplace
					ORDER BY
						ammount DESC
				";
				
				$qry = $this->db->query($sql);
				if ($qry) {
					return $qry->result_array();
				} return false;
			break;
		}
	}

	public function count($a, $b=null) {
		switch($a) {
			case '':
			break;
		}
	}

	public function value($a, $b=null) {
		switch($a) {
			case 'single_column':
				/*
					['table', 'column', 'where_col', 'where_val']
				*/

				$this->db->select($b['column']);
				$qry = $this->db->get_where($b['table'], array($b['where_col']=>$b['where_val']));
				return $qry->result_array()[0][$b['column']];
			break;
		}
	}

	public function getWhere($select, $from, $where=array(), $order=array(), $is_array=true, $is_result=true) {
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where)) {
			foreach($where as $key=>$val) {
				$this->db->where($key, $val);
			}
		}

		if (!empty($order)) {
			foreach($order as $key=>$val) {
				$this->db->order_by($key, $val);
			}
		}

		$get = $this->db->get();
		if ($is_array == false) {
			if ($is_result == false) {
				return $get;
			} else {
				return $get->result();
			}
		} else {
			return $get->result_array();
		}
	}

} ?>
