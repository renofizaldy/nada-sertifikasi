<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
	<meta http-equiv=Content-Type content="text/html; charset=windows-1252"> 
	<meta name=ProgId content=Excel.Sheet> 
	<meta name=Generator content="Microsoft Excel 11"> 
	<style> <!--table @page{} --> 
		table th{ text-align: left;}
	</style> 
	<!--[if gte mso 9]><xml> <x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet> <x:Name>Sheet1</x:Name> <x:WorksheetOptions><x:Panes> </x:Panes></x:WorksheetOptions> </x:ExcelWorksheet></x:ExcelWorksheets></ x:ExcelWorkbook> </xml> <![endif]-->
</head>
<body>
<table>
	<thead>
		<tr>
			<th>id</th>
			<th>id_cabang</th>
			<th>nama_cabang</th>
			<th>jenis</th>
			<th>bahan</th>
			<th>nomor</th>
			<th>jumlah</th>
			<th>id_material</th>
			<th>kode_material</th>
			<th>nama_material</th>
			<th>satuan_material</th>
			<th>harga_material</th>
			<th>exact_code</th>
			<th>exact_name</th>
			<th>id_komponen</th>
			<th>nomor_komponen</th>
			<th>kode_komponen</th>
			<th>nama_komponen</th>
			<th>satuan_komponen</th>
			<th>harga_komponen</th>
			<th>exact_code_komponen</th>
			<th>exact_name_komponen</th>
			<th>hpp</th>
			<th>tgl_input</th>
			<th>tgl_respon</th>
			<th>tgl_rencana</th>
			<th>tgl_realisasi</th>
			<th>tgl_kirim</th>
			<th>status</th>
		</tr>
	</thead>
	<tbody><?php foreach($output as $row): ?>
		<tr>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['rpl_cabang_id']; ?></td>
			<td><?php echo $row['rpl_cabang_nama']; ?></td>
			<td><?php echo $row['rpl_jenis']; ?></td>
			<td><?php echo $row['rpl_bahan']; ?></td>
			<td><?php echo $row['rpl_nomor']; ?></td>
			<td><?php echo $row['rpl_jumlah']; ?></td>
			<td><?php echo $row['rpl_material_id']; ?></td>
			<td><?php echo $row['rpl_material_kode']; ?></td>
			<td><?php echo $row['rpl_material_nama']; ?></td>
			<td><?php echo $row['rpl_material_satuan']; ?></td>
			<td><?php echo $row['rpl_material_harga']; ?></td>
			<td><?php echo $row['rpl_exact_code']; ?></td>
			<td><?php echo $row['rpl_exact_name']; ?></td>
			<td><?php echo $row['rpl_komp_id']; ?></td>
			<td><?php echo $row['rpl_komp_nomor']; ?></td>
			<td><?php echo $row['rpl_komp_kode']; ?></td>
			<td><?php echo $row['rpl_komp_nama']; ?></td>
			<td><?php echo $row['rpl_komp_satuan']; ?></td>
			<td><?php echo $row['rpl_komp_harga']; ?></td>
			<td><?php echo $row['rpl_komp_exact_code']; ?></td>
			<td><?php echo $row['rpl_komp_exact_name']; ?></td>
			<td><?php echo $row['rpl_return_harga']; ?></td>
			<td><?php echo $row['rpl_tgl_input']; ?></td>
			<td><?php echo $row['rpl_tgl_respon']; ?></td>
			<td><?php echo $row['rpl_tgl_rencana']; ?></td>
			<td><?php echo $row['rpl_tgl_realisasi']; ?></td>
			<td><?php echo $row['rpl_tgl_kirim']; ?></td>
			<td><?php echo $row['rpl_status']; ?></td>
		</tr>
	<?php endforeach; ?></tbody>
</table>
</body></html>