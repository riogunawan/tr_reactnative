<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Recaptcha class config
// ------------------------------------------------------------------------

// The reCaptcha server keys and API locations
// Obtain your own keys from: http://www.recaptcha.net

$config['recaptcha'] = array(
  							'public'						=> '6LfZOgYTAAAAAIrm5kvl4ncLuN58E1MMOj2C5wlu',
  							'private'						=> '6LfZOgYTAAAAALx3YTSo3TmOiiP8k-FVzOWFtCUi',
  							'RECAPTCHA_API_SERVER' 			=> 'http://www.google.com/recaptcha/api',
  							'RECAPTCHA_API_SECURE_SERVER'	=> 'https://www.google.com/recaptcha/api',
  							'RECAPTCHA_VERIFY_SERVER' 		=> 'www.google.com',
  							'RECAPTCHA_SIGNUP_URL' 			=> 'https://www.google.com/recaptcha/admin/create',
  							'theme' 						=> 'white'
							);
