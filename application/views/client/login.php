<?php include('head_top.php'); ?>

<div class="page page-center">
  <div class="container container-tight py-4">
    <div class="text-center mb-4">
      <a href="." class="navbar-brand navbar-brand-autodark">
        <img src="<?= base_url('assets/aven-wd-grey.png'); ?>" width="110" height="32" alt="Nada" class="navbar-brand-image">
      </a>
    </div>
    <div class="card card-md p-4">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">Masuk Akun</h2>
        <form action="<?= base_url('req/login') ?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name='username' class="form-control" autocomplete="off">
          </div>
          <div class="mb-2">
            <label class="form-label">Password</label>
            <div class="input-group input-group-flat">
              <input type="password" name='password' class="form-control" autocomplete="off">
            </div>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>