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
			<th>exact_code</th>
			<th>exact_name</th>
			<th>nomor</th>
			<th>kode</th>
			<th>nama</th>
			<th>satuan</th>
			<th>harga</th>
		</tr>
	</thead>
	<tbody><?php foreach($output as $row): ?>
		<tr>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['exact_kode']; ?></td>
			<td><?php echo $row['exact_nama']; ?></td>
			<td><?php echo $row['komp_nomor']; ?></td>
			<td><?php echo $row['komp_kode']; ?></td>
			<td><?php echo $row['komp_nama']; ?></td>
			<td><?php echo $row['komp_satuan']; ?></td>
			<td><?php echo $row['komp_harga']; ?></td>
		</tr>
	<?php endforeach; ?></tbody>
</table>
</body></html>