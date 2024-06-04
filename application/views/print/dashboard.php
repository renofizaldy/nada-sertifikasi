<!DOCTYPE html><html>
<head lang="id">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="robot" content="noindex, nofollow">

	<title><?php echo $top_title.$site_name; ?></title>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?php foreach($stylesheet as $ss): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $ss; ?>">
	<?php endforeach; ?>

    <style type="text/css">
    	.bg-home {
    		/*background: url("<?php echo $assets; ?>/img/bg-ab1.png") top center no-repeat;*/
    	}
    	.text-robot {
    		font-family: Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
    	}
    </style>
</head>

<body class="with-side-menu dark-theme dark-theme-blue">

	<?php require 'header.php'; ?>

	<?php require 'sidebar.php'; ?>

	<div class="page-content bg-home">
	    <div class="container-fluid">
	    	<?php echo form_open('', array('enctype'=>'multipart/form-data')); ?>
		    	<div class="row">
		    		<div class="col-md-4">
		    			<div class="form-group">
		    				<label>Pilih Range Waktu</label>
							<div class='input-group date'>
								<input name="waktu" id="daterange2" type="text" value="" class="form-control">
								<span class="input-group-addon">
									<i class="font-icon font-icon-calend"></i>
								</span>
							</div>
						</div>
		    		</div>
		    		<div class="col-md-2">
		    			<div><small style="width:100%">&nbsp;</small></div>
		    			<button type="submit" class="btn btn-primary">Tampilkan</button>
		    		</div>
		    	</div>
	    	</form>
			<section class="card">
	            <header class="card-header">
	                DATA PELAPOR
	            </header>
				<div class="card-block">
					<table id="data-pelapor" class="display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead><tr>
							<th>Kode Laporan</th>
							<th>Status</th>
							<th>Pelapor</th>
							<th>Jml. Kehilangan</th>
							<th>Pengambilan</th>
							<th>Waktu</th>
						</tr></thead>
						<tfoot><tr>
							<th>Kode Laporan</th>
							<th>Status</th>
							<th>Pelapor</th>
							<th>Jml. Kehilangan</th>
							<th>Pengambilan</th>
							<th>Waktu</th>
						</tr></tfoot>
						<tbody>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
	    </div><!--.container-fluid-->
	</div><!--.page-content-->

    <div class="modal fade" tabindex="-1" role="dialog" id="detailPelapor">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title">Detail Pelapor - <span class="ud-status"></span></h4>
				</div>
				<div class="modal-body">
					<section class="proj-page-section proj-page-dates">
						<div class="row">
							<div class="col-md-7">
								<input type="hidden" class="ud-id" value="">
								<div class="tbl">
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Nama</div>
										<div class="tbl-cell ud-nama"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Jenis Kel.</div>
										<div class="tbl-cell ud-gender"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Agama</div>
										<div class="tbl-cell ud-agama"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Pekerjaan</div>
										<div class="tbl-cell ud-pekerjaan"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Tempat/Tgl.Lahir</div>
										<div class="tbl-cell ud-tgl_lahir"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Alamat</div>
										<div class="tbl-cell ud-alamat"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Kel./Kec.</div>
										<div class="tbl-cell ud-area"></div>
									</div>
									<br>
								</div>
							</div>
							<div class="col-md-5">
								<div class="tbl">
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Kode Laporan</div>
										<div class="tbl-cell ud-kode" style="font-weight: bold"></div>
									</div>
									<div class="tbl-row">
										<div class="tbl-cell tbl-cell-lbl">Waktu Submit</div>
										<div class="tbl-cell ud-waktu"></div>
									</div>
								</div>
							</div>
						</div>
						<hr style="margin:0 0 25px;">
						<strong>DAFTAR KEHILANGAN:</strong>
						<table class="table table-bordered table-hover table-xs">
							<thead>
								<tr>
									<th>#</th>
									<th>Berkas</th>
									<th>Jumlah</th>
									<th>No. Identitas</th>
									<th>Nama Identitas</th>
									<th>Ket.</th>
								</tr>
							</thead>
							<tbody class="berkas_write"></tbody>
						</table>
						<hr style="margin:25px 0 15px;">
						<div style="margin:0 0 10px;"><strong>CETAK LAPORAN:</strong></div>
						<?php echo form_open('driver/preview/', array('target'=>'_blank')); ?>
						<input type="hidden" name="pel_id" class="pel_id" value="">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label">Petugas - Jabatan <sup>* WAJIB ISI</sup></label>
									<input name="petugas_jabatan" type="text" class="form-control" required="">
								</div>
								<div class="form-group">
									<label class="form-label">Petugas - Nama Lengkap <sup>* WAJIB ISI</sup></label>
									<input name="petugas_nama" type="text" class="form-control" required="">
								</div>
								<div class="form-group">
									<label class="form-label">Petugas - Pangkat / NRP <sup>* WAJIB ISI</sup></label>
									<input name="petugas_nomor" type="text" class="form-control" required="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label">Nomor Laporan <sup>* WAJIB ISI</sup></label>
									<input name="nomor_laporan" type="text" class="form-control" required="">
								</div>
								<div class="form-group">
									<label class="form-label">Lokasi Kehilangan <sup>* OPSIONAL</sup></label>
									<input name="lokasi_hilang" type="text" class="form-control">
								</div>
								<div class="form-group">
									<label class="form-label">&nbsp;</label>
									<button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i> PREVIEW</button>
									<button type="submit" class="btn btn-success" formaction="<?php echo base_url('driver/preview/?print=true'); ?>"><i class="fa fa-print"></i> CETAK</button>
								</div>
							</div>
						</div>
						</form>
						<hr style="margin:25px 0 15px">
						<div style="margin:0 0 10px;"><strong>STATUS:</strong></div>
						<?php echo form_open('req/put/status_laporan'); ?>
						<input type="hidden" name="sts_id" class="sts_id" value="">
						<div class="form-group">
							<div class="input-group">
								<select class="form-control" name="status_laporan">
									<option value="0">Belum Diproses</option>
									<option value="1">Telah Diproses</option>
									<option value="2">Dibatalkan</option>
								</select>
								<span class="input-group-btn">
									<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> UBAH STATUS</button>
								</span>
							</div>
						<div><small class="text-mute">Diubah pada: <span class="ud-update"></span></small></div>
						</div>
						</form>
					</section>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rounded btn-inline btn-secondary-outline" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div><!-- /modal -->

	<?php foreach($javascript as $js): ?>
		<script type="text/javascript" src="<?php echo $js; ?>"></script>
	<?php endforeach; ?>

	<script>
		$(document).ready(function() {
			$('.panel').lobiPanel({
				sortable: true
			});
			$('.panel').on('dragged.lobiPanel', function(ev, lobiPanel){
				$('.dahsboard-column').matchHeight();
			});
			$(window).resize(function(){
				setTimeout(function(){
				}, 1000);
			});

			$('#daterange2').daterangepicker({
				startDate	: '<?php echo $output['res_date_str']; ?>',
				endDate  	: '<?php echo $output['res_date_end']; ?>',
				locale   	: {
					format 	: 'DD/MM/Y'
				}
			});

			$('#data-pelapor').DataTable({
				"ajax": '<?php echo $output['req_pelapor']; ?>',
				"order": [[ 5, "desc" ]]
			});
		});

		function pelaporDetail(id) {
			var uri = "<?php echo $output['req_pelapor_detail']; ?>"+id;

			$.getJSON(uri, function(res){
				$('.ud-id').val(res.data.kode);
				$('.ud-status').html(res.data.status_view);
				$('.ud-kode').html(res.data.kode);
				$('.ud-waktu').html(res.data.datetime);
				$('.ud-nama').html(res.data.nama);
				$('.ud-gender').html(res.data.kelamin);
				$('.ud-agama').html(res.data.agama);
				$('.ud-pekerjaan').html(res.data.pekerjaan);
				$('.ud-tgl_lahir').html(res.data.tgl_lahir);
				$('.ud-alamat').html(res.data.alamat);
				$('.ud-area').html(res.data.area);

				$('.ud-update').html(res.data.timerespon);

				$("[name='status_laporan']").val(res.data.status_code);
				$("[name='petugas_jabatan']").val("");
				$("[name='petugas_nama']").val("");
				$("[name='petugas_nomor']").val("");
				$("[name='nomor_laporan']").val("");
				$("[name='lokasi_hilang']").val("");
				$("[name='sts_id']").val(res.data.kode);
				$("[name='pel_id']").val(res.data.kode);
				
				var tr = [];
				for (var i=0; i < res.berkas.length; i++) {
					tr.push('<tr>');
					tr.push("<td style='font-size: 12px;'>"+res.berkas[i].id+"</td>");
					tr.push("<td style='font-size: 12px;'>"+res.berkas[i].berkas+"</td>");
					tr.push("<td style='font-size: 12px;'>"+res.berkas[i].jumlah+"</td>");
					tr.push("<td style='font-size: 12px;'>"+res.berkas[i].id_nomor+"</td>");
					tr.push("<td style='font-size: 12px;'>"+res.berkas[i].id_nama+"</td>");
					tr.push("<td style='font-size: 12px;'>"+res.berkas[i].ket+"</td>");
					tr.push('</tr>');
				}
				$(".berkas_write").html(tr);

				$('#detailPelapor').modal('show');
			});
		}
		function cetakDetail(print=false) {
			var uri = "<?php echo $output['uri_print_preview']; ?>"+$('.ud-id').val();

			if (print) {
				window.open(uri+"&print=true");
			}

			window.open(uri);
		}
		function userUpdate(status) {
			var sent = {
				'idx'     : $('.ud-id').val(),
				'statusx' : status
			};
			$.ajax({
				url    	: "<?php echo $output['put_pelapor_status']; ?>",
				data   	: sent,
				type   	: "POST",
				success	: function(res) {
					if (res.result == 'true') {
						location.reload();
					} else {
						alert('Terjadi kesalahan 01, mohon coba kembali');
					}
				},
				error: function(xhr, status, errorMessage) {
        			alert('Terjadi kesalahan 02, mohon coba kembali');
    			}
			});
		}
	</script>

	<script src="<?php echo base_url($assets); ?>js/app.js"></script>
</body></html>