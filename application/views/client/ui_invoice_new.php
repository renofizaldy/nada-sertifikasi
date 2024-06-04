<?php include('head_top.php'); ?>
<?php include('head_nav.php'); ?>

<div class="my-3 my-md-5"><div id="cartNew" class="container"><?php echo form_open('req/post/invoice', ['autocomplete'=>'off', 'enctype'=>'multipart/form-data']); ?>

	<input type="hidden" name="_cart" v-model="JSON.stringify(items)">
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Pilih barang</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<label class="form-label">Tipe Produk</label>
								<select name="pb_tipe" type="text" class="form-control select2">
									<option selected="true" disabled="true">Pilih</option>
									<?php foreach($output['res_produk'] as $row): ?>
										<option value="<?php echo $row['id']; ?>" 
											data-order="<?php echo $row['id_order']; ?>"
											data-exact="<?php echo $row['exact_code'] ?>" 
											data-name="<?php echo $row['exact_name']; ?>" 
											data-weight="<?php echo $row['berat']; ?>" 
											data-price="<?php echo $row['harga_netto']; ?>"><?php echo $row['exact_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="form-label">Jumlah</label>
								<input name="pb_jumlah" type="text" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label">Harga Total</label>
						<input name="pb_harga" type="text" class="form-control" v-model="barangTot">
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-primary" v-on:click="addToCart()">Tambahkan <i class="fe fe-arrow-right"></i></button>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label class="form-label semibold">No. Faktur</label>
						<input name="no_faktur" type="text" class="form-control" value="">
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label class="form-label">Bukti Invoice</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" name="userfile">
							<label class="custom-file-label">Pilih file</label>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Detail pengiriman</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label semibold">Email / Username</label>
								<input name="dp_email" type="text" class="form-control" value="">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Nama Lengkap <span class="text-danger">*</span></label>
								<input name="dp_nama" type="text" class="form-control" required="" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Telepon</label>
								<input name="dp_telepon" type="text" class="form-control" value="">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label semibold">Alamat Lengkap <span class="text-danger">*</span></label>
								<textarea name="dp_alamat" class="form-control" maxlength="255" required=""></textarea>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Provinsi <span class="text-danger">*</span></label>
								<select name="dp_provinsi" class="form-control" v-on:change="getCity">
									<option selected="true" disabled="">Pilih</option>
									<option v-for="prov in provList" :value="`${prov.id}`">{{prov.name}}</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">Kota <span class="text-danger">*</span></label>
								<select name="dp_kota" class="form-control" disabled="">
									<option selected="true" disabled="">Pilih</option>
									<option v-for="city in cityList" :value="`${city.id}`">{{city.name}}</option>
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group mb-0">
								<label class="form-label semibold">Catatan Pengiriman</label>
								<textarea name="dp_catatan" class="form-control" maxlength="255"></textarea>
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
								<input name="ip_kurir" type="text" class="form-control" value="Sentral Cargo">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label semibold">No. Pengiriman</label>
								<input name="ip_resi" type="text" class="form-control" value="">
							</div>
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
								<div><small><strong>{{barang.quantity}} Barang ({{ barang.weight }}kg)</strong> x Rp {{Math.trunc(barang.price / barang.quantity).toLocaleString('id')}}</small></div>
								<div><small>{{barang.inquiry}}</small></div>
							</td>
							<td class="text-right">
								<div style="font-size: 13pt"><strong>Rp {{barang.price.toLocaleString('id')}}</strong></div>
								<div v-on:click="deleteInCart(barang)" class="badge badge-default" style="cursor:pointer">Hapus</div>
							</td>
						</tr>
					</tbody></table>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<div class="selectgroup selectgroup-pills"><?php foreach($output['res_marplace'] as $row): ?>
							<label class="selectgroup-item">
								<input type="radio" name="ai_marketplace" value="<?php echo $row['id']; ?>" class="selectgroup-input">
								<span class="selectgroup-button"><?php echo $row['marketplace']; ?></span>
							</label>
						<?php endforeach; ?></div>
					</div>
					<div class="form-group">
						<label class="form-label semibold">Potongan Harga <span class="text-danger">*</span></label>
						<input name="ai_potongan_harga" type="text" class="form-control" v-model="cartDisc" required="">
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
						<label class="form-label semibold">Catatan Informasi</label>
						<textarea name="ip_catatan" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label class="form-label semibold">Waktu Pembelian <span class="text-danger">*</span></label>
						<input name="ai_waktu_beli" type="text" class="form-control datex" required="">
					</div>
					<div class="form-group">
						<label class="form-label">Status</label>
						<div class="selectgroup w-100">
							<label class="selectgroup-item">
								<input type="radio" name="status" value="0" class="selectgroup-input">
								<span class="selectgroup-button">Menunggu</span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="status" value="1" class="selectgroup-input" checked="">
								<span class="selectgroup-button">Diproses</span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="status" value="2" class="selectgroup-input">
								<span class="selectgroup-button">Dikirim</span>
							</label>
							<label class="selectgroup-item">
								<input type="radio" name="status" value="3" class="selectgroup-input">
								<span class="selectgroup-button">Selesai</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Ringkasan transaksi</h3>
				</div>
				<div class="card-body">
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
							<td><strong>NETTO</strong> <span data-toggle='tooltip' data-placement='top' title='Total Transaksi - Biaya Kirim'><i class="fe fe-help-circle"></i></span></td>
							<td class="text-right">
								<strong>Rp {{parseInt(cartNetto).toLocaleString('id')}}</strong>
							</td>
						</tr>
					</tbody></table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-2">
					<button v-on:click="resetCart();" type="button" class="btn btn-secondary btn-lg" style="width: 100%">Reset</button>
				</div>
				<div class="col-md-4">
					<button type="submit" class="btn btn-success btn-lg" style="width: 100%">Simpan</button>
				</div>
			</div>
		</div>
	</div>

</form></div></div>

<script type="text/javascript">
	require(['vue', 'jquery', 'select2'], function(vue) {

		var cartItem = JSON.parse(localStorage.getItem('_marment_inv_cart'));
		if (cartItem == null) {
			localStorage.setItem('_marment_inv_cart', JSON.stringify([]));
			location.reload();
		}

		const uri_getCity     	= '<?php echo base_url('req/get/wilayah?type=kota&prov='); ?>';
		const uri_getProvince 	= '<?php echo base_url('req/get/wilayah?type=prov'); ?>';


		const Vue = require('vue');
		const cartNew = new Vue({
			el: '#cartNew',
			data: {
				items    		: cartItem,
				new_pro 		: false,
				pro_wght 		: 0,
				barangWgh 		: 0,
				prcChange 		: '',
				cartFee 		: 0,
				cartDisc 		: 0,
				cartOngkir 		: 0,
				provList 		: [],
				cityList 		: [],
				corrList		: [],
				srvcList 		: [],
				barangQty 		: '',
				barangTot 		: 0,
				cartModal		: '',
				showDiskonChild : false
			},
			methods: {
				addToCart() {
					var input_pro = $("[name='pb_tipe']").val();
					var input_qty = $("[name='pb_jumlah']").val();

					if (input_pro && input_qty) {
						var input_pro_order 	= $("[name='pb_tipe']").select2().find(":selected").data("order");
						var input_pro_exact 	= $("[name='pb_tipe']").select2().find(":selected").data("exact");
						var input_pro_name     	= $("[name='pb_tipe']").select2().find(":selected").data("name");
						var input_pro_price_fix	= parseInt(this.barangTot);
						var input_pro_weight   	= $("[name='pb_tipe']").select2().find(":selected").data("weight");

						if (input_qty.length === 0 || input_qty == 0) {
							input_qty = 1;
						}

						var statusExist = 0;
						var itemId;
						for (i=0; i<this.items.length; i++) {
							if (this.items[i].id == input_pro) {
								statusExist = 1;
								itemId = i;
							}
						}
						if (statusExist == 1) {
							// READ & APPEND
							var itemAppend = {
								id    	: input_pro,
								order 	: input_pro_order,
								exact 	: input_pro_exact,
								name  	: input_pro_name,
								price 	: Math.round(input_pro_price_fix),
								weight	: input_pro_weight * parseInt(input_qty),
								quantity: parseInt(input_qty)
							}
							// DELETE
							delete this.items[itemId];
							this.items = this.items.filter(function(x) { return x !== null });
							
							// WRITE
							this.items.push(itemAppend);
							localStorage.removeItem('_marment_inv_cart');
							localStorage.setItem('_marment_inv_cart', JSON.stringify(this.items));

							// location.reload();
						} else {
							// READ & APPEND
							var itemAppend = {
								id    	: input_pro,
								order 	: input_pro_order,
								exact 	: input_pro_exact,
								name  	: input_pro_name,
								price 	: Math.round(input_pro_price_fix),
								weight	: input_pro_weight * parseInt(input_qty),
								quantity: parseInt(input_qty)
							}
							
							// WRITE
							this.items.push(itemAppend);
							localStorage.removeItem('_marment_inv_cart');
							localStorage.setItem('_marment_inv_cart', JSON.stringify(this.items));

							// location.reload();
						}
					}
				},
				deleteInCart(val) {
					for (i=0; i < this.items.length; i++) {
						if (this.items[i].id === val.id) {
							delete this.items[i];
							this.items = this.items.filter(function(x) { return x !== null });
							localStorage.removeItem('_marment_inv_cart');
							localStorage.setItem('_marment_inv_cart', JSON.stringify(this.items));
						}
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
				},
				resetCart() {
					localStorage.removeItem('_marment_inv_cart');
					location.reload();
				}
			},
			computed: {
				qtyProduk 		: function() {
					var tes = 0;
					for (i=0; i<this.items.length; i++) {
						tes += (this.items[i].quantity);
					} return tes;
				},
				qtyWeight 		: function() {
					var qtyWeight = 0;
					for (i=0; i<this.items.length; i++) {
						qtyWeight += (this.items[i].weight);
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
					Object.entries(this.items).forEach(([key, val]) => {
						total.push(val.price)
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