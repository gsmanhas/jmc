<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Email Setting
|--------------------------------------------------------------------------
|
|
*/
$config['protocol'] = 'smtp';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset']  = 'utf-8';
$config['wordwrap'] = TRUE;
$config['mailtype'] = 'html';
$config['priority'] = 3;
$config['smtp_host'] = 'mail.josiemarancosmetics.com';
$config['smtp_user'] = 'cs@josiemarancosmetics.com';
$config['smtp_pass'] = 'lit89dmz%%';
$config['smtp_port'] = '26';
$config['mailfrom']  = 'cs@josiemarancosmetics.com';
$config['mailbcc']   = array('devteamintenss@gmail.com', 'developer@sixspokemedia.com', 'tester@sixspokemedia.com', 'manager@sixspokemedia.com', 'cs@josiemarancosmetics.com');
$config['cc']        = '';