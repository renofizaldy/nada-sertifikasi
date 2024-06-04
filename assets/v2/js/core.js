let hexToRgba = function(hex, opacity) {
	let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	let rgb = result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	} : null;

	return 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + opacity + ')';
};

function comparing(id, obj) {
	for (i=0; i<obj.length; i++) {
		if (id == obj[i].marketplace) {
			return i;
		}
	}
}

function redirect(uri) {
	if(confirm('Lanjutkan tindakan ini?')) {
		window.location = uri;
	} else {
		return false;
	}
}

function submitBi(uri) {
	var formData = new FormData();
		formData.append('marketplace', $("[name='bi_marketplace']").val());
		formData.append('bulan_tahun', $("[name='bi_bulan_tahun']").val());
		formData.append('biaya_iklan', $("[name='bi_biaya_iklan']").val());

	$.ajax({
		url         : uri,
		data        : formData,
		type        : "POST",
		dataType    : "TEXT",
		contentType : false,
		cache       : false,
		processData : false,
		beforeSend  : function(){
			document.getElementById("bi_submit").disabled = true;
		}, success 	: function(data) {
			var res = JSON.parse(data);
			if (res.result == true) {
				$("#bi_alert").html(`<small class="text-success">Data berhasil disimpan, silahkan refresh halaman</small>`);
			} else {
				$("#bi_alert").html(`<small class="text-danger">Sepertinya telah terjadi kesalahan</small>`);
			}
		}, complete : function() {
			document.getElementById("bi_submit").disabled = false;
		}
	});
}

function submitRf() {
	var modal = "#view_resifaktur";
	var uri_update = $(modal + " [name='uri_update']").val();

	var formData = new FormData();
		formData.append('no_faktur', $("[name='no_faktur']").val());
		formData.append('info_resi', $("[name='info_resi']").val());
		formData.append('biaya_kirim', $("[name='biaya_kirim']").val());

	$.ajax({
		url         : uri_update,
		data        : formData,
		type        : "POST",
		dataType    : "TEXT",
		contentType : false,
		cache       : false,
		processData : false,
		beforeSend  : function(){
			document.getElementById("rf_submit").disabled = true;
		}, success 	: function(data) {
			var res = JSON.parse(data);
			console.log(res);
			if (res.result == true) {
				$(modal + " #rf_alert").html(`<small class="text-success">Data berhasil disimpan</small>`);
			} else {
				$(modal + " #rf_alert").html(`<small class="text-danger">Sepertinya telah terjadi kesalahan</small>`);
			}
		}, complete : function() {
			document.getElementById("rf_submit").disabled = false;
		}
	});
}

function submitMm(uri) {
	var formData = new FormData();
		formData.append('id_produk', $("[name='mm_id_produk']").val());
		formData.append('judul', $("[name='mm_judul']").val());
		formData.append('deskripsi', $("[name='mm_deskripsi']").val());

	$.ajax({
		url         : uri,
		data        : formData,
		type        : "POST",
		dataType    : "TEXT",
		contentType : false,
		cache       : false,
		processData : false,
		beforeSend  : function(){
			document.getElementById("mm_submit").disabled = true;
		}, success 	: function(data) {
			var res = JSON.parse(data);
			if (res.result == true) {
				$("#mm_alert").html(`<small class="text-success">Data berhasil disimpan</small>`);
			} else {
				$("#mm_alert").html(`<small class="text-danger">Sepertinya telah terjadi kesalahan</small>`);
			}
		}, complete : function() {
			document.getElementById("mm_submit").disabled = false;
		}
	});
}

function submitEm(uri){
	var formData = new FormData();
		formData.append('id_stok', $("[name='id_stok']").serialize());

	// 	console.log($("[name='id_stok']").serialize());

		var types = $("input[name='id_stok']").map(function(){
			return $(this).val();
		}).get();

		console.log(types.length);

		$.ajax({
			url         : uri,
			data        : formData,
			type        : "POST",
			dataType    : "TEXT",
			contentType : false,
			cache       : false,
			processData : false,
			beforeSend  : function(){
				// document.getElementById("mm_submit").disabled = true;
			}, success 	: function(data) {
				// var res = JSON.parse(data);
				// console.log(res);
				// if (res.result == true) {
				// 	$("#mm_alert").html(`<small class="text-success">Data berhasil disimpan</small>`);
				// } else {
				// 	$("#mm_alert").html(`<small class="text-danger">Sepertinya telah terjadi kesalahan</small>`);
				// }
			}, complete : function() {
				// document.getElementById("mm_submit").disabled = false;
			}
		});
}

function view_detail(uri) {
	$.ajax({
		url 	: uri,
		type 	: "GET",
		success : function(res) {
			$("[name='upd_id_order']").val(res.data.produk[0].id_order);
			$("[name='upd_produk_id']").val(res.data.produk[0].id_produk);
			$("#upd_tipe_produk").html(`<h3 class="mb-0">${res.data.produk[0].exact_name}</h3><b>${res.data.produk[0].exact_code}</b>`);
			$("#upd_hpp_pusat").html("Rp " + parseInt(res.data.produk[0].hpp_pusat).toLocaleString('id'));
			$("#upd_hpp_cabang").html("Rp " + parseInt(res.data.produk[0].hpp_cabang).toLocaleString('id'));
			$("#upd_price_list").html("Rp " + parseInt(res.data.produk[0].price_list).toLocaleString('id'));
			$("#upd_ongkir").html("Rp " + parseInt(res.data.produk[0].ongkir).toLocaleString('id'));
			$("[name='upd_berat']").val(res.data.produk[0].berat);
			$("[name='upd_diskon']").val(res.data.produk[0].diskon);
			$("[name='upd_netto']").val(res.data.produk[0].netto);
			$("#upd_alokasi_stok").html(res.data.produk[0].alokasi);
			$("[name='upd_alokasi_stok']").val(res.data.produk[0].alokasi);
			$("[name='upd_status']").val(res.data.produk[0].status);

			$("#view_detail").modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan 02, mohon coba kembali');
		}
	});
}

function edit_info(uri, uri_back) {
	$.ajax({
		url    	: uri,
		type   	: "GET",
		success	: function(res) {
			$("#mm_alert").html('');
			$("#mm_uri_back").html(`<a href="javascript:void(0);" onclick="view_stok('${uri_back}', '${uri}');" class="btn btn-secondary btn-lg">Kembali</a>`);

			$("#mm_info_produk").html("<h3 class='mb-0'>" + res.data.exact_name + "</h3><b>" + res.data.exact_code + "</<b>");
			$("[name='mm_id_produk']").val(res.data.id_produk);

			if (res.data.judul.length > 5) {
				$("[name='mm_judul']").val(res.data.judul);
			} else {
				$("[name='mm_judul']").val("Melody-Furniture NAMA_KATEGORI_UMUM NAMA_PRODUK - WARNA_PRODUK");
			}

			if (res.data.deskripsi.length > 5) {
				$("[name='mm_deskripsi']").val(res.data.deskripsi);
			} else {
				$("[name='mm_deskripsi']").val(`NAMA_PRODUK adalah __. Dilengkapi dengan __ yang secara fungsi dapat __, di desain minimalis dan soft looking, cocok di ruangan kekinian anda. Tersedia dalam jumlah terbatas.

Spesifikasi produk :
* Warna: ${res.data.spek.nama_warna}
* Finishing: ${res.data.spek.nama_finishing}

Berat produk dan kemasan:
* Dimensi produk: P: ${res.data.spek.dimensi_panjang} x L: ${res.data.spek.dimensi_lebar} x T: ${res.data.spek.dimensi_tinggi} cm
* Dimensi box pengiriman (${res.data.spek.koli} Koli): P: ${res.data.spek.box1_panjang} x L: ${res.data.spek.box1_lebar} x T: ${res.data.spek.box1_tinggi} cm
* Berat produk tanpa kemasan: ${res.data.spek.total_nw} kg
* Berat produk dengan kemasan: ${res.data.spek.total_gw} kg

Keunggulan :
* Terbuat dari bahan MDF dan particle board berkualitas tinggi , Tidak berbau dan aman
* Dilaminasi oleh kertas dengan teknologi terbaru
* Dilengkapi dengan Petunjuk Perakitan (Assembly Instructions) memudahkan anda dalam merakit
* Produck Knock Down, dikirim dalam keadaan belum dirakit`);
			}

			$("#mm_format").html(`
				Melody-Furniture NAMA_KATEGORI_UMUM NAMA_PRODUK - WARNA_PRODUK
				<br><br>
				NAMA_PRODUK adalah __. Dilengkapi dengan __ yang secara fungsi dapat __, di desain minimalis dan soft looking, cocok di ruangan kekinian anda. Tersedia dalam jumlah terbatas.
				<br><br>
				Spesifikasi produk :<br>
				* Warna: ${res.data.spek.nama_warna}<br>
				* Finishing: ${res.data.spek.nama_finishing}
				<br><br>
				Berat produk dan kemasan:<br>
				* Dimensi produk: P: ${res.data.spek.dimensi_panjang} x L: ${res.data.spek.dimensi_lebar} x T: ${res.data.spek.dimensi_tinggi} cm<br>
				* Dimensi box pengiriman (${res.data.spek.koli} Koli): 
				P: ${res.data.spek.box1_panjang} x L: ${res.data.spek.box1_lebar} x T: ${res.data.spek.box1_tinggi} cm<br>
				* Berat produk tanpa kemasan: ${res.data.spek.total_nw} kg<br>
				* Berat produk dengan kemasan: ${res.data.spek.total_gw} kg
				<br><br>
				Keunggulan :<br>
				* Terbuat dari bahan MDF dan particle board berkualitas tinggi , Tidak berbau dan aman<br>
				* Dilaminasi oleh kertas dengan teknologi terbaru<br>
				* Dilengkapi dengan Petunjuk Perakitan ( Assembly Instructions) memudahkan anda dalam merakit<br>
				* Produck Knock Down, dikirim dalam keadaan belum dirakit
			`);

			$("#view_stok").modal('hide');
			$("#upd_info").modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan 01, mohon coba kembali');
		}
	});
}

function view_stok(uri, uri_edit) {
	$('.modal').modal('hide');
	$.ajax({
		url    	: uri,
		type   	: "GET",
		success	: function(res) {
			$("#v_stok_mss").html(res.data.produk[0].stok_mss);
			$("#v_stok_sisa").html(res.data.produk[0].stok_sisa);
			$("#v_stok_teralokasi").html(res.data.produk[0].stok_teralokasi);
			$("#v_stok_free").html(res.data.produk[0].stok_free);


			$("#v_tipe_produk").html("<h3 class='mb-0'>" + res.data.produk[0].exact_name + "</h3><b>" + res.data.produk[0].exact_code + "</<b>");
			$("#v_id_produk").val(res.data.produk[0].id_produk);

			$("#v_status").html(res.data.produk[0].status);
			$("#v_hpp_pusat").html("Rp " + parseInt(res.data.produk[0].hpp_pusat).toLocaleString('id'));
			$("#v_hpp_cabang").html("Rp " + parseInt(res.data.produk[0].hpp_cabang).toLocaleString('id'));
			$("#v_price_list").html("Rp " + parseInt(res.data.produk[0].price_list).toLocaleString('id'));
			$("#v_alokasi").html(res.data.produk[0].alokasi);
			$("#v_jmlkirim").html(res.data.produk[0].jml_kirim);
			$("#v_ongkir").html("Rp " + parseInt(res.data.produk[0].ongkir).toLocaleString('id'));
			$("#v_diskon").html(res.data.produk[0].diskon+"%");
			$("#v_netto").html("Rp " + parseInt(res.data.produk[0].netto).toLocaleString('id'));

			$("#v_edit_info").html(`- <a href="javascript:void(0);" onclick="edit_info('${uri_edit}', '${uri}');">Edit</a>`);

			var stoking = 0;

			res.data.marketplace.forEach(function(mp, index, array) {
				var group = "#mp_"+mp.id;

				// RESET
					$("#mp_notif_stok_"+mp.id).hide();
					$(group+" #stok").val("");
					$(group+" #id_stok").val("");
					$(group+" #stok").val("");
					$(group+" #link_produk").val("");
					$(group+" #waktu_input").val("");
					$(group+" #waktu_hapus").val("");
					$(group+" #status").val("");
					$(group+" #harga_jual").val("");
					$(group+" #harga_jual_label").html("");
				// END OF

				var netto = parseInt(res.data.produk[0].netto);
				var count_fee, count_ppn, temp_price;
				if ($(group+" #fee_type").val() == 'persen') {

					count_fee  = (netto * parseInt($(group+" #fee").val())) / 100;
					count_ppn  = (netto * parseInt($(group+" #ppn").val())) / 100;
					temp_price = (parseInt(netto) - parseInt(count_fee) - parseInt(count_ppn)) - parseInt(res.data.produk[0].ongkir);


				} else if ($(group+" #fee_type").val() == 'value') {

					count_fee  = $(group+" #fee").val();
					count_ppn  = (netto * parseInt($(group+" #ppn").val())) / 100;
					temp_price = (parseInt(netto) - parseInt(count_fee) - parseInt(count_ppn)) - parseInt(res.data.produk[0].ongkir);

				}

				$(group+" #harga_rekomendasi").html("Rp "+ parseInt(temp_price).toLocaleString('id'));

				var compare = comparing(mp.id, res.data.stok);
				if (typeof compare == "number") {
					$(group+" #stok").val(res.data.stok[compare].stok);

					if (res.data.stok[compare].stok > 0) {
						$("#mp_notif_stok_"+mp.id).html(res.data.stok[compare].stok);
						$("#mp_notif_stok_"+mp.id).show();
						stoking += parseInt(res.data.stok[compare].stok);
					} else {
						$("#mp_notif_stok_"+mp.id).hide();
					}

					$(group+" #id_stok").val(res.data.stok[compare].id);

					// $(group+" #link_produk").val(res.data.stok[compare].link);
					// $(group+" #waktu_input").val(res.data.stok[compare].waktu_input);
					// $(group+" #waktu_hapus").val(res.data.stok[compare].waktu_hapus);
					// $(group+" #status").val(res.data.stok[compare].status);

					$(group+" #stok").val(res.data.stok[compare].stok);
					$(group+" #harga_jual").val(parseInt(res.data.stok[compare].price).toLocaleString('id'));
					// $(group+" #harga_jual_label").html("Rp " + parseInt(res.data.stok[compare].price).toLocaleString());
				}
			});

			// INFORMASI SISA STOK
				// if (stoking == parseInt(res.data.produk[0].alokasi)) {
				// 	$("#v_info_stok").html(`<div class="alert alert-icon alert-success" role="alert">
				// 		<i class="fe fe-check mr-2" aria-hidden="true"></i> Semua alokasi stok telah di publikasikan.
				// 	</div>`);
				// } else if (stoking > parseInt(res.data.produk[0].alokasi)) {
				// 	$("#v_info_stok").html(`<div class="alert alert-icon alert-danger" role="alert">
				// 		<i class="fe fe-alert-circle mr-2" aria-hidden="true"></i> Jumlah stok yang dipublikasikan tidak sesuai dengan total free stok.
				// 	</div>`);
				// } else {
				// 	$("#v_info_stok").html(`<div class="alert alert-icon alert-warning" role="alert">
				// 		<i class="fe fe-bell mr-2" aria-hidden="true"></i> Belum di post: <b>${(parseInt(res.data.produk[0].alokasi) - stoking)}</b> Stok
				// 	</div>`);
				// }
			// END OF 

			// INFORMASI JUDUL DAN DESKRIPSI
				if (res.data.exact.judul.length > 0 && res.data.exact.deskripsi.length > 0) {
					$("#v_info_exact").html(`
						<div class="form-group">
							<label class="form-label">JUDUL</label>
							<div>${res.data.exact.judul}</div>
						</div>
						<div class="form-group">
							<label class="form-label">DESKRIPSI</label>
							<div>${res.data.exact.deskripsi}</div>
						</div>`
					);
					
				} else {
					$("#v_info_exact").html(`Informasi Judul dan Deskripsi belum ada, segera tambahkan di <b>Setup Produk</b>`);
				}
			// END OF

			// INFORMASI DAFTAR GAMBAR
				var image_list = "";
				if (res.data.image.length > 0) {
					res.data.image.forEach(function(img, index, array) {
						image_list += `<img src='${img}' style='max-width:150px;'>`;
					});

					$("#v_info_exact_gambar").html(`<div class="form-group mb-0">${image_list}</div>`);
				} else {
					$("#v_info_exact_gambar").html(`Gambar belum ada, segera tambahkan di <b>Setup Produk</b>`);
				}
			// END OF

			$("#view_stok").modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan 02, mohon coba kembali');
		}
	});
}

function view_marplace(uri) {
	$("#v_id").val("");
	$("#v_marketplace").val("");
	$("#v_username").val("");
	$("#v_email").val("");
	$("#v_password").val("");
	$("#v_login_link").val("");
	$("#v_ppn").val("");
	$("#v_fee").val("");
	$("#v_fee_type").val("");
	$("#v_informasi").val("");

	$.ajax({
		url    	: uri,
		type   	: "GET",
		success	: function(res) {
			$("#v_id").val(res.data[0].id);
			$("#v_marketplace").val(res.data[0].marketplace);
			$("#v_username").val(res.data[0].username);
			$("#v_email").val(res.data[0].email);
			$("#v_password").val(res.data[0].password);
			$("#v_login_link").val(res.data[0].login);
			$("#v_ppn").val(res.data[0].ppn);
			$("#v_fee").val(res.data[0].fee);
			$("#v_fee_type").val(res.data[0].fee_type);
			// $("#v_informasi").val();
			require(['summernote'], function() {
				$('#v_informasi').summernote("code", res.data[0].catatan);
			});
			$("#view_data").modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan 02, mohon coba kembali');
		}
	});
}

function view_invoice(uri) {
	var modal = "#view_invoice";
	$.ajax({
		url    	: uri,
		type   	: "GET",
		success	: function(res) {
			console.log(res);

			var subTotal = 0;
			var listBarang = "";
			for(i=0; i<res.data.item.length; i++) {
				subTotal += parseInt(res.data.item[i].harga_total);
				listBarang += `
					<tr>
						<td>
							<div>${res.data.item[i].exact_name}</div>
							<div><small><strong>${res.data.item[i].quantity} Barang (${res.data.item[i].berat}kg)</strong> x Rp ${parseInt(res.data.item[i].harga_satuan).toLocaleString('id')}</small></div>
							<div><small>${res.data.item[i].catatan}</small></div>
						</td>
						<td class="text-right" style="vertical-align: middle;">
							<div style="font-size: 13pt"><strong>Rp ${parseInt(res.data.item[i].harga_total).toLocaleString('id')}</strong></div>
						</td>
					</tr>
				`;
			}

			$(modal + " #buyerDetail").html(`
				<strong>${res.data.detail[0].buyer_nama}</strong> (${res.data.detail[0].buyer_email})
				<br>
				${res.data.detail[0].buyer_alamat}
				<br>
				${res.data.detail[0].buyer_kota}, ${res.data.detail[0].buyer_provinsi}
				<br>
				${res.data.detail[0].buyer_telepon}
			`);
			$(modal + " #listBarang").html(listBarang);
			$(modal + " #noFaktur").html(res.data.detail[0].no_faktur);
			$(modal + " #buktiInvoice").html(`<a href="${res.data.detail[0].bukti_invoice}" target="blank">Bukti Invoice</a>`);
			$(modal + " #totalBarang").html(parseInt(res.data.detail[0].total_item) + " Barang");
			$(modal + " #totalBerat").html(parseInt(res.data.detail[0].total_berat) + " Kg");
			$(modal + " #subTotal").html("Rp " + subTotal.toLocaleString('id'));
			$(modal + " #potonganHarga").html("Rp -" + parseInt(res.data.detail[0].potongan_harga).toLocaleString('id'));
			$(modal + " #feeMarketplace").html("Rp -" + parseInt(res.data.detail[0].fee_marketplace).toLocaleString('id'));
			$(modal + " #totalTransaksi").html("Rp " + parseInt(res.data.detail[0].total_harga).toLocaleString('id'));

			$(modal + " #ppnInvoice").html("Rp -" + parseInt(res.data.detail[0].ppn).toLocaleString('id'))
			$(modal + " #biayaKirim").html("Rp -" + parseInt(res.data.detail[0].biaya_kirim).toLocaleString('id'));

			$(modal + " #totalNetto").html("Rp " + parseInt(res.data.detail[0].total_netto).toLocaleString('id'));

			$(modal + " #totalHPPPusat").html("Rp " + parseInt(res.data.detail[0].hpp_pusat).toLocaleString('id')); 
			$(modal + " #totalMargin").html("Rp " + parseInt(res.data.detail[0].total_mrg_pusat).toLocaleString('id')); 

			$(modal + " #invoiceCatatan").show();
			if (res.data.detail[0].info_catatan.length < 1) {
				$(modal + " #invoiceCatatan").hide();
			}
			$(modal + " #invoiceCatatan").html(res.data.detail[0].info_catatan);

			$(modal).modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan 02, mohon coba kembali');
		}
	});
}

function view_resifaktur(uri, uri_update) {
	var modal = "#view_resifaktur";

	$.ajax({
		url    	: uri,
		type   	: "GET",
		success	: function(res) {
			$(modal + " [name='uri_update']").val(uri_update);

			$(modal + " [name='no_faktur']").val(res.data.detail[0].no_faktur);
			$(modal + " [name='info_resi']").val(res.data.detail[0].info_resi);
			$(modal + " [name='biaya_kirim']").val(parseInt(res.data.detail[0].biaya_kirim));

			$(modal).modal();
		},
		error: function(xhr, status, errorMessage) {
			alert('Terjadi kesalahan 02, mohon coba kembali');
		}
	});
}

function filterTampil(uri) {
	var mplace = $("[name='fil_mplace']").val();
	var status = $("[name='fil_status']").val();
	var range  = $("[name='fil_range']").val();

	if (mplace || status || range) {
		window.location.href = uri+"/?fil_mplace="+mplace+"&fil_status="+status+"&fil_range="+range;
	} else {
		return false;
	}
}

require(['daterangepicker'], function() {
	$('.date-range').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD',
			separator: ','
		}
	});
	$('.month-range').daterangepicker({
		locale: {
			format: 'YYYY-MM',
			singleDatePicker: true,
			separator: ','
		}
	});
});

require(['jquery', 'datepicker', 'datepicker_eng'], function() {
	$(document).ready(function() {
		$(".datex").datepicker({
			language: 'en',
			timepicker: true,
			timeFormat: "hh:ii:00",
			dateFormat: 'yyyy-mm-dd'
		});
	});
});

$(document).ready(function() {

	require(['summernote'], function() {
		$('#summernote').summernote({
			toolbar: [
				['style', ['bold', 'italic', 'underline', 'clear']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']]
			]
		});
	});
			
	require(['select2'], function() {
		$('.select2').select2();
	});

	/** Constant div card */
	const DIV_CARD = 'div.card';

	/** Initialize tooltips */
	$('[data-toggle="tooltip"]').tooltip();

	/** Initialize popovers */
	$('[data-toggle="popover"]').popover({
		html: true
	});

	/** Function for remove card */
	$('[data-toggle="card-remove"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);

		$card.remove();

		e.preventDefault();
		return false;
	});

	/** Function for collapse card */
	$('[data-toggle="card-collapse"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);

		$card.toggleClass('card-collapsed');

		e.preventDefault();
		return false;
	});

	/** Function for fullscreen card */
	$('[data-toggle="card-fullscreen"]').on('click', function(e) {
		let $card = $(this).closest(DIV_CARD);

		$card.toggleClass('card-fullscreen').removeClass('card-collapsed');

		e.preventDefault();
		return false;
	});

	/**  */
	if ($('[data-sparkline]').length) {
		let generateSparkline = function($elem, data, params) {
			$elem.sparkline(data, {
				type: $elem.attr('data-sparkline-type'),
				height: '100%',
				barColor: params.color,
				lineColor: params.color,
				fillColor: 'transparent',
				spotColor: params.color,
				spotRadius: 0,
				lineWidth: 2,
				highlightColor: hexToRgba(params.color, .6),
				highlightLineColor: '#666',
				defaultPixelsPerValue: 5
			});
		};

		require(['sparkline'], function() {
			$('[data-sparkline]').each(function() {
				let $chart = $(this);

				generateSparkline($chart, JSON.parse($chart.attr('data-sparkline')), {
					color: $chart.attr('data-sparkline-color')
				});
			});
		});
	}

	/**  */
	if ($('.chart-circle').length) {
		require(['circle-progress'], function() {
			$('.chart-circle').each(function() {
				let $this = $(this);

				$this.circleProgress({
					fill: {
						color: tabler.colors[$this.attr('data-color')] || tabler.colors.blue
					},
					size: $this.height(),
					startAngle: -Math.PI / 4 * 2,
					emptyFill: '#F4F4F4',
					lineCap: 'round'
				});
			});
		});
	}
});