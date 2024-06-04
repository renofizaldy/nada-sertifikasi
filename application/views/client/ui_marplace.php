<?php include('head_top.php'); ?>
<?php include('head_nav.php'); ?>

<div class="my-3 my-md-5"><div class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<?php switch($output['level_os']) {
					case 1:
						echo "<div class=\"card-status bg-gray-dark\"></div>";
					break;
					case 5:
						echo "<div class=\"card-status bg-pink\"></div>";
					break;
				} ?>
				<div class="card-header">
					<h3 class="card-title">DAFTAR AKUN DAN INFORMASI</h3>
					<div class="card-options">
						<!-- <?php if($output['level_os'] == 1): ?>
						<a data-target="#upl_xls" data-toggle='modal' href="javascript:void(0)" class="btn btn-secondary btn-sm ml-2"><i class="fe fe-upload"></i> Unggah</a>
						<a href="<?php echo base_url('req/get/excel/replacement'); ?>" target="_blank" class="btn btn-secondary btn-sm ml-2"><i class="fe fe-download"></i> Unduh</a>
						<?php endif; ?> -->
						<a href="#" data-target="#new_data" data-toggle="modal" class="btn btn-primary btn-sm ml-2"><i class="fe fe-plus"></i> Tambah</a>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-outline table-vcenter text-nowrap card-table">
						<thead>
							<tr>
								<th>Marketplace</th>
								<th class="text-center">Jml Tipe</th>
								<th>PPN</th>
								<th>Fee</th>
								<th>Email</th>
								<th>Username</th>
								<th>Password</th>
								<th class="text-center"><i class="icon-settings"></i></th>
							</tr>
						</thead>
						<tbody><?php foreach($output['res_marplace'] as $row): ?>
							<tr>
								<td>
									<div><b><?php echo htmlspecialchars_decode($row['marketplace']); ?></b></div>
								</td>
								<td class="text-center">
									<?php echo $row['stok']; ?>
								</td>
								<td>
									<?php echo $row['ppn']; ?>%
								</td>
								<td>
									<?php switch($row['fee_type']) {
										case 'persen':
											echo $row['fee']."%";
										break;
										case 'value':
											echo "Rp ".number_format($row['fee']);
										break;
									} ?>
								</td>
								<td>
									<?php echo $row['email']; ?>
								</td>
								<td>
									<?php echo $row['username']; ?>
								</td>
								<td>
									<?php echo $row['password']; ?>
								</td>
								<td class="text-right">
									<a onclick="view_marplace('<?php echo base_url('req/get/marplace/'.$row['id']); ?>');" href='javascript:void(0)' class='btn btn-secondary btn-sm'>Detail</a>
								</td>
								<td>
									<a class='icon' href='<?php echo htmlspecialchars_decode($row['login']); ?>' target='_blank'>
										<i class="fe fe-external-link"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div></div>

<div id="new_data" class="modal" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Marketplace</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<?php echo form_open('req/post/marplace'); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-label">Nama Marketplace</label>
					<input name="marketplace" type="text" class="form-control">
				</div>
				<hr style="margin: 25px 0">
				<div class="row">
					<div class="col-md-4"><div class="form-group">
						<label class="form-label">Username</label>
						<input name="username" type="text" class="form-control">
					</div></div>
					<div class="col-md-4"><div class="form-group">
						<label class="form-label">Email</label>
						<input name="email" type="text" class="form-control">
					</div></div>
					<div class="col-md-4"><div class="form-group">
						<label class="form-label">Password</label>
						<input name="password" type="text" class="form-control">
					</div></div>
				</div>
				<div class="form-group">
					<label class="form-label">Link Login</label>
					<input name="login_link" type="text" class="form-control">
				</div>
				<hr style="margin: 25px 0">
				<div class="row">
					<div class="col-md-5"><div class="form-group">
						<label class="form-label">Pajak PPN (%)</label>
						<input name="ppn" type="number" class="form-control" min="0">
					</div></div>
					<div class="col-md-5"><div class="form-group">
						<label class="form-label">Fee Marketplace</label>
						<input name="fee" type="number" class="form-control" min="0">
					</div></div>
					<div class="col-md-2"><div class="form-group">
						<label class="form-label">Jenis Fee</label>
						<select name="fee_type" class="form-control">
							<option value="persen">%</option>
							<option value="value">Rp</option>
						</select>
					</div></div>
				</div>
				<hr style="margin: 10px 0 25px;">
				<div class="form-group">
					<label class="form-label">Informasi Lainnya</label>
					<textarea name="informasi" id="summernote" class="form-control"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Tambahkan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div id="view_data" class="modal" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Marketplace</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<?php echo form_open('req/put/marplace'); ?>
			<input id="v_id" type="hidden" name="v_id">
			<div class="modal-body">
				<div class="form-group">
					<label class="form-label">Nama Marketplace</label>
					<input id="v_marketplace" name="marketplace" type="text" class="form-control">
				</div>
				<hr style="margin: 25px 0">
				<div class="row">
					<div class="col-md-4"><div class="form-group">
						<label class="form-label">Username</label>
						<input id="v_username" name="username" type="text" class="form-control">
					</div></div>
					<div class="col-md-4"><div class="form-group">
						<label class="form-label">Email</label>
						<input id="v_email" name="email" type="text" class="form-control">
					</div></div>
					<div class="col-md-4"><div class="form-group">
						<label class="form-label">Password</label>
						<input id="v_password" name="password" type="text" class="form-control">
					</div></div>
				</div>
				<div class="form-group">
					<label class="form-label">Link Login</label>
					<input id="v_login_link" name="login_link" type="text" class="form-control">
				</div>
				<hr style="margin: 25px 0">
				<div class="row">
					<div class="col-md-5"><div class="form-group">
						<label class="form-label">Pajak PPN (%)</label>
						<input id="v_ppn" name="ppn" type="number" class="form-control" min="0">
					</div></div>
					<div class="col-md-5"><div class="form-group">
						<label class="form-label">Fee Marketplace</label>
						<input id="v_fee" name="fee" type="text" class="form-control" min="0">
					</div></div>
					<div class="col-md-2"><div class="form-group">
						<label class="form-label">Jenis Fee</label>
						<select id="v_fee_type" name="fee_type" class="form-control">
							<option value="persen">%</option>
							<option value="value">Rp</option>
						</select>
					</div></div>
				</div>
				<hr style="margin: 10px 0 25px;">
				<div class="form-group">
					<label class="form-label">Informasi Lainnya</label>
					<textarea id="v_informasi" name="informasi" id="summernote" class="form-control"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div id="upl_xls" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Unggah data excel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php echo form_open('req/put/import/replacement', array('enctype'=>'multipart/form-data')); ?>
			<div class="modal-body">
				<div class="alert alert-secondary">
					Semua data yang telah ada akan digantikan oleh file excel yang akan di unggah. Pastikan file excel yang di unggah sesuai dengan format excel yang telah di unduh.
				</div>
				<div class="form-group">
					<div class="custom-file">
						<input name="file_input" type="file" class="custom-file-input" required="">
						<label class="custom-file-label">Pilih file <b>XLS</b> / <b>XLSX</b></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary"><i class="fe fe-upload"></i> &nbsp;Unggah Data</button>
			</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	require(['jquery','datatables'], function() {
		$(document).ready(function() {
			$('.dataTable').DataTable({
				"ajax": '<?php echo $output['url_tables']; ?>',
				"aoColumnDefs" : [ {
					"bSortable" : false,
					"aTargets" : [ "no-sort" ]
				} ],
				"oLanguage": {
					"sSearch"	: "Cari",
					"sInfo"		: "_START_ - _END_ dari _TOTAL_ Data",
					"sLengthMenu": "_MENU_ Baris",
					"oPaginate" : {
						"sNext"		: ">",
						"sPrevious" : "<"
					}
				}
			});
		});
	});
	require(['vue', 'jquery', 'select2'], function(vue) {
		var Vue = require('vue');
		const appvue = new Vue({
			el: "#new_data",
			data: {
				header : "test",
				info_tipe_produk: "",
				info_tipe_produk_name: "",
				info_hpp_pusat: 0,
				info_hpp_cabang: 0,
				info_price_list: 0,
				info_diskon: 0,
				info_ongkir: 0,
				info_alokasi_stok: 0
			},
			computed: {
				info_netto: function() {
					var diskon = parseInt(this.info_diskon);
					var netto  = parseInt(this.info_price_list) + parseInt(this.info_ongkir);
					if (diskon > 0) {
						return (netto - (netto * diskon/100))
					} else {
						return netto;
					}
				}
			},
			mounted: function() {
				var uri = "<?php echo base_url('req/get/produk/harga/'); ?>";
				var self = this;
				$("[name='info_tipe_produk']").change(function() {
					$.getJSON(uri+"?exact="+$(this).val(), function(res){
						self.info_hpp_pusat  = res.data[0].pusat;
						self.info_hpp_cabang = res.data[0].cabang;
						self.info_price_list = res.data[0].pricelist;
					});
					setTimeout(function() {
						self.info_tipe_produk_name = $("[name='info_tipe_produk']").select2().find(":selected").data("name");
					}.bind(self));
				});
			}
		});
	});
</script>

<?php include('foot_nav.php'); ?>