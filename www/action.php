<?php
/*------------------------------------------------------------------------------
    File: www/action.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
 Purpose: Request action handler, all request go through this script which will
          load the request handler for the action provided.
------------------------------------------------------------------------------*/
require 'init.php';

if(isset($_GET['_action']) && preg_match('/^[a-zA-Z]+$/', $_GET['_action'])) {
	$action = strtolower($_GET['_action']);
	$command = strtolower($_GET['_cmd']);
	$class = 'Request' . ucfirst($action);
	if(isset($_GET['_admin'])){
		$path = 'php/actions/admin/' . $action . '.php';
	}
	else{
		$path = 'php/actions/' . $action . '.php';
	}
	
	if(file_exists($cfg['project_root'] . $path)){
		require $path;
	
		$request = new $class;
	
		if(!User::verifyAccess(Session::getUser('id', false), $action)){
			$request->setStatus(Request::UNAUTHORIZED);
		}
		else{
			$request->process($command);
		}
	}
	else{
		$request = new Request();
		$action = 'none';
		$request->setStatus(Request::NOT_IMPLEMENTED);
	}
}
else {
	$request = new Request();
	$action = 'none';
	$request->setStatus(Request::ACTION_REQUIRED);
}
if(Request::isAjaxRequest() || true){
	header('Content-type: application/json');
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	echo $request->json();
}
else{
	Session::setOnce('action', $action);
	Session::setOnce('status', $request->getStatus());
	Session::setOnce('data', $request->getData());
	if(isset($_GET['request_page'])){
		$redirect = $cfg['site_path'] . $_GET['request_page'];
	}
	elseif(isset($_SERVER['HTTP_REFERER'])){
		$redirect = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
	}
	else{
		// can't determine where to redirect, go to the index page. This could
		// also redirect to a generic page for handling bad redirects.
		$redirect = $cfg['site_path'] . 'index.php';
	}
	header('Location: ' . $cfg['domain'] . $redirect);
	exit;
}
