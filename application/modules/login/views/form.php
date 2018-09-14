<div class="body-sign">
  <div class="center-sign">
    <h2 class="title"><span>Sign In</span><i class="fa fa-sign-in" aria-hidden="true"></i></h2>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="logo">
          <img src="<?php echo base_url() ?>assets/themes/admin/images/logo-prop-kalbar.png" alt="" />
          <div class="text">
            <h4><?php echo $title ?></h4>
            <h6><?php echo $subtitle ?></h6>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <form class="form" action="<?php echo $form_aksi ?>" method="POST">
          <?php echo $this->session->flashdata('msg'); ?>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-user"></i></div>
              <?php echo $username ?>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-lock"></i></div>
              <?php echo $pass ?>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" name="button" class="btn btn-primary btn-block btn-lg">sign in</button>
          </div>
        </form>
      </div>
    </div>
    <span class="copyright">© Copyright 2018 BIRO PEMERINTAHAN - SEKRETARIAT DAERAH PROVINSI KALIMANTAN BARAT
.</span>
  </div>
</div>
