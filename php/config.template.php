<?php
/*------------------------------------------------------------------------------
    File: php/config.template.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
 Purpose: The configuration template file
------------------------------------------------------------------------------*/

$cfg = array();

// application information
$cfg['name'] = '';
$cfg['version'] = '';
$cfg['author'] = '';
$cfg['developer'] = '';
$cfg['title'] = '';
$cfg['copyright'] = 'Copyright ' . $cfg['developer'] . '; All Rights Reserved';
$cfg['guid'] = '';

// path info
$cfg['project_root'] = '/'; // absolute path
$cfg['site_path'] = '/';
$cfg['domain'] = 'http://localhost';
$cfg['redirect_page'] = $cfg['domain'] . $cfg['site_path'] . 'index.php';
$cfg['pear_path'] = '';
$cfg['firephp_path'] = 'FirePHPCore/fb.php';
$cfg['template_engine_path'] = '';
$cfg['php_include_path_extra'] = '';

// dwoo template engine settings
$cfg['dwoo_lib_path'] = '';
$cfg['dwoo_template_directory'] = $cfg['project_root'] . '';
$cfg['dwoo_compiled_directory'] = $cfg['project_root'] . '';
$cfg['dwoo_cache_directory'] = $cfg['project_root'] . '';

// database info
$cfg['db']['host'] = 'localhost';
$cfg['db']['port'] = '3306';
$cfg['db']['engine'] = 'MySql';
$cfg['db']['username'] = '';
$cfg['db']['password'] = '';
$cfg['db']['database'] = '';
$cfg['db']['table_prefix'] = '';

// debug and error config
$cfg['debug'] = false;
$cfg['maintenance'] = false;
$cfg['error_level'] = E_ALL | E_STRICT;
$cfg['error_show'] = false;
$cfg['error_max_arg_length'] = 15;
$cfg['error_mail'] = false;
$cfg['error_mail_subject'] = $cfg['app_name'] . ' Error Report';
$cfg['error_mail_to'] = 'errors@example.com';
$cfg['error_mail_from'] = 'errors@example.com';
$cfg['error_log'] = false;
// check php manual for error_log function for information on the next settings
$cfg['error_log_type'] = 3;
$cfg['error_log_destination'] = $cfg['project_root'] . 'logs/php_errors';
$cfg['error_firebug'] = false;
$cfg['error_firebug_collapse'] = false;

// time zone
$cfg['timezone'] = '';
