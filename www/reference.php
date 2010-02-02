<?php 
/*------------------------------------------------------------------------------
    File: www/reference.php
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
		'title' => 'Reference'
	),
	'content' => TemplateEngine::get(TemplateEngine::templateFromFile('content/reference'))
);
TemplateEngine::output($tpl, $data);
