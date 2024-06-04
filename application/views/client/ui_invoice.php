<?php include('head_top.php'); ?>
<?php include('head_nav.php'); ?>

<style>
@media (min-width: 1280px){
	.container {
		max-width: 1300px;
	}
}
.form-sm-filter {
	width: 10%;
	height: 1.7rem !important;
	padding: 0.1rem 0.2rem !important;
	font-size: 0.8rem !important;
}
.table th, .text-wrap table th {
	font-size: .75rem;
}
.dataTable {
	font-size: .9rem;
}
.tit-summary {
	font-size: 1.5rem
}
</style>

<div class="my-3 my-md-5"><div class="container">

	<?php switch($_SESSION['level_os']) {
		case 1: ?>
			<div class="d-flex flex-row">
				<div class="flex-fill mr-2">
					<div class="card">
						<div class="card-body p-4" data-toggle='tooltip' data-placement='top' title='Total Nilai Penjualan'>
							<div class="card-value float-right"><img src="<?php echo base_url('assets/v2/images/ico-gross.png'); ?>" style="height:100%"></div>
							<h3 id="totalGrs" class="mb-1 tit-summary">Rp 0</h3>
							<div><span class="tag bg-warning text-white">GROSS</span></div>
						</div>
					</div>
				</div>
				<div class="flex-fill mr-2">
					<div class="card">
						<div class="card-body p-4" data-toggle='tooltip' data-placement='top' title='Gross - (Fee Marketplace + Voucher)'>
							<div class="card-value float-right"><img src="<?php echo base_url('assets/v2/images/ico-give.png'); ?>" style="height:100%"></div>
							<h3 id="totalSum" class="mb-1 tit-summary">Rp 0</h3>
							<div><span class="tag bg-info text-white">TRANSAKSI</span></div>
						</div>
					</div>
				</div>
				<div class="flex-fill mr-2">
					<div class="card">
						<div class="card-body p-4" data-toggle='tooltip' data-placement='top' title='Transaksi - (PPn + Biaya Kirim + Biaya Iklan)'>
							<div class="card-value float-right"><img src="<?php echo base_url('assets/v2/images/ico-netto.png'); ?>" style="height:100%"></div>
							<h3 id="totalNet" class="mb-1 tit-summary">Rp 0</h3>
							<div><span class="tag bg-success text-white">NETTO</span></div>
						</div>
					</div>
				</div>
				<div class="flex-fill">
					<div class="card">
						<div class="card-body p-4" data-toggle='tooltip' data-placement='top' title='Netto - Total HPP Pusat'>
							<div class="card-value float-right"><img src="<?php echo base_url('assets/v2/images/ico-margin.png'); ?>" style="height:100%"></div>
							<h3 id="totalMPusat" class="mb-1 tit-summary">Rp 0</h3>
							<div><span class="tag bg-gray text-white">M. PUSAT</span> <strong id="totalMPusatPersen" class="ml-1"></strong></div>
						</div>
					</div>
				</div>
				<!-- <div class="flex-fill">
					<div class="card">
						<div class="card-body p-4" data-toggle='tooltip' data-placement='top' title='Netto - Total HPP Pusat'>
							<div class="card-value float-right"><img src="<?php echo base_url('assets/v2/images/ico-margin.png'); ?>" style="height:100%"></div>
							<h3 id="totalMCabang" class="mb-1 tit-summary">Rp 0</h3>
							<div><span class="tag bg-gray text-white">M. CABANG</span> <strong id="totalMCabangPersen" class="ml-1"></strong></div>
						</div>
					</div>
				</div> -->
			</div>
		<?php break;
	} ?>

	<div class="row">
		<div class="col-md-12">
			<div class="card table-responsive">
				<div class="card-header">
					<i class="fe fe-filter"></i> 
					<select name="fil_mplace" class="form-control form-sm-filter ml-2">
						<option value="all" <?php echo ($output['get_mplace'] == "all") ? "selected":""; ?>>Semua Marketplace</option>
						<?php foreach($output['res_marplace'] as $row): ?>
							<option value="<?php echo $row['marketplace'] ?>" <?php echo ($row['marketplace'] == $output['get_mplace']) ? "selected":""; ?>><?php echo $row['marketplace'] ?></option>
						<?php endforeach; ?>
					</select>
					<select name="fil_status" class="form-control form-sm-filter ml-2">
						<option <?php echo ($output['get_status'] == 'all') ? "selected":""; ?> value="all">Semua Status</option>
						<option <?php echo ($output['get_status'] == '0') ? "selected":""; ?> value="0">Menunggu</option>
						<option <?php echo ($output['get_status'] == '1') ? "selected":""; ?> value="1">Diproses</option>
						<option <?php echo ($output['get_status'] == '2') ? "selected":""; ?> value="2">Dikirim</option>
						<option <?php echo ($output['get_status'] == '3') ? "selected":""; ?> value="3">Selesai</option>
					</select>
					<input name="fil_range" type="text" class="form-control form-sm-filter ml-2 date-range" placeholder="Rentang Waktu" value="<?php echo $output['get_current']; ?>">
					<button type="button" class="btn btn-sm btn-gray-dark ml-2" onclick="filterTampil('<?php echo base_url('route/invoice'); ?>');">Tampilkan</button>

					<div class="card-options">
						<?php switch($_SESSION['level_os']) {
							case 1: ?>
								<a href="javascript:void(0);" data-target="#get_excel" data-toggle="modal" class="btn btn-secondary btn-sm mr-2"><i class="fe fe-download"></i> Excel</a>
								<div class="dropdown mr-2">
									<button class="btn btn-gray-dark btn-sm dropdown-toggle" type="button" id="kolomChoice" data-toggle="dropdown"><i class="fe fe-list"></i> Kolom</button>
									<div class="dropdown-menu dropdown-menu-right px-1 py-1">
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="6" href="javascript:void(0);">Margin Pusat</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="7" href="javascript:void(0);">Margin Cabang</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="11" href="javascript:void(0);">Waktu Beli</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="0" href="javascript:void(0);">Status</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="2" href="javascript:void(0);">Faktur</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="8" href="javascript:void(0);">Pembeli</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="9" href="javascript:void(0);">Penerima</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="10" href="javascript:void(0);">Ongkir</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="11" href="javascript:void(0);">Produk</a>
										<a class="dropdown-item text-right p-2 m-0 toggle-vis" data-column="12" href="javascript:void(0);">Resi</a>
									</div>
								</div>
								<a href="<?php echo base_url('invoice/tambah'); ?>" class="btn btn-primary btn-sm"><i class="fe fe-plus"></i> Tambah</a>
							<?php break;
						} ?>
					</div>
				</div>
				<?php switch($_SESSION['level_os']) {
					case 1: // PUSAT ?>
						<table class="dataTable table table-hover" style="width:100%">
							<thead>
								<tr>
									<th class="">Status</th>
									<th class="">Market</th>
									<th class="">Faktur</th>
									<th class="">Gross</th>
									<th class="">Transaksi</th>
									<th class="">Netto</th>
									<th class="">M Pusat</th>
									<th class="">M Cabang</th>
									<th class="">Pembeli</th>
									<th class="">Penerima</th>
									<th class="">Ongkir</th>
									<th class="">Produk</th>
									<th class="">Resi</th>
									<th class="">Dibeli Pada</th>
									<th class="w-1 text-center no-sort"><i class="icon-settings"></i></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					<?php break;
					case 5: // VIEW ?>
						<table class="dataTable table table-hover" style="width:100%">
							<thead>
								<tr>
									<th class="">Status</th>
									<th class="">Market</th>
									<th class="">Faktur</th>
									<th class="">Gross</th>
									<th class="">Transaksi</th>
									<th class="">Buyer</th>
									<th class="">Produk</th>
									<th class="">Resi</th>
									<th class="">Dibeli Pada</th>
									<th class="w-1 text-center no-sort"><i class="icon-settings"></i></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					<?php break;
				} ?>
			</div>
		</div>
	</div>

	<?php switch($_SESSION['level_os']) {
		case 1: ?>
			<div class="row">
				<div class="col-md-3">
					<div class="card" style="height: calc(39rem + 11px)">
						<div class="card-header">
							<strong>PRODUK TERLARIS</strong>
						</div>
						<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
							<table class="table card-table table-vcenter">
								<thead>
									<tr>
										<th>Tipe</th>
										<th class="w-10">Dibeli</th>
									</tr>
								</thead>
								<tbody id="stat_produk">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-4">
							<div class="card" style="height: calc(17rem + 13px)">
								<div class="card-header"><strong>PROVINSI</strong></div>
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="stat_provinsi">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card" style="height: calc(17rem + 13px)">
								<div class="card-header"><strong>KOTA</strong></div>
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="stat_kota">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card" style="height: calc(17rem + 13px)">
								<div class="card-header"><strong>TOTAL DETAIL</strong></div>
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody>
											<tr>
												<td>Fee Marketplace</td>
												<td><span id="stat_fee" class="badge badge-danger"></span></td>
											</tr>
											<tr>
												<td>Voucher</td>
												<td><span id="stat_voucher" class="badge badge-danger"></span></td>
											</tr>
											<tr>
												<td>Biaya Kirim</td>
												<td><span id="stat_ongkir" class="badge badge-danger"></span></td>
											</tr>
											<tr>
												<td><a href="javascript:void(0);" data-toggle="modal" data-target="#add_biayaiklan">Biaya Iklan</a></td>
												<td><span id="stat_iklan" class="badge badge-danger"></span></td>
											</tr>
											<tr>
												<td>PPn</td>
												<td><span id="stat_ppn" class="badge badge-danger"></span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<strong>MP by GROSS</strong>
							<div class="card" style="height: calc(7rem + 17px)">
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="sort_gross">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<strong>MP by NETTO</strong>
							<div class="card" style="height: calc(7rem + 17px)">
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="sort_netto">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<strong>MP by FEE</strong>
							<div class="card" style="height: calc(7rem + 17px)">
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="sort_fee">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<strong>MP by VOUCHER</strong>
							<div class="card" style="height: calc(7rem + 17px)">
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="sort_voucher">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<strong>MP by ONGKIR</strong>
							<div class="card" style="height: calc(7rem + 17px)">
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="sort_ongkir">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<strong>MP by IKLAN</strong>
							<div class="card" style="height: calc(7rem + 17px)">
								<div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
									<table class="table card-table table-vcenter">
										<tbody id="sort_iklan">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php break;
	} ?>

</div></div>

<div id="view_invoice" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Transaksi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body p-0">
				<table class="table card-table">
					<tbody id="listBarang"></tbody>
				</table>

				<hr class="mt-3 mb-5">

				<div class="px-5 pb-5">
					<div class="card p-3">
						<div class="d-flex align-items-center">
							<div style="margin-top: -5px">
								<small class="text-muted">Buyer</small>
								<div id="buyerDetail"></div>
							</div>
						</div>
					</div>

					<div id="invoiceCatatan" class="alert alert-warning">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vel laborum quam rem nam! Placeat deserunt, a corrupti totam, id, nostrum aperiam voluptatem quos rerum odit aliquid rem recusandae explicabo quidem.</div>

					<table class="table card-table"><tbody>
						<tr>
							<td><strong id="noFaktur"></strong></td>
							<td class="text-right">
								<span id="buktiInvoice"></span>
							</td>
						</tr>
						<tr>
							<td>Total Barang</td>
							<td class="text-right">
								<span id="totalBarang"></span>
							</td>
						</tr>
						<tr>
							<td>Total Berat</td>
							<td class="text-right">
								<span id="totalBerat"></span>
							</td>
						</tr>
						<tr>
							<td><strong>Subtotal / Gross</strong></td>
							<td class="text-right">
								<span id="subTotal" class="text-success"></span>
							</td>
						</tr>
						<tr>
							<td>Potongan Harga</td>
							<td class="text-right">
								<span id="potonganHarga" class="text-danger"></span>
							</td>
						</tr>
						<tr>
							<td>Fee Marketplace</td>
							<td class="text-right">
								<span id="feeMarketplace" class="text-danger"></span>
							</td>
						</tr>
						<tr style="font-size: 14pt;">
							<td><strong>TOTAL TRANSAKSI</strong></td>
							<td class="text-right text-info">
								<strong id="totalTransaksi"></strong>
							</td>
						</tr>
						<tr>
							<td>PPn 10%</td>
							<td class="text-right">
								<span id="ppnInvoice" class="text-danger"></span>
							</td>
						</tr>
						<tr>
							<td>Biaya Kirim</td>
							<td class="text-right">
								<span id="biayaKirim" class="text-danger"></span>
							</td>
						</tr>
						<tr style="font-size: 14pt;">
							<td><strong>TOTAL NETTO</strong></td>
							<td class="text-right text-success">
								<strong id="totalNetto"></strong>
							</td>
						</tr>
						<tr>
							<td>Total HPP Pusat</td>
							<td class="text-right">
								<span id="totalHPPPusat" class="text-danger"></span>
							</td>
						</tr>
						<tr style="font-size: 14pt;">
							<td><strong>TOTAL MARGIN</strong></td>
							<td class="text-right">
								<strong id="totalMargin"></strong>
							</td>
						</tr>
					</tbody></table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="view_resifaktur" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Quick Update Faktur dan Resi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body p-0 pt-3 ">
				<input type="hidden" name="uri_update">

				<div class="form-group px-4">
					<label class="form-label">No. Faktur</label>
					<input type="text" class="form-control" name="no_faktur">
				</div>

				<hr class="my-4">

				<div class="row px-4">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">No. Resi</label>
							<input type="text" class="form-control" name="info_resi">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Biaya Kirim</label>
							<input type="text" class="form-control" name="biaya_kirim">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			    <div class="text-left" id="rf_alert"></div>
				<div class="text-right"><button class="btn btn-primary" id="rf_submit" onclick="submitRf()">Simpan</button></div>
			</div>
		</div>
	</div>
</div>

<div id="add_biayaiklan" class="modal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah/Edit Biaya Iklan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body p-0 pt-3 ">
				<div class="form-group px-4">
					<select class="form-control" name="bi_marketplace">
						<option selected="" disabled="">Pilih</option>
						<?php foreach($output['res_marplace'] as $row): ?>
							<option value="<?php echo $row['id'] ?>" <?php echo ($row['marketplace'] == $output['get_mplace']) ? "selected":""; ?>><?php echo $row['marketplace'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<hr class="my-4">

				<div class="row px-4">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Bulan-Tahun</label>
							<input type="text" class="form-control month-range" name="bi_bulan_tahun">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Biaya Iklan</label>
							<input type="text" class="form-control" name="bi_biaya_iklan">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			    <div class="text-left" id="bi_alert"></div>
				<div class="text-right"><button class="btn btn-primary" id="bi_submit" onclick="submitBi('<?php echo base_url('req/post/biaya_iklan'); ?>');">Simpan</button></div>
			</div>
		</div>
	</div>
</div>

<div id="get_excel" class="modal" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Unduh Excel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body pt-3 ">
				<a href="<?php echo $output['url_excel'].'&fil_excel=extended'; ?>" target="_blank" class="btn btn-primary mr-2">Extended</a>
				<a href="<?php echo $output['url_excel'].'&fil_excel=lite'; ?>" target="_blank" class="btn btn-gray">Lite</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	localStorage.removeItem('_marment_inv_cart');
	require(['jquery','datatables'], function() {
		$(document).ready(function() {
			var res_biaya_iklan = 0;
			$.ajax({
				url 	: '<?php echo $output['url_stats_plgdibeli']; ?>',
				type 	: "GET",
				success : function(res) {

					var result_produk   = "";
					var result_provinsi = "";
					var result_kota     = "";

					for(i=0; i<res.paling_dibeli.length; i++) {
						result_produk += `
							<tr>
								<td>${res.paling_dibeli[i][1]}</td>
								<td>${res.paling_dibeli[i][2]}</td>
							</tr>
						`;
					}

					for(i=0; i<res.origin_provinsi.length; i++) {
						result_provinsi += `
							<tr>
								<td>${res.origin_provinsi[i][0]}</td>
								<td>${res.origin_provinsi[i][1]}</td>
							</tr>
						`;
					}

					for(i=0; i<res.origin_kota.length; i++) {
						result_kota += `
							<tr>
								<td>${res.origin_kota[i][0]}</td>
								<td>${res.origin_kota[i][1]}</td>
							</tr>
						`;
					}

					var result_sort_gross = "";
					for(i=0; i<res.gross_sort.length; i++) {
						result_sort_gross += `
							<tr>
								<td class="p-3"><small>${res.gross_sort[i][0]}</small></td>
								<td class="text-right p-3"><span class="badge badge-default">Rp ${parseInt(res.gross_sort[i][1]).toLocaleString('id')}</span></td>
							</tr>
						`;
					}
					var result_sort_netto = "";
					for(i=0; i<res.netto_sort.length; i++) {
						result_sort_netto += `
							<tr>
								<td class="p-3"><small>${res.netto_sort[i][0]}</small></td>
								<td class="text-right p-3"><span class="badge badge-default">Rp ${parseInt(res.netto_sort[i][1]).toLocaleString('id')}</span></td>
							</tr>
						`;
					}
					var result_sort_fee = "";
					for(i=0; i<res.fee_sort.length; i++) {
						result_sort_fee += `
							<tr>
								<td class="p-3"><small>${res.fee_sort[i][0]}</small></td>
								<td class="text-right p-3"><span class="badge badge-default">Rp ${parseInt(res.fee_sort[i][1]).toLocaleString('id')}</span></td>
							</tr>
						`;
					}
					var result_sort_voucher = "";
					for(i=0; i<res.voucher_sort.length; i++) {
						result_sort_voucher += `
							<tr>
								<td class="p-3"><small>${res.voucher_sort[i][0]}</small></td>
								<td class="text-right p-3"><span class="badge badge-default">Rp ${parseInt(res.voucher_sort[i][1]).toLocaleString('id')}</span></td>
							</tr>
						`;
					}
					var result_sort_ongkir = "";
					for(i=0; i<res.ongkir_sort.length; i++) {
						result_sort_ongkir += `
							<tr>
								<td class="p-3"><small>${res.ongkir_sort[i][0]}</small></td>
								<td class="text-right p-3"><span class="badge badge-default">Rp ${parseInt(res.ongkir_sort[i][1]).toLocaleString('id')}</span></td>
							</tr>
						`;
					}
					var result_sort_iklan = "";
					for(i=0; i<res.iklan_sort.length; i++) {
						result_sort_iklan += `
							<tr>
								<td class="p-3"><small>${res.iklan_sort[i][0]}</small></td>
								<td class="text-right p-3"><span class="badge badge-default">Rp ${parseInt(res.iklan_sort[i][1]).toLocaleString('id')}</span></td>
							</tr>
						`;
					}

					$("#totalGrs").html("Rp " + res.summary[0]['total_gross'].toLocaleString('id'));
					$("#totalSum").html("Rp " + res.summary[0]['total_transaksi'].toLocaleString('id'));
					$("#totalNet").html("Rp " + res.summary[0]['total_netto'].toLocaleString('id'));
					$("#totalMPusat").html("Rp " + res.summary_hpp[0]['hpp_pusat'].toLocaleString('id'));
					$("#totalMCabang").html("Rp " + res.summary_hpp[0]['hpp_cabang'].toLocaleString('id'));
					$("#totalMPusatPersen").html(res.summary_hpp[0]['per_pusat']);
					$("#totalMCabangPersen").html(res.summary_hpp[0]['per_cabang']);

					$("#stat_produk").html(result_produk);
					$("#stat_provinsi").html(result_provinsi);
					$("#stat_kota").html(result_kota);

					$("#sort_gross").html(result_sort_gross);
					$("#sort_netto").html(result_sort_netto);
					$("#sort_fee").html(result_sort_fee);
					$("#sort_voucher").html(result_sort_voucher);
					$("#sort_ongkir").html(result_sort_ongkir);
					$("#sort_iklan").html(result_sort_iklan);

					$("#stat_fee").html("Rp " + parseInt(res.fee[0]).toLocaleString('id'));
					$("#stat_voucher").html("Rp " + parseInt(res.voucher[0]).toLocaleString('id'));
					$("#stat_ongkir").html("Rp " + parseInt(res.ongkir[0]).toLocaleString('id'));
					$("#stat_iklan").html("Rp " + parseInt(res.iklan[0]).toLocaleString('id'));
					$("#stat_ppn").html("Rp " + parseInt(res.ppn[0]).toLocaleString('id'));

					res_biaya_iklan = res.iklan[0];
				},
				error: function(xhr, status, errorMessage) {
					alert('Terjadi kesalahan 02, mohon coba kembali');
				}
			});

			<?php switch($_SESSION['level_os']) {
				case 1: ?>
					var tgt_0 = 13;
					var tgt_1 = [3, 4, 5, 6, 7, 10];
					var tgt_2 = [12];
				<?php break;
				case 5: ?>
					var tgt_0 = 7;
					var tgt_1 = [3];
					var tgt_2 = [8];
				<?php break;
			} ?>

			var dt = $('.dataTable').DataTable({
				"ajax" : '<?php echo $output['url_tables']; ?>',
				"order": [[ tgt_0, "desc" ]],
				"aoColumnDefs" : [
					{
						"targets"  : tgt_1,
						"render"   : function(data, type, row) {
							return "<span class='text-info'>Rp "+data+"</span>";
						}
					},
					{
						"targets"  : tgt_2,
						"orderable": false
					}
				],
				"oLanguage": {
					"sSearch"	: "Filter",
					"sInfo"		: "_START_ - _END_ dari _TOTAL_ Data",
					"sLengthMenu": "_MENU_ Baris",
					"oPaginate" : {
						"sNext"		: ">",
						"sPrevious" : "<"
					}
				},
				// "footerCallback": function ( row, data, start, end, display ) {
					// var api = this.api(), data;
		
					// // Remove the formatting to get integer data for summation
					// var intVal = function ( i ) {
					// 	return typeof i === 'string' ?
					// 		i.replace(/[\$,]/g, '')*1: 
					// 		typeof i === 'number' ?
					// 			i: 0;
					// };

					// // GROSS
					// total3 = api.column(3).data().reduce(function(a,b) {
					// 	return intVal(a) + intVal(b);
					// }, 0);
					// pageTotal3 = api.column(3).data().reduce(function(a,b) {
					// 	return intVal(a) + intVal(b);
					// }, 0);
					// $("#totalGrs").html(
					// 	`<strong>Rp&nbsp;`+pageTotal3.toLocaleString('id')+`</strong>`
					// );
		
					// // TRANSAKSI
					// total = api.column(4).data().reduce( function (a, b) {
					// 	return intVal(a) + intVal(b);
					// }, 0);
					// // Total over this page
					// pageTotal = api.column(4).data().reduce( function (a, b) {
					// 	return intVal(a) + intVal(b);
					// }, 0);
					// // Update footer
					// $("#totalSum").html(
					// 	`<strong>Rp&nbsp;`+pageTotal.toLocaleString('id')+`</strong>`
					// );

					// // NETTO
					// total2 = api.column(5).data().reduce(function(a,b) {
					// 	return intVal(a) + intVal(b);
					// }, 0);
					// pageTotal2 = api.column(5).data().reduce(function(a,b) {
					// 	return (intVal(a) + intVal(b));
					// }, 0) - res_biaya_iklan;
					// $("#totalNet").html(
					// 	`<strong>Rp&nbsp;`+pageTotal2.toLocaleString('id')+`</strong>`
					// );
				// }
			});

			<?php switch($_SESSION['level_os']) {
				case 1: ?>
					dt.column(6).visible(false);
					dt.column(7).visible(false);
					dt.column(9).visible(false);
					dt.column(10).visible(false);
					dt.column(13).visible(false);
				<?php break;
			} ?>
			$('a.toggle-vis').on( 'click', function (e) {
				e.preventDefault();
		
				// Get the column API object
				var column = dt.column( $(this).attr('data-column') );
		
				// Toggle the visibility
				column.visible( ! column.visible() );
			});
			// dt.ajax.reload();
			// dt.column(8).visible(false);
			$("[data-toggle='tooltip']").tooltip();
		});
	});
</script>

<?php include('foot_nav.php'); ?>