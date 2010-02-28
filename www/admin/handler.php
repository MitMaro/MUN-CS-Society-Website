<?php
/*------------------------------------------------------------------------------
    File: www/admin/handler.php
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
}
else {
	if(isset($_GET['_page']) && trim($_GET['_page']) != ""){
	$page = strtolower(trim($_GET['_page']));
	}
	else{
		$page = 'home';
	}

	$action = new Action('admin/view/' . $page);	

	$tpl = TemplateEngine::templateFromFile('admin/main');
	if(!$action->isValid() && !TemplateEngine::templateExists('admin/pages/' . $page)){
		$data->assign('page', array(
			'title' => 'Page Not Found',
			'content' => TemplateEngine::get(TemplateEngine::templateFromFile('admin/special/not_found'))
		));
	}
	elseif(!$action->checkActionAllowedByUser(Session::getUser('id', 1), Session::getUser('group', 1))) {
		$data->assign('page', array(
			'title' => 'Access Denied',
			'content' => TemplateEngine::get(TemplateEngine::templateFromFile('admin/special/denied'))
		));
	}
	else{
		$section_data = ucfirst("{$page}Data");
		$section_data = new $section_data;
		
		if(file_exists($cfg['project_root'] . 'www/admin/js/' . $page . '.js')){
			$script = $page;
		}
		else{
			$script = false;
		}
		
		if(file_exists($cfg['project_root'] . 'www/admin/css/' . $page . '.css')){
			$css = $page;
		}
		else{
			$css = false;
		}
		
		$data->assign('page', array(
			'title' => 'User Control Panel',
			'content' => TemplateEngine::get(TemplateEngine::templateFromFile('admin/pages/' . $page), $section_data),
			'script' => $script,
			'css' => $css
		));
	}
}

TemplateEngine::output($tpl, $data);
