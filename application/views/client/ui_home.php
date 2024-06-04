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

<div class="page">
<div class='page-main'>

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

<div id="new_data" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Data Surat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<?= form_open('req/post/surat'); ?>
			<div class="modal-body">
				<input type="hidden" name="in_jenissurat" value="<?= $output['param']; ?>" />

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
			<?= form_open('req/put/surat', ['autocomplete'=>'off']); ?>
			<div class="modal-body">
				<input type="hidden" name="up_jenissurat" value="<?= $output['param']; ?>" />
				<input type="hidden" class="form-control" name="data_id">

				<div class="form-group">
					<label class="form-label">Nomor Surat</label>
					<input name="up_nomorsurat" type="number" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Nama Pengirim</label>
					<input name="up_namapengirim" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Waktu</label>
					<input name="up_waktu" type="datetime-local" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Lampiran</label>
					<input name="up_lampiran" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Perihal</label>
					<input name="up_perihal" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Nama Penerima</label>
					<input name="up_namapenerima" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Isi Surat</label>
					<textarea name="up_isisurat" class="form-control" required=""></textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Unit Penerbit</label>
					<input name="up_unitpenerbit" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Tempat</label>
					<input name="up_tempat" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Pengesah</label>
					<input name="up_pengesah" type="text" class="form-control" required="" />
				</div>

				<div class="form-group">
					<label class="form-label">Tembusan</label>
					<input name="up_tembusan" type="text" class="form-control" required="" />
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

<?php include('foot_nav.php'); ?>