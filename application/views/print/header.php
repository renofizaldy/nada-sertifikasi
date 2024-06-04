

	<header class="site-header">
		<div class="container-fluid">
			<a href="#" class="site-logo-text">POLRESTA SIDOARJO</a>
			<button class="hamburger hamburger--htla">
				<span>toggle menu</span>
			</button>
			<div class="site-header-content">
				<div class="site-header-content-in">
					<div class="dropdown dropdown-typical">
						<a href="#" target="_blank" class="dropdown-toggle no-arr">
							<span class="lbl">Laporan Kehilangan</span>
						</a>
					</div>
					<div class="site-header-shown">
						<div class="dropdown user-menu">
							<button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img src="<?php echo base_url($assets); ?>img/avatar-2-64.png" alt="">
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
								<a class="dropdown-item" href="<?php echo base_url('driver/logout'); ?>"><span class="font-icon glyphicon glyphicon-log-out"></span>Logout</a>
							</div>
						</div>
					</div><!--.site-header-shown-->
	
					<div class="mobile-menu-right-overlay"></div>

					<?php if (isset($backbut)) { ?>
					<div class="site-header-collapsed">
						<div class="site-header-collapsed-in">
							<div class="dropdown dropdown-typical">
								<a class="dropdown-toggle no-arr" id="dd-header-sales" onclick="return history.back();">
									<span class="fa fa-arrow-left"></span>
									&nbsp;
									<span class="lbl">KEMBALI</span>
								</a>
							</div>
						</div>
					</div>
					<?php } ?>
				</div><!--site-header-content-in-->
			</div><!--.site-header-content-->
		</div><!--.container-fluid-->
	</header><!--.site-header-->