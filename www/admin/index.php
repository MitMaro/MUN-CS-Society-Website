<?php
/*------------------------------------------------------------------------------
    File: www/admin/index.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
require 'init.php';

$data = new AdminData();
if(!User::isLogged()){
	$tpl = TemplateEngine::templateFromFile('admin/login');
	
	$data->assign('pages', array(
		'title' => 'News and Updates',
	));
	
}
else {
	$tpl = TemplateEngine::templateFromFile('admin/main');
	$data->assign('content', TemplateEngine::get(TemplateEngine::templateFromFile('content/index')));
}

TemplateEngine::output($tpl, $data);
