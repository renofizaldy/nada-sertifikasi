<!DOCTYPE html><html>
<head lang="id">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="robot" content="noindex, nofollow">

	<title><?php echo $top_title.$site_name; ?></title>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?php foreach($stylesheet as $ss): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url($assets.$ss); ?>">
	<?php endforeach; ?>
</head>
<body>
	<div class="page-center">
		<div class="page-center-in">
			<div class="container-fluid">
				<?php echo form_open('', array('class'=>'sign-box')); ?>
					<div style="margin-bottom: 20px;">
						<img src="<?php echo base_url($assets.'img/logo_m.png'); ?>" style="max-width:100%">
					</div>
					<header class="sign-title">Masuk Akun</header>
					<div class="form-group">
						<label>Username</label>
						<input name="username" type="text" class="form-control" placeholder="" required="" />
					</div>
					<div class="form-group">
						<label>Password</label>
						<input name="password" type="password" class="form-control" placeholder="" required="" />
					</div>
					<button type="submit" class="btn btn-rounded">Masuk</button>
				</form>
			</div>
		</div>
	</div><!--.page-center-->

	<?php foreach($javascript as $js): ?>
		<script type="text/javascript" src="<?php echo base_url($assets.$js); ?>"></script>
	<?php endforeach; ?>

	<script>
		$(function() {
			$('.page-center').matchHeight({
				target: $('html')
			});

			$(window).resize(function(){
				setTimeout(function(){
					$('.page-center').matchHeight({ remove: true });
					$('.page-center').matchHeight({
						target: $('html')
					});
				},100);
			});
		});
	</script>
	<script src="<?php echo $assets; ?>/js/app.js"></script>
</body></html>