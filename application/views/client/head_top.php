<!doctype html>
<html lang="id" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="Content-Language" content="en" />

	<meta name="msapplication-TileColor" content="#2d89ef">
	<meta name="theme-color" content="#4188c9">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">

	<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/'); ?>apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/'); ?>favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/'); ?>favicon-16x16.png">
	<link rel="manifest" href="<?= base_url('assets/'); ?>site.webmanifest">

	<title><?= $top_title.$site_name; ?></title>

	<link rel="stylesheet" href="<?= base_url('assets/v2/fonts/font-awesome/css/'); ?>font-awesome.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/v2/fonts/source-sans-pro/'); ?>font-sspro.css">

	<script src="<?= $assets; ?>js/require.min.js"></script>
	<script>
		requirejs.config({
			baseUrl: '<?= $assets; ?>'
		});
	</script>

	<!-- Dashboard Core -->
	<link href="<?= $assets; ?>css/dashboard.css" rel="stylesheet" />
	<link href="<?= $assets; ?>css/datepicker.min.css" rel="stylesheet" />
	<link href="<?= $assets; ?>plugins/select2/select2.min.css" rel="stylesheet" />
	<link href="<?= $assets; ?>plugins/summernote/summernote-bs4.css" rel="stylesheet" />
	<link href="<?= $assets; ?>plugins/air-datepicker/css/datepicker.min.css" rel="stylesheet" />
	<link href="<?= $assets; ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />

	<?php if ($stylesheet) { foreach($stylesheet as $css): ?>
		<link href="<?= $css; ?>" rel="stylesheet" />
	<?php endforeach; } ?>

	<script src="<?= $assets; ?>js/dashboard.js"></script>

	<!-- Input Mask Plugin -->
	<script src="<?= $assets; ?>plugins/input-mask/plugin.js"></script>

	<style type="text/css">
		.menux {
			position: absolute; transform: translate3d(-181px, 20px, 0px); top: 0px; left: 0px; will-change: transform;
		}
		.nav-tabs .nav-link {
			padding: 1rem 0.75rem !important;
		}
	</style>
</head>
<body class="">
	<div class="page">
		<div class="page-main">