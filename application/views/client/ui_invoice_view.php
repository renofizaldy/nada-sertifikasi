<?php include('head_top.php'); ?>
<?php include('head_nav.php'); ?>

<?php $out = $output['res_invoice'][0]; ?>

<div class="my-3 my-md-5"><div id="cartNew" class="container"><?php echo form_open('req/put/invoice/'.$output['filter_get'], array('autocomplete'=>'off', 'enctype'=>'multipart/form-data')); ?>

	<input type="hidden" name="_cart" v-model="JSON.stringify(items)">
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label class="form-label semibold">No. Faktur</label>
						<input name="no_faktur" type="text" class="form-control" value="<?php echo $out['no_faktur']; ?>">
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<?php if (strlen($out['bukti_invoice']) > 3) { ?>
						<table class="table card-table">
							<tbody><tr>
								<td class="py-0 pl-0"><strong>Bukti Invoice</strong></td>
								<td class="py-0 pr-0 text-right">
									<strong><a target="_blank" href="<?php echo base_url('assets/u/'.$out['bukti_invoice']); ?>" class="">Lihat</a></strong> // 
									<a href="javascript:void(0);" v-on:click="dropImg('<?php echo $out['id']; ?>', '<?php echo $out['bukti_invoice']; ?>');" class="text-danger">Hapus</a>
								</td>
							</tr></tbody>
						</table>
					<?php } else { ?>
						<div class="form-group">
							<label class="form-label">Bukti Invoice</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="userfile">
								<label class="custom-file-label">Pilih file</label>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Detail pengiriman</h3>
				</div>
				<div class="card-body">
					<input type="hidden" name="id_invoice" value="<?php echo $out['id']; ?>">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label semibold">Email / Username</label>
								<input value="<?php echo htmlspecialchars_decode($out['buyer_email']); ?>" name="dp_email" type="text" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Nama Lengkap <span class="color-red">*</span></label>
								<input value="<?php echo htmlspecialchars_decode($out['buyer_nama']); ?>" name="dp_nama" type="text" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Telepon</label>
								<input value="<?php echo htmlspecialchars_decode($out['buyer_telepon']); ?>" name="dp_telepon" type="text" class="form-control">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label semibold">Alamat Lengkap <span class="color-red">*</span></label>
								<textarea name="dp_alamat" class="form-control" maxlength="255" required=""><?php echo str_replace(['<br />', '<br>'], "", htmlspecialchars_decode($out['buyer_alamat'])); ?></textarea>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Provinsi</label>
								<select name="dp_provinsi" class="form-control" disabled="">
									<option value="<?php echo $out['buyer_id_provinsi'] ?>"><?php echo $out['buyer_provinsi'] ?></option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Kota</label>
								<select name="dp_kota" class="form-control" disabled="">
									<option value="<?php echo $out['buyer_id_kota']; ?>"><?php echo $out['buyer_kota']; ?></option>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label semibold">Catatan</label>
								<textarea name="dp_catatan" class="form-control" maxlength="255"><?php echo str_replace(['<br />', '<br>'], "", htmlspecialchars_decode($out['buyer_catatan'])); ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Informasi pengiriman</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Kurir dan Servis</label>
								<input value="<?php echo htmlspecialchars_decode($out['info_kurir']); ?>" name="ip_kurir" type="text" class="form-control" placeholder="Contoh: JNE - Reguler">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">No. Pengiriman</label>
								<input value="<?php echo htmlspecialchars_decode($out['info_resi']); ?>" name="ip_resi" type="text" class="form-control">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<div class="selectgroup selectgroup-pills"><?php foreach($output['res_marplace'] as $row): ?>
							<label class="selectgroup-item">
								<input type="radio" name="ai_marketplace" value="<?php echo $row['id']; ?>" class="selectgroup-input" <?php echo ($out['id_marketplace'] == $row['id']) ? "checked":""; ?>>
								<span class="selectgroup-button"><?php echo $row['marketplace']; ?></span>
							</label>
						<?php endforeach; ?></div>
					</div>
					<div class="form-group">
						<label class="form-label semibold">Potongan Harga</label>
						<input name="ai_potongan_harga" type="text" class="form-control" v-model="cartDisc">
					</div>
					<div class="form-group">
						<label class="form-label semibold">Fee Marketplace <span class="text-danger">*</span></label>
						<input name="ai_fee_marketplace" type="text" class="form-control" v-model="cartFee" required="">
					</div>
					<div class="form-group">
						<label class="form-label semibold">Biaya Kirim</label>
						<input name="ai_biaya_kirim" type="text" class="form-control" v-model="cartOngkir">
					</div>
					<div class="form-group">
						<label class="form-label semibold">Catatan</label>
						<textarea name="ip_catatan" class="form-control"><?php echo str_replace(['<br />', '<br>'], "", htmlspecialchars_decode($out['info_catatan'])); ?></textarea>
					</div>
					<div class="form-group">
						<label class="form-label semibold">Waktu Pembelian</label>
						<input value="<?php echo $out['waktu_beli']; ?>" name="ai_waktu_beli" type="text" class="form-control datex" required="">
					</div>

					<div class="form-group">
						<label class="form-label">Status</label>
						<div class="selectgroup w-100">
							<label class="selectgroup-item">
								<input type="radio" name="status" value="0" class="selectgroup-input" <?php echo ($out['status'] == 0) ? "checked":""; ?>>
								<span class="selectgroup-button">Menunggu</span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="status" value="1" class="selectgroup-input" <?php echo ($out['status'] == 1) ? "checked":""; ?>>
								<span class="selectgroup-button">Diproses</span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="status" value="2" class="selectgroup-input" <?php echo ($out['status'] == 2) ? "checked":""; ?>>
								<span class="selectgroup-button">Dikirim</span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="status" value="3" class="selectgroup-input" <?php echo ($out['status'] == 3) ? "checked":""; ?>>
								<span class="selectgroup-button">Selesai</span>
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Daftar pembelian</h3>
				</div>
				<div class="card-body" style="padding:0">
					<table class="table card-table"><tbody>
						<tr v-for="barang in items">
							<td>
								<div>{{barang.name}}</div>
								<div><small><strong>{{barang.quantity}} Barang ({{ barang.weight }}kg)</strong> x Rp {{Math.trunc(barang.price).toLocaleString('id')}}</small></div>
								<div><small>{{barang.inquiry}}</small></div>
							</td>
							<td class="text-right">
								<div style="font-size: 13pt"><strong>Rp {{(barang.price * barang.quantity).toLocaleString('id')}}</strong></div>
								<!-- <div v-on:click="deleteInCart(barang)" class="badge badge-default">Hapus</div> -->
							</td>
						</tr>
					</tbody></table>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Ringkasan transaksi</h3>
				</div>
				<div class="card-body">
					<input type="hidden" name="rt_subtotal" :value="`${cartSubTotal}`">
					<table class="table card-table"><tbody>
						<tr>
							<td>Total Barang</td>
							<td class="text-right">
								<span>{{qtyProduk}} Barang</span>
							</td>
						</tr>
						<tr>
							<td>Total Berat</td>
							<td class="text-right">
								<span>{{qtyWeight}} Kg</span>
							</td>
						</tr>
						<tr>
							<td><strong>Subtotal / Gross</strong></td>
							<td class="text-right">
								<strong>Rp {{parseInt(cartSubTotal).toLocaleString('id')}}</strong>
							</td>
						</tr>
						<tr>
							<td>Potongan Harga</td>
							<td class="text-right">
								<span class="text-danger">Rp -{{parseInt(cartDisc).toLocaleString('id')}}</span>
							</td>
						</tr>
						<tr>
							<td>Fee Marketplace</td>
							<td class="text-right">
								<span class="text-danger">Rp -{{parseInt(cartFee).toLocaleString('id')}}</span>
							</td>
						</tr>
						<tr style="font-size: 14pt;">
							<td><strong>TOTAL TRANSAKSI</strong></td>
							<td class="text-right">
								<strong>Rp {{parseInt(cartTotal).toLocaleString('id')}}</strong>
							</td>
						</tr>
						<tr>
							<td>PPn 10%</td>
							<td class="text-right">
								<span class="text-danger">Rp -{{parseInt(cartPPn).toLocaleString('id')}}</span>
							</td>
						</tr>
						<tr>
							<td>Biaya Kirim</td>
							<td class="text-right">
								<span class="text-danger">Rp -{{parseInt(cartOngkir).toLocaleString('id')}}</span>
							</td>
						</tr>
						<tr style="font-size: 12pt;">
							<td><strong>NETTO</strong> <span data-toggle='tooltip' data-placement='top' title='Total Transaksi - (PPn + Biaya Kirim)'><i class="fe fe-help-circle"></i></span></td>
							<td class="text-right">
								<strong>Rp {{parseInt(cartNetto).toLocaleString('id')}}</strong>
							</td>
						</tr>
						<tr>
							<td>HPP Pusat</td>
							<td class="text-right">
								<span class="text-danger">Rp -{{parseInt(hppPusat).toLocaleString('id')}}</span>
							</td>
						</tr>
						<tr style="font-size: 12pt;">
							<td><strong>MARGIN</strong> <span data-toggle='tooltip' data-placement='top' title='Netto - HPP Pusat'><i class="fe fe-help-circle"></i></span></td>
							<td class="text-right">
								<strong>Rp {{(parseInt(cartNetto)-parseInt(hppPusat)).toLocaleString('id')}}</strong>
							</td>
						</tr>
					</tbody></table>
				</div>
			</div>
			<div class="text-right">
				<a href="<?php echo base_url('req/delete/invoice/'.$out['id'].'/'.$output['filter_get']); ?>" class="btn btn-outline-danger btn-lg mr-3">Hapus</a>
				<a href="<?php echo base_url('route/invoice/label/'.$out['id']); ?>" target="_blank" class="btn btn-outline-info btn-lg mr-3">Cetak Label</a>
				<button type="submit" class="btn btn-success btn-lg">Simpan</button>
			</div>
		</div>
	</div>

</form></div></div>

<script type="text/javascript">
	require(['vue', 'jquery', 'select2'], function(vue) {

		<?php $total_hpp = 0;
			foreach($output['res_items'] as $row) {
				$total_hpp += $row['hpp_pusat'] * $row['quantity'];
		} ?>

		localStorage.removeItem('_marment_inv_cart');
		// READ & APPEND
		var itemAppendx = [
			<?php foreach($output['res_items'] as $row): ?>
				{
					id    	: <?php echo $row['produk']; ?>,
					exact 	: "<?php echo $row['exact_code']; ?>",
					name  	: "<?php echo htmlspecialchars_decode($row['exact_name']); ?>",
					price 	: <?php echo $row['harga_satuan']; ?>,
					weight	: <?php echo $row['berat']; ?>,
					quantity: <?php echo $row['quantity']; ?>,
					inquiry : "<?php echo htmlspecialchars_decode($row['catatan']); ?>",
					hpp 	: <?php echo $row['hpp_pusat']; ?>
				},
			<?php endforeach; ?>
		];
		// WRITE
		localStorage.setItem('_marment_inv_cart', JSON.stringify(itemAppendx));
		var cartItem = JSON.parse(localStorage.getItem('_marment_inv_cart'));

		const uri_getCity     	= '<?php echo base_url('req/get/wilayah?type=kota&prov='); ?>';
		const uri_getProvince 	= '<?php echo base_url('req/get/wilayah?type=prov'); ?>';

		var Vue = require('vue');
		const cartNew = new Vue({
			el: '#cartNew',
			data: {
				items    		: cartItem,
				new_pro 		: false,
				pro_wght 		: 0,
				barangWgh 		: 0,
				prcChange 		: '',
				cartFee 		: <?php echo (!empty($out['fee_marketplace'])) ? $out['fee_marketplace'] : 0; ?>,
				cartDisc 		: <?php echo (!empty($out['potongan_harga'])) ? $out['potongan_harga'] : 0; ?>,
				cartOngkir 		: <?php echo (!empty($out['biaya_kirim'])) ? $out['biaya_kirim'] : 0; ?>,
				hppPusat 		: <?php echo $total_hpp; ?>,
				provList 		: [],
				cityList 		: [],
				corrList		: [],
				srvcList 		: [],
				barangQty 		: '',
				barangTot 		: 0,
				cartModal		: '',
				defaultProv 	: {},
				showDiskonChild : false
			},
			methods: {
				addToCart() {
					var input_pro = $("[name='pb_tipe']").val();
					var input_qty = $("[name='pb_jumlah']").val();
					var input_not = $("[name='pb_catatan']").val();

					if (input_pro && input_qty) {
						var input_pro_exact 	= $("[name='pb_tipe']").select2().find(":selected").data("exact");
						var input_pro_name     	= $("[name='pb_tipe']").select2().find(":selected").data("name");
						var input_pro_price_fix	= parseInt(this.barangTot);
						var input_pro_weight   	= $("[name='pb_tipe']").select2().find(":selected").data("weight");

						if (input_qty.length === 0 || input_qty == 0) {
							input_qty = 1;
						}

						var statusExist = 0;
						var itemId;
						for (i=0; i<cartItem.length; i++) {
							if (cartItem[i].id == input_pro) {
								statusExist = 1;
								itemId = i;
							}
						}
						if (statusExist == 1) {
							// READ & APPEND
							var itemAppend = {
								id    	: input_pro,
								exact 	: input_pro_exact,
								name  	: input_pro_name,
								price 	: input_pro_price_fix,
								weight	: input_pro_weight * parseInt(input_qty),
								quantity: parseInt(input_qty),
								inquiry : cartItem[itemId].inquiry+" "+input_not
							}
							// DELETE
							delete cartItem[itemId];
							cartItem = cartItem.filter(function(x) { return x !== null });
							
							// WRITE
							cartItem.push(itemAppend);
							localStorage.removeItem('_marment_inv_cart');
							localStorage.setItem('_marment_inv_cart', JSON.stringify(cartItem));

							location.reload();
						} else {
							// READ & APPEND
							var itemAppend = {
								id    	: input_pro,
								exact 	: input_pro_exact,
								name  	: input_pro_name,
								price 	: input_pro_price_fix,
								weight	: input_pro_weight * parseInt(input_qty),
								quantity: parseInt(input_qty),
								inquiry : input_not
							}
							
							// WRITE
							cartItem.push(itemAppend);
							localStorage.removeItem('_marment_inv_cart');
							localStorage.setItem('_marment_inv_cart', JSON.stringify(cartItem));

							location.reload();
						}
					}
				},
				deleteInCart(val) {
					if (confirm('Hapus dari keranjang?')) {
						for (i=0; i < cartItem.length; i++) {
							if (cartItem[i].id === val.id) {
								delete cartItem[i];
								cartItem = cartItem.filter(function(x) { return x !== null });
								localStorage.removeItem('_marment_inv_cart');
								localStorage.setItem('_marment_inv_cart', JSON.stringify(cartItem));
								location.reload();
							}
						}
					} else {
						return false;
					}
				},
				dropImg(id, invoice) {
					if (confirm('Harap ganti dengan bukti invoice baru')) {
						var uri = "req/delete/invoice_img/?id="+id+"&file_name="+invoice;
						window.location = "<?php echo base_url(); ?>" + uri;
					} else {
						return false;
					}
				},
				getCity(val) {
					var prov = val.target.value;

					$.ajax({
						url         : uri_getCity+prov,
						beforeSend  : function() {
							$("[name='dp_kota']").prop('disabled', true);
						}
					}).done(data => {
						$("[name='dp_kota']").removeAttr('disabled');
						this.cityList = data.kota;
					});
					$("[name='dp_kota']").prop('selectedIndex', 0);
				}
			},
			computed: {
				qtyProduk 		: function() {
					var tes = 0;
					for (i=0; i<cartItem.length; i++) {
						tes += (cartItem[i].quantity);
					} return tes;
				},
				qtyWeight 		: function() {
					var qtyWeight = 0;
					for (i=0; i<cartItem.length; i++) {
						qtyWeight += (cartItem[i].weight);
					} return qtyWeight;
				},
				// cartAdtCost 	: function() {
				// 	if (this.cartAdtCost.length > 0) {
				// 		return this.cartAdtCost;
				// 	} else {
				// 		return 0;
				// 	}
				// },
				cartSubTotal  	: function() {
					var total = [];
					Object.entries(cartItem).forEach(([key, val]) => {
						total.push(val.price*val.quantity)
					});
					return total.reduce(function(total, num) { return total + num }, 0);
				},
				cartTotal 		: function() {
					return parseInt(this.cartSubTotal) - (parseInt(this.cartDisc) + parseInt(this.cartFee));
				},
				cartPPn 		: function() {
					return (parseInt(this.cartSubTotal) - parseInt(this.cartDisc)) * (10/100);
				},
				cartNetto 		: function() {
					return parseInt(this.cartSubTotal) - (parseInt(this.cartDisc) + parseInt(this.cartFee)) - (parseInt(this.cartPPn) + parseInt(this.cartOngkir));
				}
			},
			mounted: function() {
				// window.onbeforeunload = function() {
				// 	return "Are yo sure?";
				// };

				$.ajax({
					url         : uri_getProvince
				}).done(data => {
					this.provList = data.provinsi;
				});

				var self = this;
				$("[name='pb_tipe']").change(function() {
					setTimeout(function() {
						var input_prc  = $("[name='pb_tipe']").select2().find(":selected").data("price");
						self.barangPrc = input_prc;
						self.barangQty = 1;

						self.barangTot = self.barangPrc;
						self.pro_wght  = $("[name='pb_tipe']").select2().find(":selected").data("weight");
						self.barangWgh = $("[name='pb_tipe']").select2().find(":selected").data("weight");
					}.bind(self));
				});
			},
			watch: {
				'barangQty': function(val) {
					if (val < 0 || val == 0) {
						this.barangQty = 1;
					} else {
						if (this.new_pro == true) {
							this.barangTot = this.barangTot;
							this.pro_wght  = this.pro_wght;
						} else {
							this.barangTot = (this.barangPrc*val);
							this.pro_wght  = (this.barangWgh*val);
						}
					}
				},
				'new_pro'  : function(val) {
					if (val = true) {
						this.barangPrc = 0;
						this.barangQty = 0;
						this.barangTot = 0;
						this.pro_wght  = 0;
					}
				}
			}
		});
	});
</script>

<?php include('foot_nav.php'); ?>