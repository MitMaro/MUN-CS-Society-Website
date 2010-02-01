<?php
/*------------------------------------------------------------------------------
    File: www/index.php
 Project: CS Society
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
------------------------------------------------------------------------------*/
require 'init.php';

$tpl = TemplateEngine::templateFromFile('main');

$data = array(
	'content' => TemplateEngine::get(TemplateEngine::templateFromFile('content/news'))
);
TemplateEngine::output($tpl, $data);
