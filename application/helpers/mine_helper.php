<?php
function searchForId($id, $array) {
	foreach ($array as $key => $val) {
		if ($val[0] === $id) {
			return $val[1];
		}
	} return null;
}

// SYSTEM REPLACED
	if (!function_exists('view')) {
		function view($uri, $dat=null) {
			$ci =& get_instance();
			$ci->config->load('setup');
			$ci->load->model('select');

			$dat['site_name'] = " | ".$ci->config->item('site_name');

			$dat['assets'] = base_url($ci->config->item('assets'));

			$ci->load->view($ci->config->item('theme').$uri, $dat);
		}
	}
// 

// USER
	if (!function_exists('auth')) {
		function auth($perm, $red='') {
			$ci =& get_instance();
			$ci->load->library('Aauth');

			if ($ci->aauth->is_loggedin()) {
				switch($perm) {
					case 'login':
						redirect(base_url());
					break;
					case 'home':
						return true;
					break;
					default:
						$user = $ci->aauth->get_user_id();
						if ($ci->aauth->is_allowed($perm)) {
							return true;
						} else {
							redirect(base_url());
						}
					break;
				}
			} else {
				switch($perm) {
					case 'login':
						return true;
					break;
					default:
						redirect(base_url('login'));
						exit();
					break;
				}
			}
		}
	}
	if (!function_exists('alert')) {
		function alert($one, $two, $three=null) {
			$ci =& get_instance();
			$ci->load->library('session');
			if ($one) {
				$msg = "success";
				if (!is_null($three)) {
					$msg = $three;
				}
				$ci->session->set_flashdata('alert', $msg);
				redirect(base_url($two));
			} else {
				$msg = "failure";
				if (!is_null($three)) {
					$msg = $three;
				}
				$ci->session->set_flashdata('alert', $msg);
				redirect(base_url($two));
			}
		}
	}
// 

// STRING
	if (!function_exists('cleanInput')) {
		function cleanInput($str, $minify=null) {
			$ci =& get_instance();
			$ci->load->helper('security');
			if (isset($minify)) {
				if (is_array($str)) {
					foreach($str as $key=>$val) {
						$minify = html_escape(trim($ci->security->xss_clean(strip_tags($val))));
						$firsts = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify);
						$result = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $firsts);
						$str[$key] = $result;
					} return $str;
				} else {
					$minify = html_escape(trim($ci->security->xss_clean(strip_tags($str))));
					$firsts = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify);
					$result = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $firsts);
					return $result;
				}
			} else {
				if (is_array($str)) {
					foreach($str as $key=>$val) {
						$str[$key] = html_escape($ci->security->xss_clean(trim($val)));
					}
					return $str;
				} else {
					return html_escape($ci->security->xss_clean(trim($str)));
				}
			}
		}
	}
	if (!function_exists('remEmptyArray')) {
		function remEmptyArray($array){
			foreach ($array as $key => $value) {
				if ($key === null || $key === '') { 
					unset($array[$key]);
				}
				else if ($value === null || $value === '') { 
					unset($array[$key]);
				}
			} return $array;
		}
	}
	if (!function_exists('searchInArray')) {
		function searchInArray($search, $array, $key) {
			foreach ($array as $k => $v) {
				if ($v[$key] == $search) {
					return true;
				}
			}
			return null;
		}
	}
	if (!function_exists('remKeyNotExist')) {
		function remKeyNotExist($array, $key) {
			foreach($array as $k=>$v) {
				if (!array_key_exists($key, $v)) {
					unset($array[$k]);
				} 
			} return $array;
		}
	}
	if (!function_exists('get_file_size')) {
		function get_file_size($uri) {
			$ch = curl_init($uri);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_NOBODY, TRUE);

			$data = curl_exec($ch);
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

			curl_close($ch);
			return $size;
		}
	}
	if (!function_exists('isJson')) {
		function isJson($string) {
			json_decode($string);
			return (json_last_error() == JSON_ERROR_NONE);
		}
	}
// 

// DATE TIME TRANSLATE
	if (!function_exists('timepub')) {
		function timepub($date, $format=null) {
			$hari    = "D";
			$tanggal = "j M Y";
			$bulan   = "M";
			$waktu   = "H:i";

			switch($format) {
				case 'Y/M/D':
					return date("Y/m/d - H:i", strtotime($date));
				break;
				case 'Y-M-D':
					return date("Y-m-d", strtotime($date));
				break;
				case 'sql':
					return date("Y-m-d H:i:s", strtotime($date));
				break;
				case 'formal':
					return timepub_trans($hari, $date)." tanggal ".timepub_trans($tanggal, $date)." Jam ".date($waktu)." WIB";
				break;
				case 'tanggal':
					return timepub_trans($tanggal, $date);
				break;
				default:
					return timepub_trans($hari, $date).", ".timepub_trans($tanggal, $date)." - ".timepub_trans($waktu, $date);
				break;
			}
		}
	}
	if (!function_exists('timepub_trans')) {
		function timepub_trans($format, $nilai) {
			if ($nilai == '0000-00-00' or $nilai == NULL) {
				return NULL;
			} else {
				$en = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
				$id = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
				return str_replace($en, $id, date($format, strtotime($nilai)));
			}
		}
	}
	if (!function_exists('getDaysInYearMonth')) {
		function getDaysInYearMonth(int $year, int $month, string $format){
			$date = DateTime::createFromFormat("Y-n", "$year-$month");
		
			$datesArray = array();
			for($i=1; $i<=$date->format("t"); $i++){
				$datesArray[] = DateTime::createFromFormat("Y-n-d", "$year-$month-$i")->format($format);
			}
			
			return $datesArray;
		}
	}
// 

// WRAP THE TEXT
	if (!function_exists('html2txt')) {
		function html2txt($document){
			$search = array(
				'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
				'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
			);
			$text = preg_replace($search, '', $document);
			return $text;
		}
	}
	if (!function_exists('scontent')) {
		function scontent($str, $limit) {
			return preg_replace('/\s+?(\S+)?$/', '', html2txt(substr(htmlspecialchars_decode($str), 0, $limit)));
		}
	}
// 

// UPLOADER
	if (!function_exists('uploader')) {
		function uploader($type, $param=array()) {
			$ci =& get_instance();
			switch($type) {
				case 'excel':
					/* array(
						'file' => input name,
						'name' => custom file name
					);*/

					$file = $param['file'];

					if (array_key_exists('name', $param)) {
						$config['file_name'] = $param['name'];
					}

					$config['upload_path']   = './uploads';
					$config['allowed_types'] = 'xls|xlsx';

					$ci->load->library('upload', $config);

					if ($ci->upload->do_upload($file)) {
						$data = array(
							'upload_data' => $ci->upload->data(),
							'field'       => $file,
							'files'       => $_FILES[$file]
						);

						return $data['upload_data']['file_name'];
					} return false;
				break;
				case 'native':
					/* array(
						'file' => input name,
						'name' => custom file name
					);*/

					$ci->load->library('upload');
					$ci->load->library('image_lib');

					$file = $param['file'];

					if (array_key_exists('name', $param)) {
						$config['file_name'] = $param['name'];
					}

					switch($file) {
						default:
							$config['upload_path']   = './assets/u/';
							$config['allowed_types'] = 'jpg|png|jpeg|bmp';
						break;
					}
					$ci->upload->initialize($config);

					if ($_FILES[$file]['error'] == 0) {
						if ($ci->upload->do_upload($file)) {
							$data = array(
								'upload_data' => $ci->upload->data(),
								'field'       => $file,
								'files'       => $_FILES[$file]
							);

							$config['image_library'] = 'gd2';							
							$config['source_image']  = './assets/u/'.$data['upload_data']['file_name'];
							$ci->image_lib->initialize($config);

							$ci->image_lib->clear();

							return $data['upload_data']['file_name'];
						} return false;
					} return false;
				break;
				case 'nativex':
					/* array(
						'file' => input name,
						'name' => custom file name
					);*/
					$ci->load->library('upload');
					$ci->load->library('image_lib');

					$config['file_name']  	 = $param['filex'];
					$config['upload_path']   = './assets/u/';
					$config['allowed_types'] = 'jpg|png|jpeg|bmp|gif|webp';
					$ci->upload->initialize($config);

					if ($param['filex']['error'] == 0) {
						if ($ci->upload->do_upload(false, $param['filex'])) {
							$upload_data = $ci->upload->data('filex', $param);
							$ci->image_lib->clear();

							return true;
						} return false;
					}
				break;
				case 'gcloud':
					/* array(
						'type' 	 => 'upload or delete',
						'folder' => 'folder_name',
						'file'   => 'file_name'
					); */
					$ci->load->library('Google_cloudstorage');
					switch($param['type']) {
						case 'upload':
							return $ci->google_cloudstorage->upload(
									$param['file'], 
									$param['folder'], 
									array(
										'allow_type' => $param['allow_type'],
										'max_size'   => $param['max_size']
									)
								);
						break;
						case 'delete':
							return false;
						break;
					}
				break;
				case 'cloudinary':
					/* array(
						'type' => 'upload or delete',
						'tags' => 'pure',
						'file' => $file
					); */
					$ci->load->library('CloudinaryLib');
					switch($param['type']) {
						case 'upload':
							switch($param['tags']) {
								case 'pure':
									$options = array(
										'tags'	      	=> 'pure',
										'width'       	=> 800,
										'height'      	=> 450,
										'aspect_ratio'	=> "16:9",
										'crop'        	=> "fill",
										'quality' 		=> "auto"
									);
								break;
								default:
									return false;
								break;
							} 
							return $ci->cloudinarylib->upload($param['file'], $options);
						break;
						case 'delete':
							$delete = $ci->cloudinarylib->delete($param['file']);
							if ($delete) {
								return true;
							} return false;
						break;
						default:
							return false;
						break;
					}
				break;
			}
		}
	}
	if (!function_exists('fuFilter')) {
		function fuFilter($name, $type=null, $ip=null) {
			switch($type) {
				case 'file_list':
					// IP ADDRESS
					$ipad = "0-0-0-0";
					if (!is_null($ip) && $ip !== "::1") {
						$ipad = str_replace(".", "-", $ip);
					}

					// SORT FILE
					$resup = json_decode($_POST['fileuploader-list-'.$name], true);

					// GAMBAR
					$gambar = $_FILES[$name];
					$result = array();

					// MAKE IT AS NEW ARRAY
					for ($i=0; $i<count($gambar['name']); $i++) {
						$time = time();
						$size = $gambar['size'][$i];
						$exts = pathinfo($gambar['name'][$i], PATHINFO_EXTENSION);
						$rand = mt_rand(10000000, 99999999).'.'.strtolower($exts);
						$result[$i] = array(
							'name_bfr' => $gambar['name'][$i],
							'name' 	   => $time.'_'.$ipad.'_'.$size.'_'.$rand,
							'type'     => $gambar['type'][$i],
							'tmp_name' => $gambar['tmp_name'][$i],
							'error'    => $gambar['error'][$i],
							'size'     => $gambar['size'][$i],
							'sort' 	   => fuSortFinder($resup, $gambar['name'][$i])
						);
					}

					// REMOVE EMPTY ARRAY
					if (empty($result[count($result)-1]['name_bfr'])) {
						unset($result[count($result)-1]);
					}

					// RE-ORDER ARRAY KEY
					usort($result, function($a, $b) {
						return $a['sort'] - $b['sort'];
					});

					return $result;
				break;
				default:
					// IP ADDRESS
					$ipad = "0-0-0-0";
					if (!is_null($ip) && $ip !== "::1") {
						$ipad = str_replace(".", "-", $ip);
					}

					// GAMBAR
					$gambar = $_FILES[$name];
					$result = array();

					// MAKE IT AS NEW ARRAY
					$time = time();
					$size = $gambar['size'];
					$exts = pathinfo($gambar['name'], PATHINFO_EXTENSION);
					$rand = mt_rand(10000000, 99999999).'.'.strtolower($exts);
					$result[] = array(
						'name_bfr' => $gambar['name'],
						'name' 	   => $time.'_'.$ipad.'_'.$size.'_'.$rand,
						'type'     => $gambar['type'],
						'tmp_name' => $gambar['tmp_name'],
						'error'    => $gambar['error'],
						'size'     => $gambar['size']
					);

					return $result;
				break;
			}
		}
	}
	if (!function_exists('fuSortFinder')) {
		function fuSortFinder($array, $file) {
			for ($i=0; $i<count($array); $i++) {
				$file_name = substr($array[$i]['file'], 3);
				if ($file_name == $file) {
					return $array[$i]['index'];
					break;
				}
			}
		}
	}
// 

// TYPE
	if (!function_exists('type_data')) {
		function type_data($type, $data) {
			switch($type) {
				case 'status':
					switch($data) {
						case 1:
							return "<span class=\"status-icon bg-success\"></span> Aktif";
						break;
						case 2:
							return "<span class=\"status-icon bg-danger\"></span> Tidak Aktif";
						break;
					}
				break;
				case 'status_on_detail':
					switch($data) {
						case 1:
							return "<span class=\"tag bg-success text-white\">Aktif</span>";
						break;
						case 2:
							return "<span class=\"tag bg-danger text-white\">Tidak Aktif</span>";
						break;
					}
				break;
				case 'check_no':
					if (!empty($data)) {
						return "<span style=\"display:none\">Y</span><span class=\"text-success\"><i class=\"fe fe-check\"></i></span>";
					} else {
						return "<span style=\"display:none\">N</span><span class=\"text-danger\"><i class=\"fe fe-x\"></i></span>";
					}
				break;
				case 'invoice_status':
					switch($data) {
						case 0:
							return "<span class=\"status-icon bg-secondary\"></span> Menunggu";
						break;
						case 1:
							return "<span class=\"status-icon bg-pink\"></span> Diproses";
						break;
						case 2:
							return "<span class=\"status-icon bg-orange\"></span> Dikirim";
						break;
						case 3:
							return "<span class=\"status-icon bg-lime\"></span> Selesai";
						break;
					}
				break;
				case 'invoice_status_lite':
					switch($data) {
						case 0:
							return "Menunggu";
						break;
						case 1:
							return "Diproses";
						break;
						case 2:
							return "Dikirim";
						break;
						case 3:
							return "Selesai";
						break;
					}
				break;
			}
		}
	}
	if (!function_exists('set_status')) {
		function set_status($tgl) {
			if (!empty($tgl['respon']) AND empty($tgl['rencana']) AND empty($tgl['realisasi']) AND empty($tgl['kirim'])) {
				return 2;
			} 
			else if (!empty($tgl['respon']) AND !empty($tgl['rencana']) AND empty($tgl['realisasi']) AND empty($tgl['kirim'])) {
				return 2;
			} 
			else if (!empty($tgl['respon']) AND !empty($tgl['rencana']) AND !empty($tgl['realisasi']) AND empty($tgl['kirim'])) {
				return 3;
			} 
			else if (!empty($tgl['respon']) AND !empty($tgl['rencana']) AND !empty($tgl['realisasi']) AND !empty($tgl['kirim'])) {
				return 4;
			} else {
				return 1;
			}
		}
	}
	if (!function_exists('check_stok_update')) {
		function check_stok_update() {
			
		}
	}
// 

