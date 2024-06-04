<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Req extends CI_Controller {

	public function test($type, $dat=null) {
		switch($type) {
			case 'shopee':
				$file = fopen('./assets/shopee.csv', 'r');
				while (($line = fgetcsv($file)) !== FALSE) {
					$faktur = $line[0];
					$sql = "SELECT id, total_harga FROM marment_invoice WHERE no_faktur LIKE '%{$faktur}%'";
					$qry = $this->db->query($sql);
					if ($res = $qry->row()) {
						$this->db->update('marment_invoice', ['biaya_kirim'=>$line[1], 'total_netto'=>$res->total_harga-$line[1]], ['id'=>$res->id]);
					}
				}
				fclose($file);
			break;
			case 'tokopedia':
				$file = fopen('./assets/tokopedia.csv', 'r');
				while (($line = fgetcsv($file)) !== FALSE) {
					$faktur = $line[0];
					$sql = "SELECT id, total_harga FROM marment_invoice WHERE no_faktur LIKE '%{$faktur}%'";
					$qry = $this->db->query($sql);
					if ($res = $qry->row()) {
						$this->db->update('marment_invoice', ['biaya_kirim'=>$line[1], 'total_netto'=>$res->total_harga-$line[1]], ['id'=>$res->id]);
					}
				}
				fclose($file);
			break;
			case 'update_faktur':
				$qry = $this->db->get('marment_invoice')->result_array();
				foreach($qry as $res) {
					$faktur = str_replace('/GM/20', '', $res['no_faktur']);
					$this->db->update('marment_invoice', ['no_faktur' => $faktur], ['id' => $res['id']]);
				}
			break;
			case 'update_ongkir':
				$qry = $this->db->get('marment_produk')->result_array();
				foreach($qry as $res) {
					$ongkir = ($res['berat'] * 5000);
					$this->db->update('marment_produk', ['ongkir'=>$ongkir], ['id'=>$res['id']]);
				}
			break;
			case 'print_bukti':
				switch($dat) {
					case 'maret':
						$date = "'2020-03-01' AND '2020-03-31'";
					break;
					case 'april':
						$date = "'2020-04-01' AND '2020-04-30'";
					break;
				}
				$sql = "SELECT bukti_invoice FROM marment_invoice WHERE id_marketplace = 5 AND waktu_beli BETWEEN {$date} ORDER BY waktu_beli DESC";
				$var['result'] = $this->db->query($sql)->result_array();
				$var['stylesheet'] = [
					base_url('assets/v2/').'css/normalize.min.css',
					base_url('assets/v2/').'css/paper.css'
				];

				view('client/print_bukti', $var);
			break;
			case 'update_ppn':
				$sql = "SELECT 
						marment_invoice.*,
						SUM(marment_invoice_item.harga_total) as subtotal
					FROM
						marment_invoice
					INNER JOIN
						marment_invoice_item
						ON marment_invoice.id = marment_invoice_item.invoice
					GROUP BY
						marment_invoice.id
				";
				$qry = $this->db->query($sql)->result_array();
				if ($qry) {
					foreach($qry as $row) {
						$subtotal    = $row['subtotal'];
						$voucher 	 = $row['potongan_harga'];
						$total_harga = $row['total_harga'];

						$ppn         = ($subtotal-$voucher) * (10/100);

						$ongkir      = $row['biaya_kirim'];

						$this->db->update(
							'marment_invoice', 
							[
								'ppn'         => $ppn,
								'total_netto' => $total_harga - ($ongkir + $ppn)
							], 
							[
								'id' => $row['id']
							]
						);
					}
				}
			break;
			case 'fix_satuan':
				$qry = $this->db->get('marment_invoice_item')->result_array();
				if ($qry){
					foreach($qry as $row) {
						$this->db->update(
							'marment_invoice_item', 
							[
								'harga_satuan' => $row['harga_total']/$row['quantity']
							],
							[
								'id' => $row['id']
							]
						);
					}
				}
			break;
		}
	}

	public function get($a=null, $b=null, $c=null) {
		session_start();
		if (!isset($_SESSION['level_os'])) {
			show_404();
			exit();
		}
		$this->config->load('setup');
		$this->load->model('select');
		switch($a) {
			case 'produk':
				$data['data'] = array();
				switch($b) {
					case 'harga':
						$qry = $this->select->get('list_hpp_by_exact');

						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								// TABLES
								$data['data'][] = array(
									'pusat'     => $row['hpp'],
									'cabang'    => $row['harga_agen'],
									'pricelist' => $row['pricelist'],
									'berat' 	=> $row['total_gw']
								);
							}
						}

						$this->output->set_header('Access-Control-Allow-Origin: *', false);
						$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($data));
					break;
					case 'list':
						$qry = $this->select->get('list_produk');
						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								$uri_detail = base_url('req/get/produk/detail/'.$row['id']);
								$uri_stok   = base_url('req/get/produk/stok/'.$row['id']);
								$uri_edit   = base_url('req/get/produk/stok_edit_info/'.$row['id']);

								$qry_exact = $this->select->get('detail_exact', $row['exact_code']);
								if ($qry_exact AND count($qry_exact) > 0) {
									$deskripsi_check = type_data('check_no', $qry_exact[0]['marment_deskripsi']);
								}

								$stok_count = $this->select->get('list_stok_by_produk', $row['id']);
								if ($stok_count > 0 OR !is_null($stok_count)) {
									$stok_marplace = $row['stok_alokasi']-$stok_count;
								} else if (is_null($stok_count)) {
									$stok_marplace = $row['stok_alokasi'];
								} else {
									$stok_marplace = 0;
								}

								$free_stok = $row['stok_alokasi'] - $row['jml_kirim'];

								$data['data'][] = array(
									$row['exact_code'],
									$row['exact_name'],
									$stok_marplace, //$stok_marplace ." dari ". $free_stok,
									$row['diskon']."%",
									"Rp ".number_format($row['harga_netto']),
									$deskripsi_check,
									"<a onclick=\"view_detail('{$uri_detail}');\" href='javascript:void(0)' class='btn btn-secondary btn-sm'>Detail</a>&nbsp;&nbsp;<a onclick=\"view_stok('{$uri_stok}', '{$uri_edit}');\" href='javascript:void(0)' class='btn btn-primary btn-sm'>Stok</a>"
								);
							}
						}

						$this->output->set_header('Access-Control-Allow-Origin: *', false);
						$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($data));
					break;
					case 'detail':
						$qry_produk = $this->select->get('detail_produk', $c);

						$data['data']['produk']	= array();

						if ($qry_produk AND count($qry_produk) > 0) {
							foreach($qry_produk as $row) {

								$free_stok = $row['stok_alokasi'] - $row['jml_kirim'];

								$data['data']['produk'][] = array(
									'id_order'  => $row['id_order'],
									'id_produk' => $row['id'],
									'exact_code'=> $row['exact_code'],
									'exact_name'=> $row['exact_name'],
									'alokasi' 	=> $free_stok,
									'diskon' 	=> $row['diskon'],
									'berat' 	=> $row['berat'],
									'ongkir' 	=> $row['ongkir'],
									'hpp_pusat' => $row['hpp_pusat'],
									'hpp_cabang'=> $row['hpp_cabang'],
									'price_list'=> $row['price_list'],
									'netto' 	=> $row['harga_netto'],
									'status' 	=> $row['status']
								);

							}
						}

						$this->output->set_header('Access-Control-Allow-Origin: *', false);
						$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($data));
					break;
					case 'stok':
						$qry_produk = $this->select->get('detail_produk', $c);
						$qry_stok = $this->select->get('detail_stok', $c);
						$qry_marketplace = $this->select->get('list_marketplace');

						$stok_teralokasi = 0;

						$data['data']['produk']	= [];
						$data['data']['stok'] = [];
						$data['data']['marketplace'] = [];
						$data['data']['exact'] = [];
						$data['data']['image'] = [];

						if ($qry_produk AND count($qry_produk) > 0) {
							foreach($qry_produk as $row) {

								$stok_sisa = $row['stok_alokasi'] - $row['jml_kirim'];

								$data['data']['produk'][] = array(
									'id_produk' => $row['id'],
									'exact_code'=> $row['exact_code'],
									'exact_name'=> $row['exact_name'],
									'jml_kirim' => $row['jml_kirim'],
									'diskon' 	=> $row['diskon'],
									'ongkir' 	=> $row['ongkir'],
									'hpp_pusat' => $row['hpp_pusat'],
									'hpp_cabang'=> $row['hpp_cabang'],
									'price_list'=> $row['price_list'],
									'netto' 	=> $row['harga_netto'],
									'status' 	=> type_data('status_on_detail', $row['status']),

									'stok_mss'  => (int) $row['stok_alokasi'],
									'stok_sisa' => $stok_sisa,
								);

							}

							$qry_exact = $this->select->get('detail_exact', $qry_produk[0]['exact_code']);
							if ($qry_exact AND count($qry_exact) > 0) {
								$data['data']['exact'] = array(
									'judul'     => (!empty($qry_exact[0]['marment_judul'])) ? htmlspecialchars_decode($qry_exact[0]['marment_judul']) : "",
									'deskripsi' => (!empty($qry_exact[0]['marment_deskripsi'])) ? preg_replace('/^(?:<br\s*\/?>\s*)+/', '', htmlspecialchars_decode($qry_exact[0]['marment_deskripsi'])) : ""
								);

								$qry_exact_img = $this->select->get('detail_exact_img', $qry_exact[0]['id_produk']);
								if ($qry_exact_img AND count($qry_exact_img) > 0) {
									foreach($qry_exact_img as $row) {
										$img_host = $this->config->item('site_img') . $row;
										if ($_SERVER['SERVER_NAME'] == 'melody-id.com') {
											$img_host = "http://melody-id.com/mss/gambar_os/" . $row;
										}
										$data['data']['image'][] = $img_host;
									}
								}
							}
						}

						if ($qry_stok AND count($qry_stok) > 0) {
							foreach($qry_stok as $row) {

								$data['data']['stok'][] = array(
									'id'          	=> $row['id'],
									'marketplace' 	=> $row['marketplace'],
									'stok'        	=> $row['stok'],
									'ppn'         	=> $row['ppn'],
									'fee'         	=> $row['fee'],
									'price'       	=> $row['price'],
									'link'        	=> htmlspecialchars_decode($row['link_produk']),
									'status'      	=> $row['status'],
									'time_push'   	=> $row['time_push'],
									'time_pull'   	=> $row['time_pull'],
								);

								$stok_teralokasi += $row['stok'];

							}
						}

						if ($qry_marketplace AND count($qry_marketplace) > 0) {
							foreach($qry_marketplace as $row) {

								$data['data']['marketplace'][] = array(
									'id'          	=> $row['id'],
									'marketplace' 	=> $row['marketplace'],
									'fee_type' 		=> $row['fee_type']
								);

							}
						}

						$data['data']['produk'][0]['stok_teralokasi'] = $stok_teralokasi;
						$data['data']['produk'][0]['stok_free'] = $stok_sisa - $stok_teralokasi;

						$this->output->set_header('Access-Control-Allow-Origin: *', false);
						$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($data));
					break;
					case 'stok_edit_info':
						$data['data'] = [];

						$qry_produk = $this->select->get('detail_produk', $c);
						if ($qry_produk AND count($qry_produk) > 0) {
							$qry_exact = $this->select->get('detail_exact', $qry_produk[0]['exact_code']);
							if ($qry_exact AND count($qry_exact) > 0) {
								$data['data'] = [
									'id_produk'  => $qry_exact[0]['id_produk'],
									'exact_code' => $qry_exact[0]['exact_code'],
									'exact_name' => $qry_exact[0]['exact_name'],
									'judul'      => htmlspecialchars_decode($qry_exact[0]['marment_judul']),
									'deskripsi'  => str_replace(['<br />', '<br>'], "", htmlspecialchars_decode($qry_exact[0]['marment_deskripsi'])),
									'spek'       => [
										'koli'            => $qry_exact[0]['koli'],
										'total_nw'        => $qry_exact[0]['total_nw'],
										'total_gw'        => $qry_exact[0]['total_gw'],
										'nama_warna'      => $qry_exact[0]['nama_warna'],
										'nama_finishing'  => $qry_exact[0]['nama_finishing'],
										'dimensi_panjang' => $qry_exact[0]['dimensi_panjang']/10,
										'dimensi_lebar'   => $qry_exact[0]['dimensi_lebar']/10,
										'dimensi_tinggi'  => $qry_exact[0]['dimensi_tinggi']/10,
										'box1_panjang'    => $qry_exact[0]['box1_panjang']/10,
										'box1_lebar'      => $qry_exact[0]['box1_lebar']/10,
										'box1_tinggi'     => $qry_exact[0]['box1_tinggi']/10,
										'box2_panjang'    => $qry_exact[0]['box2_panjang']/10,
										'box2_lebar'      => $qry_exact[0]['box2_lebar']/10,
										'box2_tinggi'     => $qry_exact[0]['box2_tinggi']/10
									]
								];
							}
						}

						$this->output->set_header('Access-Control-Allow-Origin: *', false);
						$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($data));
					break;
				}
			break;

			case 'marplace':
				$data['data'] = array();

				$qry = $this->select->get('detail_marketplace', $b);

				if ($qry AND count($qry) > 0) {
					foreach($qry as $row) {
						$data['data'][] = array(
							'id' 		  => $row['id'],
							'marketplace' => $row['marketplace'],
							'email'       => $row['email'],
							'username'    => $row['username'],
							'password'    => $row['password'],
							'login'       => $row['login'],
							'catatan'     => htmlspecialchars_decode($row['catatan']),
							'ppn'         => $row['ppn'],
							'fee'         => $row['fee'],
							'fee_type'    => $row['fee_type']
						);
					}
				}

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($data));
			break;

			case 'stats':
				$fil_mplace = $this->input->get('fil_mplace');
				$fil_status = $this->input->get('fil_status');
				$fil_range  = $this->input->get('fil_range');
				if ($fil_mplace OR is_numeric($fil_status) OR $fil_range) {
					$filter['mplace'] = $fil_mplace;
					$filter['status'] = $fil_status;
					$filter['range']  = (strlen($fil_range) > 0) ? explode(',', $fil_range) : "";
					$filter_get = "?fil_mplace={$fil_mplace}&fil_status={$fil_status}&fil_range={$fil_range}";
				}

				// produk terlaris
					$data['paling_dibeli'] = [];
					$qry = $this->select->get('stat_paling_dibeli', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['paling_dibeli'][] = [
								$row['exact_code'],
								"<span title='{$row['exact_name']}'>".substr($row['exact_name'], 0, 22)."...</span>",
								$row['count']
							];
						}
					}

				// detail biaya
					// FEE
						$data['fee'] = [];
						$qry = $this->select->get('stat_fee_marketplace', $filter);
						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								$data['fee'][] = is_null($row['total']) ? 0 : $row['total'];
							}
						}
					// VOUCHER
						$data['voucher'] = [];
						$qry = $this->select->get('stat_voucher', $filter);
						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								$data['voucher'][] = is_null($row['total']) ? 0 : $row['total'];
							}
						}
					// ONGKIR
						$data['ongkir'] = [];
						$qry = $this->select->get('stat_biaya_kirim', $filter);
						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								$data['ongkir'][] = is_null($row['total']) ? 0 : $row['total'];
							}
						}
					// IKLAN
						$data['iklan'] = [];
						$qry = $this->select->get('stat_biaya_iklan', $filter);
						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								$data['iklan'][] = is_null($row['total']) ? 0 : $row['total'];
							}
						}
					// PPn
						$data['ppn'] = [];
						$qry = $this->select->get('stat_ppn', $filter);
						if ($qry AND count($qry) > 0) {
							foreach($qry as $row) {
								$data['ppn'][] = is_null($row['total']) ? 0 : $row['total'];
							}
						}

				// summary
					$data['summary'] = [];
					$qry = $this->select->get('stat_summary', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['summary'][] = [
								'total_gross'     => (int) $row['gross'],
								'total_transaksi' => (int) $row['transaksi_masuk'],
								'total_netto'     => (int) $row['netto'] - $data['iklan'][0]
							];
						}
					}

					$data['summary_hpp'] = [];
					$qry = $this->select->get('stat_summary_hpp', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {

							$m_pusat  = $data['summary'][0]['total_netto']-$row['hpp_pusat_total'];
							$m_cabang = $data['summary'][0]['total_netto']-$row['hpp_cabang_total'];

							$per_m_pusat = 0;
							$per_m_cabang = 0;
							if ($m_pusat != 0 && $m_cabang != 0 && $data['summary'][0]['total_netto'] != 0) {
								$per_m_pusat  = number_format(($m_pusat / $data['summary'][0]['total_netto']) * 100).'%';
								$per_m_cabang = number_format(($m_cabang / $data['summary'][0]['total_netto']) * 100).'%';
							}

							$data['summary_hpp'][] = [
								'hpp_pusat'  => (int) $m_pusat,
								'hpp_cabang' => (int) $m_cabang,
								'per_pusat'  => $per_m_pusat,
								'per_cabang' => $per_m_cabang
							];

						}
					}

				// origin buyer
					$data['origin_provinsi'] = [];
					$qry = $this->select->get('stat_location_provinsi', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['origin_provinsi'][] = [
								$row['buyer_provinsi'],
								$row['count']
							];
						}
					}
					$data['origin_kota'] = [];
					$qry = $this->select->get('stat_location_kota', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['origin_kota'][] = [
								$row['buyer_kota'],
								$row['count']
							];
						}
					}

				// fee tertinggi
					$data['fee_sort'] = [];
					$qry = $this->select->get('sort_fee', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['fee_sort'][] = [
								$row['marketplace'],
								$row['fee']
							];
						}
					}

				// voucher tertinggi
					$data['voucher_sort'] = [];
					$qry = $this->select->get('sort_voucher', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['voucher_sort'][] = [
								$row['marketplace'],
								$row['voucher']
							];
						}
					}

				// ongkir tertinggi
					$data['ongkir_sort'] = [];
					$qry = $this->select->get('sort_ongkir', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['ongkir_sort'][] = [
								$row['marketplace'],
								$row['ongkir']
							];
						}
					}

				// iklan tertinggi
					$data['iklan_sort'] = [];
					$qry = $this->select->get('sort_iklan', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['iklan_sort'][] = [
								$row['marketplace'],
								$row['ammount']
							];
						}
					}

				// gross tertinggi
					$data['gross_sort'] = [];
					$qry = $this->select->get('sort_gross', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['gross_sort'][] = [
								$row['marketplace'],
								$row['gross']
							];
						}
					}

				// netto tertinggi
					$data['netto_sort'] = [];
					$qry = $this->select->get('sort_netto', $filter);
					if ($qry AND count($qry) > 0) {
						foreach($qry as $row) {
							$data['netto_sort'][] = [
								$row['marketplace'],
								is_null(searchForId($row['marketplace'], $data['iklan_sort'])) ? $row['netto'] : $row['netto']-searchForId($row['marketplace'], $data['iklan_sort'])
							];
						}
					}
				// 

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($data));
			break;

			case 'invoice':
				switch($b) {
					case 'list':
						switch($_SESSION['level_os']) {
							case 1: // PUSAT
								$fil_mplace = $this->input->get('fil_mplace');
								$fil_status = $this->input->get('fil_status');
								$fil_range  = $this->input->get('fil_range');
								if ($fil_mplace OR is_numeric($fil_status) OR $fil_range) {
									$filter['mplace'] = $fil_mplace;
									$filter['status'] = $fil_status;
									$filter['range']  = (strlen($fil_range) > 0) ? explode(',', $fil_range) : "";
									$filter_get = "?fil_mplace={$fil_mplace}&fil_status={$fil_status}&fil_range={$fil_range}";
								}
				
								$data['data'] = [];
								$qry = $this->select->get('list_invoice', $filter);
								if ($qry AND count($qry) > 0) {
									foreach($qry as $row) {
										
										$uri_view = base_url('req/get/invoice/detail/?fil_invoice='.$row['id']);

										$uri_update_resifaktur = base_url('req/put/resifaktur/'.$row['id'].'/'.$filter_get);

										$status_ongkir = "";
										if ($row['biaya_kirim'] == 0) {
											$status_ongkir = "text-danger";
										}

										$m_pusat = $row['total_netto']-$row['hpp_pusat_total'];
										$m_cabang = $row['total_netto']-$row['hpp_cabang_total'];

										$per_m_pusat = 0;
										$per_m_cabang = 0;
										if ($m_pusat != 0 && $m_cabang != 0 && $row['total_netto'] != 0) {
											$per_m_pusat  = number_format((($m_pusat) / $row['total_netto']) * 100);
											$per_m_cabang = number_format((($m_cabang) / $row['total_netto']) * 100);
										}

										$data['data'][] = [
											type_data('invoice_status', $row['status']),
											$row['marketplace'],
											$row['no_faktur'],
											number_format($row['total_harga']+$row['potongan_harga']+$row['fee_marketplace']),
											number_format($row['total_harga']),
											number_format($row['total_netto']),
											number_format($m_pusat)." <sup class='text-default'><strong>". $per_m_pusat ."%</strong></sup>",
											number_format($m_cabang)." <sup class='text-default'><strong>". $per_m_cabang ."%</strong></sup>",
											"<span data-toggle='tooltip' data-placement='top' title='".$row['buyer_email']."'>".substr($row['buyer_email'], 0, 10)."...</span>",
											$row['buyer_nama'],
											number_format($row['biaya_kirim']),
											"<span data-toggle='tooltip' data-placement='top' title='".$row['tipe_produk']."'>".substr($row['tipe_produk'], 0, 10)."...</span>",
											"<span data-toggle='tooltip' data-placement='top' title='".$row['info_kurir']."' class='mr-1 {$status_ongkir}'>".$row['info_resi']."</span>",
											$row['waktu_beli'],
											"<div class='item-action dropdown'>
												<a href='javascript:void(0)' data-toggle='dropdown' class='icon' aria-expanded='false'><i class='fe fe-more-vertical'></i></a>
												<div class='dropdown-menu dropdown-menu-right' x-placement='bottom-end'>
													<a href='javascript:void(0)' onclick='view_invoice(`{$uri_view}`)' class='dropdown-item'><i class='dropdown-icon fe fe-clipboard'></i> Quick View</a>
													<a href='".base_url('route/invoice/view/'.$row['id'].'/'.$filter_get)."' class='dropdown-item'><i class='dropdown-icon fe fe-edit'></i> Detail</a>
													<a href='".base_url('route/invoice/label/'.$row['id'])."' target='_blank' class='dropdown-item'><i class='dropdown-icon fe fe-printer'></i> Cetak Label</a>
													<div class='dropdown-divider'></div>
													<a href='javascript:void(0)' onclick='view_resifaktur(`{$uri_view}`, `{$uri_update_resifaktur}`)' class='dropdown-item'><i class='dropdown-icon fe fe-edit-2'></i> Resi &amp; Faktur</a>
													<a href='".base_url('req/put/status/'.$row['id'].'/'.$filter_get)."' class='dropdown-item'><i class='dropdown-icon fe fe-check'></i> Atur Selesai</a>
													<div class='dropdown-divider'></div>
													<a href='javascript:void(0);' onclick='redirect(`".base_url('req/delete/invoice/'.$row['id'].'/'.$filter_get)."`)' class='dropdown-item'><i class='dropdown-icon fe fe-trash'></i> Hapus</a>
												</div>
											</div>"
										];
									}
								}
				
								$this->output->set_header('Access-Control-Allow-Origin: *', false);
								$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
								$this->output->set_content_type('application/json');
								$this->output->set_output(json_encode($data));
							break;
							case 5: // VIEW
								$fil_mplace = $this->input->get('fil_mplace');
								$fil_status = $this->input->get('fil_status');
								$fil_range  = $this->input->get('fil_range');
								if ($fil_mplace OR is_numeric($fil_status) OR $fil_range) {
									$filter['mplace'] = $fil_mplace;
									$filter['status'] = $fil_status;
									$filter['range']  = (strlen($fil_range) > 0) ? explode(',', $fil_range) : "";
									$filter_get = "?fil_mplace={$fil_mplace}&fil_status={$fil_status}&fil_range={$fil_range}";
								}
				
								$data['data'] = [];
								$qry = $this->select->get('list_invoice', $filter);
								if ($qry AND count($qry) > 0) {
									foreach($qry as $row) {
										$uri_view = base_url('req/get/invoice/detail/?fil_invoice='.$row['id']);
										$data['data'][] = [
											type_data('invoice_status', $row['status']),
											$row['marketplace'],
											$row['no_faktur'],
											$row['total_harga'],
											$row['buyer_nama']." (<span data-toggle='tooltip' data-placement='top' title='".$row['buyer_email']."'>".substr($row['buyer_email'], 0, 7)."...</span>)",
											"<span data-toggle='tooltip' data-placement='top' title='".$row['tipe_produk']."'>".substr($row['tipe_produk'], 0, 7)."...</span>",
											$row['info_resi'],
											$row['waktu_beli'],
											"<a href='javascript:void(0)' onclick='view_invoice(`{$uri_view}`)' class='icon'><i class='fe fe-clipboard'></i></a>&nbsp;&nbsp;<a href='".base_url('route/invoice/label/'.$row['id'])."' target='blank' class='icon'><i class='fe fe-printer'></i></a>"
										];
									}
								}
				
								$this->output->set_header('Access-Control-Allow-Origin: *', false);
								$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
								$this->output->set_content_type('application/json');
								$this->output->set_output(json_encode($data));
							break;
						}
					break;
					case 'detail':
						$data['data'] = [];
						$fil_invoice = $this->input->get('fil_invoice');

						$total_hpp = 0;

						$qry_2 = $this->select->get('list_invoice_item', $fil_invoice);
						if ($qry_2 AND count($qry_2) > 0) {
							foreach($qry_2 as $row) {
								$data['data']['item'][] = [
									'exact_code'   => $row['exact_code'],
									'exact_name'   => $row['exact_name'],
									'quantity'     => $row['quantity'],
									'harga_satuan' => $row['harga_satuan'],
									'harga_total'  => $row['harga_total'],
									'hpp_pusat'    => $row['hpp_pusat'] * $row['quantity'],
									'berat'        => $row['berat'],
									'catatan'      => $row['catatan']
								];
								$total_hpp += $row['hpp_pusat'] * $row['quantity'];
							}
						}

						$qry_1 = $this->select->get('detail_invoice', $fil_invoice);
						if ($qry_1 AND count($qry_1) > 0) {
							foreach($qry_1 as $row) {
								$data['data']['detail'][] = [
									'no_faktur'       => $row['no_faktur'],
									'info_kurir' 	  => $row['info_kurir'],
									'info_resi' 	  => $row['info_resi'],
									'info_catatan' 	  => htmlspecialchars_decode($row['info_catatan']),
									'waktu_beli'      => $row['waktu_beli'],
									'buyer_nama'      => $row['buyer_nama'],
									'buyer_email'     => $row['buyer_email'],
									'buyer_telepon'   => $row['buyer_telepon'],
									'buyer_alamat'    => htmlspecialchars_decode($row['buyer_alamat']),
									'buyer_kota'      => $row['buyer_kota'],
									'buyer_provinsi'  => $row['buyer_provinsi'],
									'buyer_catatan'   => htmlspecialchars_decode($row['buyer_catatan']),
									'marketplace'     => $row['marketplace'],
									'potongan_harga'  => $row['potongan_harga'],
									'fee_marketplace' => $row['fee_marketplace'],
									'ppn' 			  => $row['ppn'],
									'biaya_kirim' 	  => $row['biaya_kirim'],
									'total_item'      => $row['total_item'],
									'total_berat'     => $row['total_berat'],
									'total_harga'     => $row['total_harga'],
									'total_netto' 	  => $row['total_netto'],
									'hpp_pusat' 	  => $total_hpp,
									'total_mrg_pusat' => $row['total_netto']-$total_hpp,
									'bukti_invoice'   => base_url('assets/u/'.$row['bukti_invoice'])
								];
							}
						}

						$this->output->set_header('Access-Control-Allow-Origin: *', false);
						$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($data));
					break;
				}
			break;

			case 'excel':
				switch($b) {
					case 'dashboard':
						require APPPATH.'third_party/vendor/autoload.php';

						$qry = $this->select->get('list_produk');
						if ($qry AND count($qry) > 0) {

							$header = [
								'EXACT CODE' 	=> 'string',
								'EXACT NAME' 	=> 'string',
								'HPP'        	=> 'integer',
								'ONGKIR'     	=> 'integer',
								'HARGA JUAL' 	=> 'integer',
								'GP'         	=> 'float'
							];


							$qry_list_marketplace = $this->select->get('list_marketplace');
							foreach ($qry_list_marketplace as $qlm) {
								$header[strtoupper($qlm['marketplace'])] = 'integer';
							}


							$wExcel = new Ellumilel\ExcelWriter();
							$wExcel->writeSheetHeader('Marketplace', $header);
							$wExcel->setAuthor('Reno Fizaldy');


							foreach($qry as $row) {

								$lst_stok = [];
								$qry_stok = $this->select->get('list_stok_grup_marketplace', $row['id']);

								// IF STOK EXIST
								if ($qry_stok AND count($qry_stok) > 0) {
									foreach($qry_stok as $qs) {
										$lst_stok[$qs['idm']] = $qs['stok']; 
									}
								} 
								// IF NONE
								else {
									foreach($qry_list_marketplace as $qlm) {
										$lst_stok[$qlm['id']] = 0;
									}
								}

								$xls_content = [
									$row['exact_code'],
									$row['exact_name'],
									$row['hpp_pusat'],
									$row['ongkir'],
									$row['harga_netto'],
									(($row['harga_netto']-$row['hpp_pusat'])/$row['harga_netto'])
								];

								$wExcel->writeSheetRow('Marketplace', array_merge($xls_content, $lst_stok));

							}


							$qry_xls = $wExcel->writeToFile("./down/stok_marketplace.xlsx");
							redirect(base_url('down/stok_marketplace.xlsx'));
						} else {
							echo "<b>Terjadi Kesalahan 01</b>: Mohon hubungi administrator untuk perbaikan.";
							exit();
						}
					break;
					case 'invoice':
						$filter     = [];
						$fil_mplace = $this->input->get('fil_mplace');
						$fil_status = $this->input->get('fil_status');
						$fil_range  = $this->input->get('fil_range');
						$fil_excel  = $this->input->get('fil_excel');
						if ($fil_mplace OR is_numeric($fil_status) OR $fil_range) {
							$filter['mplace'] = $fil_mplace;
							$filter['status'] = $fil_status;
							$filter['range']  = (strlen($fil_range) > 0) ? explode(',', $fil_range) : "";
						}

						switch($fil_excel) {
							case 'extended':
								require APPPATH.'third_party/vendor/autoload.php';
								$wExcel = new Ellumilel\ExcelWriter();
								$wExcel->setAuthor('Reno Fizaldy');

								$qry = $this->select->get('list_invoice', $filter);
								if ($qry AND count($qry) > 0) {
									$date = date('d-M-Y');

									$header = [
										'TGL BELI'         => 'datetime',
										'NO RESI'          => 'string',
										'FAKTUR'           => 'string',
										'MARKET'           => 'string',
										'BUYER'            => 'string',
										'ALAMAT'           => 'string',
										'KOTA'             => 'string',
										'PROVINSI'         => 'string',
										'EXACT CODE'       => 'string',
										'EXACT NAME'       => 'string',
										'GW'               => 'integer',
										'BUDGET ONGKIR'    => 'integer',
										'QTY'              => 'integer',
										'HPP PUSAT'        => 'integer',
										'TOTAL HPP'        => 'integer',
										'HARGA'            => 'integer',
										'TOTAL HARGA'      => 'integer',
										'POTONGAN/VOUCHER' => 'integer',
										'FEE'              => 'integer',
										'TOTAL BAYAR'      => 'integer',
										'ONGKIR'           => 'integer',
										'PPn'              => 'integer',
										'TOTAL NETTO'      => 'integer',
										'MARGIN'           => 'integer',
										'GAP'              => 'integer',
										'NOTE'             => 'string'
									];
									$wExcel->writeSheetHeader('Marketplace', $header);

									foreach($qry as $row) {
										$xls_content = [
											$row['waktu_beli'],
											$row['info_resi'],
											$row['no_faktur'],
											$row['marketplace'],
											$row['buyer_nama']." (".$row['buyer_email'].")",
											$row['buyer_alamat'],
											$row['buyer_kota'],
											$row['buyer_provinsi'],
											($row['total_item'] > 1) ? "-" : $row['exact_produk'],
											($row['total_item'] > 1) ? "-" : $row['tipe_produk'],
											$row['total_berat'],
											"-",
											$row['total_item'],
											$row['hpp_pusat_total'],
											"-",
											"-",
											$row['harga_produk'],
											$row['potongan_harga'],
											$row['fee_marketplace'],
											$row['total_harga'],
											$row['biaya_kirim'],
											$row['ppn'],
											$row['total_netto'],
											$row['total_netto']-$row['hpp_pusat_total'],
											(($row['total_netto']-$row['hpp_pusat_total'])/$row['total_netto'])*100,
											htmlspecialchars_decode($row['info_catatan'])
										];
										$wExcel->writeSheetRow('Marketplace', $xls_content);

										if ($row['total_item'] > 1) {
											$qry_child = $this->select->get('list_invoice_item', $row['id']);
											if ($qry_child AND count($qry_child) > 0) {
												foreach($qry_child as $row_child) {


													$xls_content = [
														$row['waktu_beli'],
														"-",
														$row['no_faktur'],
														"-",
														"-",
														"-",
														"-",
														"-",
														$row_child['exact_code'],
														$row_child['exact_name'],
														$row_child['berat'],
														$row_child['ongkir'],
														$row_child['quantity'],
														$row_child['hpp_pusat'],
														($row_child['hpp_pusat'] * $row_child['quantity']),
														$row_child['harga_satuan'],
														($row_child['harga_satuan'] * $row_child['quantity']),
														"-",
														"-",
														"-",
														"-",
														"-",
														"-",
														"-",
														"-",
														""
													];
													$wExcel->writeSheetRow('Marketplace', $xls_content);
												}
											}
										}
									}

									$qry_xls = $wExcel->writeToFile("./down/marketplace-{$date}.xlsx");
									redirect(base_url("down/marketplace-{$date}.xlsx"));
								} else {
									echo "<b>Terjadi Kesalahan 01</b>: Mohon hubungi administrator untuk perbaikan.";
									exit();
								}
							break;
							case 'lite':
								require APPPATH.'third_party/vendor/autoload.php';
								$wExcel = new Ellumilel\ExcelWriter();
								$wExcel->setAuthor('Reno Fizaldy');

								$qry = $this->select->get('list_invoice', $filter);
								if ($qry AND count($qry) > 0) {
									$date = date('d-M-Y');

									$header = [
										'TGL BELI'         => 'datetime',
										'NO RESI' 		   => 'string',
										'FAKTUR'           => 'string',
										'MARKET'           => 'string',
										'BUYER'            => 'string',
										'ALAMAT'           => 'string',
										'KOTA'             => 'string',
										'PROVINSI'         => 'string',
										'EXACT CODE'       => 'string',
										'EXACT NAME'       => 'string',
										'QTY'              => 'integer',
										'HARGA'            => 'integer',
										'TOTAL HARGA'      => 'integer',
										'POTONGAN/VOUCHER' => 'integer',
										'FEE'              => 'integer',
										'TOTAL BAYAR'      => 'integer',
										'NOTE' 			   => 'string'
									];
									$wExcel->writeSheetHeader('Marketplace', $header);

									foreach($qry as $row) {
										$xls_content = [
											$row['waktu_beli'],
											$row['info_resi'],
											$row['no_faktur'],
											$row['marketplace'],
											$row['buyer_nama']." (".$row['buyer_email'].")",
											$row['buyer_alamat'],
											$row['buyer_kota'],
											$row['buyer_provinsi'],
											($row['total_item'] > 1) ? "-" : $row['exact_produk'],
											($row['total_item'] > 1) ? "-" : $row['tipe_produk'],
											$row['total_item'],
											"-",
											$row['harga_produk'],
											$row['potongan_harga'],
											$row['fee_marketplace'],
											$row['total_harga'],
											htmlspecialchars_decode($row['info_catatan'])
										];
										$wExcel->writeSheetRow('Marketplace', $xls_content);

										if ($row['total_item'] > 1) {
											$qry_child = $this->select->get('list_invoice_item', $row['id']);
											if ($qry_child AND count($qry_child) > 0) {
												foreach($qry_child as $row_child) {


													$xls_content = [
														$row['waktu_beli'],
														"-",
														$row['no_faktur'],
														"-",
														"-",
														"-",
														"-",
														"-",
														$row_child['exact_code'],
														$row_child['exact_name'],
														$row_child['quantity'],
														$row_child['harga_satuan'],
														($row_child['harga_satuan'] * $row_child['quantity']),
														"-",
														"-",
														"-",
														""
													];
													$wExcel->writeSheetRow('Marketplace', $xls_content);
												}
											}
										}
									}

									$qry_xls = $wExcel->writeToFile("./down/marketplace-{$date}.xlsx");
									redirect(base_url("down/marketplace-{$date}.xlsx"));
								} else {
									echo "<b>Terjadi Kesalahan 01</b>: Mohon hubungi administrator untuk perbaikan.";
									exit();
								}
							break;
						}
					break;
				}
			break;

			case 'wilayah':
				$type = $this->input->get('type');

				switch($type) {
					case 'prov':
						$qry = $this->select->get('list_province');
						if ($qry) {
							$data = array('status' => true, 'provinsi'=>$qry);
						}
					break;
					case 'kota':
						$prov = $this->input->get('prov');
						$qry = $this->select->get('list_city', $prov);
						if ($qry) {
							$data = array('status' => true, 'kota'=>$qry);
						}
					break;
					case 'kecamatan':
					break;
					case 'kelurahan':
					break;
					default:
					break;
				}

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($data));
			break;

			default:
			break;
		}
	}

	public function post($a=null, $b=null, $c=null) {
		session_start();
		if (!isset($_SESSION['level_os'])) {
			show_404();
			exit();
		}
		$this->config->load('setup');
		$this->load->model('insert');
		switch($a) {
			case 'produk':
				$qry = $this->insert->produk();
				alert($qry, null);
			break;
			case 'marplace':
				$qry = $this->insert->marplace();
				alert($qry, 'marplace');
			break;
			case 'invoice':
				$get_mplace = ($this->input->get('fil_mplace')) ? $this->input->get('fil_mplace') : 'all';
				$get_status = ($this->input->get('fil_status')) ? $this->input->get('fil_status') : 'all';
				$get_range  = ($this->input->get('fil_range')) ? $this->input->get('fil_range') : date('Y-m-01,Y-m-t');
				$filter_get = "?fil_mplace=all&fil_status=all&fil_range=".$get_range;
				if ($get_mplace OR is_numeric($get_status) OR $get_range) {
					$filter_get = "?fil_mplace={$get_mplace}&fil_status={$get_status}&fil_range={$get_range}";
				}

				$qry = $this->insert->invoice();
				alert($qry, 'invoice/'.$filter_get.'&clear_cart=true');
			break;
			case 'biaya_iklan':

				// $bi_marketplace = $this->input->post('marketplace');
				// $bi_biaya_iklan = $this->input->post('biaya_iklan');
				// $bi_tahun       = substr($this->input->post('bulan_tahun'), 0, 4);
				// $bi_bulan       = substr($this->input->post('bulan_tahun'), 5, 2);
				// print_r($check->result_array());
				// exit();
				$data['result'] = false;
				$qry = $this->insert->biaya_iklan();
				if ($qry) {
					$data['result'] = true;
				}
				
				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($data));
			break;
			case 'setup':
				$qry = $this->insert->setup($b, $c);
				alert($qry, 'route/setup/'.$b);
			break;
			default:
			break;
		}
	}

	public function put($a=null, $b=null) {
		// session_start();
		// if (!isset($_SESSION['level_os'])) {
		// 	show_404();
		// 	exit();
		// }
		$this->config->load('setup');
		$this->load->model('update');
		switch($a) {
			case 'stok':
				$qry = $this->update->stok();
				alert($qry, '');

				// echo (string) filter_input(INPUT_POST, 'id_stok');
			break;

			case 'status':
				$get_mplace = ($this->input->get('fil_mplace')) ? $this->input->get('fil_mplace') : 'all';
				$get_status = ($this->input->get('fil_status')) ? $this->input->get('fil_status') : 'all';
				$get_range  = ($this->input->get('fil_range')) ? $this->input->get('fil_range') : date('Y-m-01,Y-m-t');
				$filter_get = "?fil_mplace=all&fil_status=all&fil_range=".$get_range;
				if ($get_mplace OR is_numeric($get_status) OR $get_range) {
					$filter_get = "?fil_mplace={$get_mplace}&fil_status={$get_status}&fil_range={$get_range}";
				}

				$qry = $this->update->invoice_status($b);
				alert($qry, 'route/invoice/'.$filter_get);
			break;
			case 'resifaktur':
				$get_mplace = ($this->input->get('fil_mplace')) ? $this->input->get('fil_mplace') : 'all';
				$get_status = ($this->input->get('fil_status')) ? $this->input->get('fil_status') : 'all';
				$get_range  = ($this->input->get('fil_range')) ? $this->input->get('fil_range') : date('Y-m-01,Y-m-t');
				$filter_get = "?fil_mplace=all&fil_status=all&fil_range=".$get_range;
				if ($get_mplace OR is_numeric($get_status) OR $get_range) {
					$filter_get = "?fil_mplace={$get_mplace}&fil_status={$get_status}&fil_range={$get_range}";
				}

				$qry = $this->update->invoice_resifaktur($b);
				$res = ['result' => false];
				if ($qry) {
					$res = ['result' => true];
				}

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode(['result' => true]));
			break;

			case 'edit_info':
				$qry = $this->update->info();

				$res = ['result' => false];
				if ($qry) {
					$res = ['result' => true];
				}

				$this->output->set_header('Access-Control-Allow-Origin: *', false);
				$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode(['result' => true]));
			break;
			case 'detail':
				$qry = $this->update->detail();
				alert($qry, '');
			break;
			case 'marplace':
				$qry = $this->update->marplace();
				alert($qry, 'marplace');
			break;
			case 'invoice':
				$get_mplace = ($this->input->get('fil_mplace')) ? $this->input->get('fil_mplace') : 'all';
				$get_status = ($this->input->get('fil_status')) ? $this->input->get('fil_status') : 'all';
				$get_range  = ($this->input->get('fil_range')) ? $this->input->get('fil_range') : date('Y-m-01,Y-m-t');
				$filter_get = "?fil_mplace=all&fil_status=all&fil_range=".$get_range;
				if ($get_mplace OR is_numeric($get_status) OR $get_range) {
					$filter_get = "?fil_mplace={$get_mplace}&fil_status={$get_status}&fil_range={$get_range}";
				}

				$qry = $this->update->invoice();
				alert($qry, 'invoice'.$filter_get);
			break;
			default:
			break;
		}
	}

	public function delete($a=null, $b=null) {
		session_start();
		if (!isset($_SESSION['level_os'])) {
			show_404();
			exit();
		}
		$this->config->load('setup');
		$this->load->model('delete');

		switch($a) {
			case 'invoice_img':
				$idx = $this->input->get('id');
				$file_name = $this->input->get('file_name');

				$qry = $this->delete->invoice('bukti', ['id' => $idx, 'file' => $file_name]);
				alert($qry, 'route/invoice/view/'.$idx);
			break;
			case 'invoice':
				$get_mplace = ($this->input->get('fil_mplace')) ? $this->input->get('fil_mplace') : 'all';
				$get_status = ($this->input->get('fil_status')) ? $this->input->get('fil_status') : 'all';
				$get_range  = ($this->input->get('fil_range')) ? $this->input->get('fil_range') : date('Y-m-01,Y-m-t');
				$filter_get = "?fil_mplace=all&fil_status=all&fil_range=".$get_range;
				if ($get_mplace OR is_numeric($get_status) OR $get_range) {
					$filter_get = "?fil_mplace={$get_mplace}&fil_status={$get_status}&fil_range={$get_range}";
				}

				$qry = $this->delete->invoice(null, $b);
				alert($qry, 'route/invoice/'.$filter_get);
			break;
			default:
			break;
		}
	}

}
