<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|   http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config['mailtype']     = 'html';
$config['charset']      = 'utf-8';
$config['newline']      = "\r\n";

$config['protocol']     = 'smtp';
$config['smtp_host']    = SHAREBOX_SMTP_HOSTNAME;
$config['smtp_port']    = '465';
$config['smtp_timeout'] = '30';
$config['smtp_user']    = SHAREBOX_SMTP_USERNAME;
$config['smtp_pass']    = SHAREBOX_SMTP_PASSWORD;


/* End of file email.php */
/* Location: ./application/config/email.php */
