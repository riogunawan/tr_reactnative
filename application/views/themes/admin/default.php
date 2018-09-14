<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
      <?php echo $title; ?>
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <?php
      if (!empty($meta)) {
          foreach($meta as $name=>$content){
              echo "\n\t\t";
              ?>
                  <meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" />
              <?php
          }
      }
      echo "\n";

      if (!empty($canonical)) {
          echo "\n\t\t";
          ?>
              <link rel="canonical" href="<?php echo $canonical?>" />
          <?php

      }
      echo "\n\t";
    ?>

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/themes/admin/plugins/bootstrap/css/bootstrap.css" charset="utf-8">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/themes/admin/plugins/jquery-scroller/jquery.mCustomScrollbar.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/themes/admin/plugins/sweetalert/sweetalert.css" media="screen" title="no title" charset="utf-8">

    <!-- custom css-->
    <?php
      foreach ($css as $file) {
          echo "\n\t\t";
          ?>
              <link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" />
          <?php
      }
      echo "\n\t";
    ?>

    <?php echo $script_head ?>

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/themes/admin/css/style.css" charset="utf-8" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/themes/admin/css/custom-bootstrap.css" charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/themes/admin/images/logo-prop-kalbar.png" />
  </head>
  <body>
    <!-- header -->
    <header>
      <div class="mobile-nav hidden-sm hidden-md hidden-lg">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-5 col-md-6 logo">
            <a href="index.php">
              <img src="<?php echo base_url() ?>assets/themes/admin/images/logo-prop-kalbar.png" alt="" />
              <div class="text">
                <h4>BINA KAWASAN DAN PERTANAHAN</h4>
                <h6>BIRO PEMERINTAHAN - SEKRETARIAT DAERAH PROVINSI KALIMANTAN BARAT</h6>
              </div>
            </a>
          </div>
          <div class="col-xs-12 col-sm-7 col-md-6 header-menu">
            <ul class="profile-menu pull-right">
              <li>
                <div class="frame-photo">
                  <?php
                    $avatar = $this->session->userdata('avatar');
                    $avatar_url = "assets/upload/admin/thumb/$avatar";
                    $avatar_thumb = ( empty($avatar) || !file_exists(FCPATH . $avatar_url) ) ? base_url('assets/themes/admin/images/profil-dummy.png') : base_url("$avatar_url");
                   ?>
                  <img src="<?php echo $avatar_thumb ?>" alt="avatar" />
                </div>
                <div class="identify">
                  <span class="name hidden-xs"><strong><?php echo $this->session->userdata('nama_lengkap') ?></strong></span>
                  <span class="status hidden-xs"><?php echo $this->session->userdata('level') ?></span>
                </div>
                <span class="caret-icon"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                <ul>
                  <li><a href="<?php echo site_url('admin/form/'.$this->session->userdata('id_admin')) ?>"><i class="fa fa-cog" aria-hidden="true"></i> Update Profile</a></li>
                  <li><a href="<?php echo site_url('login/logout') ?>"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <div class="wrapper toggle-sidebar">
      <!-- side menu -->
      <?php echo $this->load->view('themes/admin/menu'); ?>

      <section class="main-content">

        <?php echo $output;?>

      </section>
    </div>
  </body>
  <script src="<?php echo base_url() ?>assets/themes/admin/js/jquery-2.2.3.min.js"></script>
  <script src="<?php echo base_url() ?>assets/themes/admin/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url() ?>assets/themes/admin/plugins/jquery-scroller/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="<?php echo base_url() ?>assets/themes/admin/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="<?php echo base_url() ?>assets/themes/admin/plugins/sweetalert/sweetalert.min.js"></script>
  <?php
    foreach ($js as $file) {
        echo "\n\t\t";
        ?>
        <script src="<?php echo $file; ?>"></script>
        <?php
    }
    echo "\n\t";
  ?>

  <?php echo $script_foot ?>

  <script src="<?php echo base_url() ?>assets/themes/admin/js/scripts.js"></script>
</html>
