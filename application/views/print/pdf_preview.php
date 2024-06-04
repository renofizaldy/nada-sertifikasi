<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PREVIEW</title>

	<?php foreach($stylesheet as $ss): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $ss; ?>">
	<?php endforeach; ?>

	<style type='text/css'>
		@page { size: A4 }
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
			width:50%;
			float:left; 
			margin:0 auto; 
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
			font-family: 'Arial', sans-serif;
			font-size: 12px;
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
		.sheet_number {
			float:left;
			text-align:center;
			width:100%
		}
		.sheet_number h3 {
			margin:0;
			color:#666;
			font-family:'Tahoma'
		}
		.footer {
			position: relative;
			margin-top: 940px;
		}
		.footer img {
			width: 100%;
			height: 40px;
		}

		#img_foot {
		    width: 60px;
		    height: 60px;
		    overflow: hidden;
		}
		#img_foot img {
		    border: 1px solid #CCC;
		    width:60px;
		}
		.heading {
			font-family: 'Arial', sans-serif;
			font-size: 14px;
			font-weight: bold;
			width: 370px;
			text-align: center;
			padding-bottom: 5px;
			border-bottom: 1px solid black;
		}

		table.biodata tr td.title {
			padding-right: 30px;
			padding-bottom: 5px;
		}
		table.biodata tr td.content {
			padding-left: 10px;
		}
	</style>
</head>
<body class="A4">
<section class="sheet" style="padding:50px 70px;">
	<div id="container">
		<div class='heading'>
			<div class="font-bold">
				KEPOLISIAN NEGARA REPUBLIK INDONESIA
				<br>
				RESORT KOTA SIDOARJO
				<br>
				Jalan. RA. Kartini No. 87-A, Sidoarjo 61218
			</div>
		</div>
		<div class='wrapper-full'>
			<div class='wrapper-content'>
				<div class="logo" style="text-align: center; font-weight: bold;">
					<img src="<?php echo $output['res_logo']; ?>">
					<div style="text-decoration: underline; font-size: 15px;">SURAT TANDA LAPOR KEHILANGAN</div>
					<div style="font-size: 13px;">Nomor: ...<!-- STLK / 9380 / XI / YAN.2.5 / 2018 / SPKT / RESTA SDA --></div>
				</div>
				<div class="" style="margin-top: 15px; font-size: 13px; text-align: justify;">
					<p>Yang bertanda tangan di bawah ini menerangkan dengan sebenarnya bahwa pada hari <?php echo $output['res_date_top']; ?>, telah datang seseorang mengaku bernama:</p>
					<table class="biodata">
						<tr>
							<td class="title">Nama</td>
							<td class="separator">:</td>
							<td class="content"><b><?php echo $output['res_detail']['nama']; ?></b></td>
						</tr>
						<tr>
							<td class="title">Tempat / Tgl Lahir</td>
							<td class="separator">:</td>
							<td class="content"><?php echo $output['res_detail']['tgl_lahir']; ?></td>
						</tr>
						<tr>
							<td class="title">Jenis Kelamin</td>
							<td class="separator">:</td>
							<td class="content"><?php echo $output['res_detail']['kelamin']; ?></td>
						</tr>
						<tr>
							<td class="title">Agama</td>
							<td class="separator">:</td>
							<td class="content"><?php echo $output['res_detail']['agama']; ?></td>
						</tr>
						<tr>
							<td class="title">Pekerjaan</td>
							<td class="separator">:</td>
							<td class="content"><?php echo $output['res_detail']['pekerjaan']; ?></td>
						</tr>
						<tr>
							<td class="title">Alamat</td>
							<td class="separator">:</td>
							<td class="content"><?php echo $output['res_detail']['alamat']; ?></td>
						</tr>
						<tr>
							<td class="title">Telepon</td>
							<td class="separator">:</td>
							<td class="content"><?php echo $output['res_detail']['telepon']; ?></td>
						</tr>
					</table>
					<p>Menerangkan bahwa pelapor telah kehilangan surat / barang berupa :</p>
					<ul><?php foreach($output['res_berkas'] as $row): ?>
						<li><?php echo $row['jumlah']; ?> lembar <?php echo $row['berkas']; ?> nomor identitas <b><?php echo $row['identitas_nomor']; ?></b> a/n <b><?php echo $row['identitas_nama']; ?></b></li>
					<?php endforeach; ?></ul>
					<p>Pelapor tersebut pada point A di atas tidak mengetahui secara jelas waktu kehilangan surat / barang penting lainnya dan diperkirakan hilang dalam perjalanan disekitar wilayah Kab. Sidoarjo.</p>
					<p>Surat keterangan ini tidak berlaku sebagai pengganti surat / barang yang hilang dan kepada pemilik <b>SEGERA</b> mengurus sesuai dengan kepentingan sehubungan dengan hilangnya surat / barang tersebut di atas adapun isi laporan di luar tanggung jawab penerima laporan jika di kemudian hari ternyata isi laporan tidak sesuai dengan sebenarnya.</p>
					<p>Demikian surat keterangan ini di buat dengan sebenarnya dan berlaku selama <b>DUA MINGGU</b> sejak tanggal dikeluarkan.</p>
				</div>
			</div>
		</div>
		<div class='wrapper-full' style="margin-top: 50px;">
			<div class='wrapper-content'>
				<table style="text-align: left; left: 400px; position: relative; border-bottom: 1px solid #333">
					<tr>
						<td>Dikeluarkan di</td>
						<td>:</td>
						<td>Sidoarjo</td>
					</tr>
					<tr>
						<td>Pada tanggal</td>
						<td>:</td>
						<td><?php echo $output['res_date_bot']; ?></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="wrapper-left">
			<div class='wrapper-content' style="font-weight: bold; text-align: center">
				<div class="pelapor">PELAPOR</div>
				<div class="pelapor_name" style="margin-top: 100px;"><?php echo $output['res_detail']['nama']; ?></div>
			</div>
		</div>
		<div class="wrapper-right">
			<div class='wrapper-content' style="font-weight: bold; text-align: center">
				<div class="pelapor">JABATAN PETUGAS</div>
				<div class="pelapor_name" style="margin-top: 100px;">
					<span style="text-decoration: underline;">NAMA PETUGAS</span>
					<br>
					<span>PANGKAT NRP</span>
				</div>
			</div>
		</div>

	</div>
	<div class="footer">
		
	</div>
</section>
<script type="text/javascript">
	<?php if ($this->input->get('print')) { ?>
		window.print();
	<?php } ?>
</script>
</body></html>