<?php 
/*------------------------------------------------------------------------------
    File: www/contact.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
require 'init.php';

$tpl = TemplateEngine::templateFromFile('main');

$data = array(
	'page' => array(
		'title' => 'Contact'
	),
	'content' => TemplateEngine::get(TemplateEngine::templateFromFile('content/contact'))
);
TemplateEngine::output($tpl, $data);
