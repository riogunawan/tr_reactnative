<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$base_url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '').'://'.$_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>404</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" href="<?php echo $base_url.'/' ?>assets/themes/admin/plugins/bootstrap/css/bootstrap.css" charset="utf-8">
    <link rel="stylesheet" href="<?php echo $base_url.'/' ?>assets/themes/admin/css/style.css" charset="utf-8" type="text/css">
    <link rel="stylesheet" href="<?php echo $base_url.'/' ?>assets/themes/admin/css/custom-bootstrap.css" charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <link rel="shortcut icon" href="<?php echo $base_url.'/' ?>assets/themes/admin/logo.ico" />
  </head>
  <body>
    <div class="body-sign">
      <div class="center-sign">
        <span class="notfound-title">4<i class="fa fa-exclamation-circle" aria-hidden="true"></i>4</span>
        <span class="notfound-text"><?php echo $message; ?></span>
		
        <center><a href="<?php echo $base_url.'/' ?>" class="btn btn-default btn-notfound btn-rounded">homepage</a></center>
      </div>
    </div>
  </body>
  <script src="<?php echo $base_url.'/' ?>assets/themes/admin/js/jquery-2.2.3.min.js"></script>
  <script src="<?php echo $base_url.'/' ?>assets/themes/admin/plugins/bootstrap/js/bootstrap.min.js"></script>
</html>
