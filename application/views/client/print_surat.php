<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cetak Data Surat</title>

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
		.tbody-h tr th,
		.tbody-r tr td {
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
							<div>
								Nada Soraya
								<br>
								Daftar <?= $top_title; ?>
							</div>
						</div>
					</div>
				</div>
				<div>
					<table style="width: 100%;box-sizing: border-box;">
						<thead class="tbody-h">
							<tr>
								<th>No. Surat</th>
								<th>Pengirim</th>
								<th>Waktu</th>
								<th>Tempat</th>
								<th>Lampiran</th>
								<th>Perihal</th>
							</tr>
						</thead>
						<tbody class="tbody-r">
							<?php foreach($output['res_items'] as $row): ?>
							<tr>
								<td><?= $row['nomor_surat']; ?></td>
								<td><?= $row['nama_pengirim']; ?></td>
								<td><?= timepub($row['waktu'], 'Y/M/D'); ?></td>
								<td><?= $row['tempat']; ?></td>
								<td><?= $row['lampiran']; ?></td>
								<td><?= $row['perihal']; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	// window.print();
</script>
</body></html>