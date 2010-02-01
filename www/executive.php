<?php 
/*------------------------------------------------------------------------------
    File: www/executive.php
 Project: CS Society
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
------------------------------------------------------------------------------*/
require 'init.php';

$tpl = TemplateEngine::templateFromFile('main');

$data = array(
	'page' => array(
		'title' => 'Executive'
	),
	'content' => TemplateEngine::get(TemplateEngine::templateFromFile('content/executive'))
);
TemplateEngine::output($tpl, $data);
