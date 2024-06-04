<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cetak Label</title>

	<?php foreach($stylesheet as $ss): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $ss; ?>">
	<?php endforeach; ?>

	<style type='text/css'>
		@page { size: A4 }
		body {
			font-size: 12pt;
		}
		a { text-decoration:none; color:#3582cb; font-weight:bold;}
		.dim-produk {
			font-family: "Tahoma";
			font-size:10px;
		}
		.table-warna {
			font-family: "Tahoma";
			font-size:9px;
			border-width: 1px;
			border-color: #d5d5d5;
			border-collapse: collapse; 
			text-align:justify; 
			line-height:1.7em;
		}
		.table-warna th {
			padding:4px;
			color:#333;
			border:1px solid #e6e6e6;
			text-transform:uppercase;
		}
		.table-warna td {
			padding:4px;
			color:#333;
			text-transform:uppercase;
		}
		.table-warna2 {
			font-size:10px;
			border-width: 1px;
			border-color: #d5d5d5;
			border-collapse: collapse; 
			text-align:justify; 
			line-height:1.7em;
		}
		.table-warna2 th {
			padding:4px;
			color:#333;
			text-transform:uppercase;
		}
		.table-warna2 td {
			border:1px solid #e6e6e6;
			padding:4px;
			color:#333;
			text-transform:uppercase;
		}
		.style1 {
			color: #FFFFFF;
		}
		#container { 
			width:100%; 
			background:none; 
			float:left; 
			margin-bottom: 15px; 
		}
		.wrapper-full { 
			width:100%; 
			margin:0 auto; 
			margin-bottom:20px;
		}
		.wrapper-left { 
			width:90%;
			float:left; 
			margin:0 auto 0 5%; 
		}
		.wrapper-right { 
			width:50%;
			float:right; 
			margin:0 auto; 
		}
		.wrapper-right img {
			max-width:100%;
			max-height:310px;
		}
		.wrapper-content { 
			font-family: 'Arial Narrow', sans-serif;
			font-size: 11pt;
			border: 1px solid #CCC;
			padding: 10px 13px;
		}
		#clear { clear: both; }

		.spacer30 {
			margin-top: 30px;
			float: left;
		}
		.spacer20 {
			margin-top: 20px;
			float: left;
		}
		.spacer10 {
			margin-top: 10px;
			float: left;
		}
		.spacer10 {
			margin-top: 5px;
			float: left;
		}
		.heading {

    		border-bottom: 1px solid #aaa;
    		padding-bottom: 0.8rem;
    		margin-bottom: 0.8rem;

		}
		.from-top {
			margin: 0 0 0 2rem;
			font-size: 8pt;	
		}
		.d-flex {
			display: flex;
		}
		.f-9 {
			font-size: 9pt;
		}
		.f-10 {
			font-size: 10pt;
		}
		.f-11 {
			font-size: 11pt;
		}
		.f-13 {
			font-size: 13pt;
		}
		.f-14 {
			font-size: 14pt;
		}
		.f-17 {
			font-size: 17pt;
		}
		.col-gray {
			color: #666;
		}

		.ico-instagram {
			width: 8pt;
			position: absolute;
			margin-left: -1rem;
			margin-top: 2px;
		}
		.ico-whatsapp {
			width: 8pt;
			position: absolute;
			margin-left: 0;
			margin-top: 4px;
		}
		.flex-fill {
			-ms-flex: 1 1 auto!important;
			flex: 1 1 auto!important;
		}
		.text-right {
			text-align: right;
		}
	</style>
</head>
<body class="A4">
<section class="sheet" style="padding:10px;">
	<div id="container">
		<div class='wrapper-left'>
			<div class='wrapper-content'>
				<div class='heading'>
					<div class="d-flex f-11" style="margin-bottom: .3rem">
						<div class="flex-fill">
							<img src="<?php echo base_url('assets/v2/images/logo-sm.png'); ?>" style="height: 2.2rem">
							<div>
								PT. Gatra Mapan (Div. Marketplace)
								<br>
								JL. Tegal Mapan, No.18, Pakisjajar - Kab. Malang
							</div>
						</div>
						<div class="flex-fill text-right">
							<img src="<?php echo $output['mplace']['logo']; ?>" style="height: 2.2rem">
							<div><?php echo $output['mplace']['url']; ?></div>
						</div>
					</div>
					<span>
					</span>
				</div>
				<div class="d-flex" style="padding: .5rem 0;">
					<div class="flex-fill" style="width:60%;font-size:22pt">
						Penerima:<br>
						<strong><?php echo $output['res_invoice'][0]['buyer_nama']; ?></strong><br>
						<?php echo htmlspecialchars_decode($output['res_invoice'][0]['buyer_alamat']); ?><br>
						<?php echo $output['res_invoice'][0]['buyer_kota'].", ".$output['res_invoice'][0]['buyer_provinsi']; ?><br>
						<?php echo $output['res_invoice'][0]['buyer_telepon']; ?>
					</div>
					<div class="flex-fill text-right" style="font-size: 17pt">
						<div>Total Berat: <strong><?php echo $output['res_invoice'][0]['total_berat']; ?>kg</strong></div>
						<br>
						<strong><?php echo $output['res_invoice'][0]['info_kurir']; ?></strong>
						<br>
						<?php echo $output['res_invoice'][0]['info_resi']; ?>
					</div>
				</div>

				<?php if (strlen($output['res_invoice'][0]['buyer_catatan']) > 0) { ?>
					<div style="background: #feffc8;padding: 1rem;margin: .5rem 0 1rem;color: #6d381f;">
						<strong>Catatan dari Penerima:</strong>
						<div style="font-size:17pt;font-style:italic">"<?php echo $output['res_invoice'][0]['buyer_catatan']; ?>"</div>
					</div>
				<?php } ?>

				<hr style="border: .5px dashed #CCC;">

				<strong>Daftar Barang:</strong>
				<?php foreach($output['res_items'] as $items): ?>
				<div class="d-flex col-gray" style="padding:.5rem 0;font-size: 17pt;">
					<div style="flex: 1 1 auto;"><?php echo $items['exact_name']; ?></div>
					<div style="flex: 0 0 auto;"><?php echo $items['quantity']*$items['koli'] ?> Koli (<?php echo $items['berat']; ?>kg)</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	// <?php if ($output['print']) { ?> window.print(); <?php } ?>
</script>
</body></html>