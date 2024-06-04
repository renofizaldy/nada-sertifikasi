<?php include('head_top.php'); ?>
<?php include('head_nav.php'); ?>

<style type="text/css">
	.label-sup {
		position      : relative;
		font-size     : 60%;
		line-height   : 0;
		vertical-align: baseline;
		top           : -.8em;
		background    : #5eba00;
		padding       : 4px 8px;
		border-radius : 54px;
		color         : white;
	}
</style>

<div class="page">
<div class='page-main'>

<div class="my-3 my-md-5"><div class="container">

<div class="d-flex flex-row">
  <div class="flex-fill mr-2">
    <div class="card">
      <div class="card-body p-4" data-toggle='tooltip' data-placement='top'>
        <div class="card-value float-right"><img src="<?= base_url('assets/tabler/images/ico-gross.png'); ?>" style="height:100%"></div>
        <h3 id="totalGrs" class="mb-1 tit-summary"><?= $output['res_total_masuk']; ?></h3>
        <div><span class="tag bg-warning text-white">SURAT MASUK</span></div>
      </div>
    </div>
  </div>
  <div class="flex-fill mr-2">
    <div class="card">
      <div class="card-body p-4" data-toggle='tooltip' data-placement='top'>
        <div class="card-value float-right"><img src="<?= base_url('assets/tabler/images/ico-gross.png'); ?>" style="height:100%"></div>
        <h3 id="totalSum" class="mb-1 tit-summary"><?= $output['res_total_keluar']; ?></h3>
        <div><span class="tag bg-info text-white">SURAT KELUAR</span></div>
      </div>
    </div>
  </div>
</div>

</div></div>

<?php include('foot_nav.php'); ?>