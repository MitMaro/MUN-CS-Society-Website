<?php
/*------------------------------------------------------------------------------
    File: php/actions/Permissions.php
 Project: MUN CS Website
 Version: 0.1.0
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://society.cs.mun.ca/
   Email: muncssociety@gmail.com
 Purpose: Handles admin permission requests
------------------------------------------------------------------------------*/

class RequestPermissions extends Request {
	
	public function __construct(){
		self::useParam('id');
	}
	
	public function get(){
		$this->valid['id'] = (int)$_GET['id'];
		$this->data = DBAdminPermissions::getUserPermissions($this->valid['id']);
		return true;
	}
	
	public function getUsers(){
		$this->data = DBAdminPermissions::getUsers();
		return true;
	}
	
	public function update(){
		$this->valid['page_id'] = (int)$_POST['page_id'];
		$this->valid['user_id'] = (int)$_POST['user_id'];
		if(trim(strtolower($_POST['allowed'])) == 'yes'){
			$this->valid['allowed'] = 'yes';
		}
		else{
			$this->valid['allowed'] = 'no';
		}
		return DBAdminPermissions::update($this->valid['user_id'], $this->valid['page_id'], $this->valid['allowed']);
	}
	
	public function validateGet(){
		return isset($_GET['id']) && (int)$_GET['id'] > 0;
	}

	public function validateUpdate(){
		return isset($_POST['page_id'], $_POST['user_id'], $_POST['allowed']) &&
		       (int)$_POST['page_id'] > 0 && (int)$_POST['user_id'] > 0;
	}
}