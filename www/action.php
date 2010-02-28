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
if(isset($_GET['_action']) && preg_match('|^[0-9a-z_]+(/[a-z0-9_]+)*$|', $_GET['_action'])) {

	$action = new Action(strtolower($_GET['_action']));

	$path = $action->getActionAsPath();
	$class= $action->getActionClassName();
	if($action->isValid() && file_exists($cfg['project_root'] . $path)){
		require $path;
	
		$request = new $class;
		
		// default id of 1 is the guest user, later I would like to grab this
		// info from the database
		if(!$action->checkActionAllowedByUser(Session::getUser('id', 1), Session::getUser('group', 1))){
			$request->setStatus(Request::UNAUTHORIZED);
		}
		else {
			$request->process($action->getCommand());
		}
	}
	else{
		$request = new Request();
		$request->setStatus(Request::NOT_IMPLEMENTED);
	}
}
else {
	$action = new Action("none");
	$request = new Request();
	$request->setStatus(Request::ACTION_REQUIRED);
}
if(Request::isAjaxRequest() || isset($_GET['_json'])){
	header('Content-type: application/json');
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	echo $request->json();
}
else{
	Session::setOnce('action', $action->getActionName());
	Session::setOnce('status', $request->getStatus());
	Session::setOnce('data', $request->getData());

	// handle some common return codes from the request class
	$msg = $request->getStatus();
	if($msg['code'] === Request::OK){
		Session::addMessage('Action completed successfully');
	}
	else if($msg['code'] === Request::INVALID_DATA){
		Session::addMessage('Invalid data was provided, please correct and try again');
	}
	else if($msg['code'] === Request::UNAUTHORIZED){
		Session::addMessage('You are not authorized to perform that action');
	}
	else {
		Session::addMessage('An error has occured: ' . $msg['message']);
	}
	
	if(isset($_REQUEST['request_page'])){
		$redirect = $cfg['site_path'] . trim($_REQUEST['request_page'], '/');
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