<?php 
/*------------------------------------------------------------------------------
    File: www/contact.php
 Project: CS Society
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
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
