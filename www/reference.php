<?php 
/*------------------------------------------------------------------------------
    File: www/reference.php
 Project: CS Society
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
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
