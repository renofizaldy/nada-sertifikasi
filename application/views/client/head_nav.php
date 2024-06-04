

			<div class="header py-4 d-md-none bg-gray-dark shadow">
				<div class="container">
					<div class="d-flex">
						<a class="header-brand" href="#">
							<img src="<?= base_url('assets/aven-wd-white.png'); ?>" class="header-brand-img" alt="tabler logo">
						</a>
						<a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
							<span class="header-toggler-icon"></span>
						</a>
					</div>
				</div>
			</div>

			<div class="header collapse d-lg-flex p-0 bg-gray-dark shadow" id="headerMenuCollapse">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-2 ml-auto text-right d-none d-sm-block d-md-block d-lg-block d-xl-block">
							<img src="<?= base_url('assets/aven-wd-white.png'); ?>" class="header-brand-img" alt="tabler logo" style="margin-right: 0;">
						</div>
						<div class="col-lg order-lg-first">
							<ul class="nav nav-tabs border-0 flex-column flex-lg-row">
								<li class="nav-item">
									<a href="<?= base_url('surat/surat_masuk'); ?>" class="nav-link"><i class="fa fa-inbox"></i> Surat Masuk</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url('surat/surat_keluar'); ?>" class="nav-link"><i class="fa fa-paper-plane"></i> Surat Keluar</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url('req/logout'); ?>" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>