<?php include('head_top.php'); ?>
<?php include('head_nav.php'); ?>

<style type="text/css">
	.label-sup {
		position      : relative;
		font-size     : 60%;
		line-height   : 0;
		vertical-align: baseline;
		top           : -.8em;
		background    : #5eba00;
		padding       : 4px 8px;
		border-radius : 54px;
		color         : white;
	}
</style>

<div class="my-3 my-md-5"><div class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-status bg-gray-dark"></div>
				<div class="card-header">
					<h3 class="card-title">Surat Keluar</h3>
					<div class="card-options">
						<a href="#" data-target="#new_data" data-toggle="modal" class="btn btn-primary btn-sm ml-2"><i class="fe fe-plus"></i> Tambah</a>
					</div>
				</div>
				<table class="dataTable table card-table table-vcenter text-nowrap">
					<thead>
						<tr>
							<th>No.Surat</th>
							<th>Pengirim</th>
							<th>Waktu</th>
							<th>Tempat</th>
							<th>Lampiran</th>
							<th>Perihal</th>
							<th class="no-sort"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

</div></div>

<div id="new_data" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Data Surat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<?= form_open('req/post/produk'); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-label">Nomor Surat</label>
					<input name="in_nomorsurat" type="number" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Nama Pengirim</label>
					<input name="in_namapengirim" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Waktu</label>
					<input name="in_waktu" type="datetime-local" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Lampiran</label>
					<input name="in_lampiran" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Perihal</label>
					<input name="in_perihal" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Nama Penerima</label>
					<input name="in_namapenerima" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Isi Surat</label>
					<textarea name="in_isisurat" class="form-control" required=""></textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Unit Penerbit</label>
					<input name="in_unitpenerbit" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Tempat</label>
					<input name="in_tempat" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Pengesah</label>
					<input name="in_pengesah" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Tembusan</label>
					<input name="in_tembusan" type="text" class="form-control" required="" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Tambahkan</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div id="view_detail" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Produk</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<?= form_open('req/put/detail', ['autocomplete'=>'off']); ?>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="upd_produk_id">
				<input type="hidden" class="form-control" name="upd_id_order">
				<div class="form-group text-center">
					<div id="upd_tipe_produk"></div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="card p-3">
							<div class="d-flex align-items-center">
								<div style="margin-top: -5px">
									<small class="text-muted">HPP Pusat</small>
									<h5 class="m-0"><span id="upd_hpp_pusat"></span></h5>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card p-3">
							<div class="d-flex align-items-center">
								<div style="margin-top: -5px">
									<small class="text-muted">HPP Cabang</small>
									<h5 class="m-0"><span id="upd_hpp_cabang"></span></h5>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card p-3">
							<div class="d-flex align-items-center">
								<div style="margin-top: -5px">
									<small class="text-muted">Price List</small>
									<h5 class="m-0"><span id="upd_price_list"></span></h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label class="form-label">Berat (Kg): <span class="text-muted" id="upd_ongkir"></span></label>
							<input name="upd_berat" type="number" class="form-control" min="0">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">Diskon (%)</label>
							<input name="upd_diskon" type="number" class="form-control" min="0">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Harga Jual</label>
					<input name="upd_netto" type="number" class="form-control is-valid" min="0">
					<small class="text-success"></small>
				</div>
				<div class="form-group mb-0">
					<label class="form-label">Status</label>
					<div class="selectgroup w-100">
						<label class="selectgroup-item">
							<input type="radio" name="upd_status" value="1" class="selectgroup-input">
							<span class="selectgroup-button">Aktif</span>
						</label>
						<label class="selectgroup-item">
							<input type="radio" name="upd_status" value="2" class="selectgroup-input">
							<span class="selectgroup-button">Tidak Aktif</span>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	require(['jquery','datatables'], function() {
		$(document).ready(function() {
			$('.dataTable').DataTable({
				"ajax": '<?= $output['url_tables']; ?>',
				"aoColumnDefs" : [
					{
						"bSortable": false,
						"aTargets" : [ "no-sort" ]
					}
				],
				"oLanguage": {
					"sSearch"    : "Filter",
					"sInfo"      : "_START_ - _END_ dari _TOTAL_ Data",
					"sLengthMenu": "_MENU_ Baris",
					"oPaginate"  : {
						"sNext"    : ">",
						"sPrevious": "<"
					}
				}
			});
		});
	});
</script>

<?php include('foot_nav.php'); ?>