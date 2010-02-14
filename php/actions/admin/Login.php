<?php
/*------------------------------------------------------------------------------
    File: php/actions/Login.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
class RequestLogin extends Request {
	
	public function __construct(){
		self::useParam('id');
	}
	
	public function login(){
		global $cfg;
		$this->data['logged_in'] = false;
		$valid['username'] = trim($_POST['username']);
		$valid['user_seed'] = DBAdminLogin::getUserSeed($valid['username']);
		// username wasn't found so we can't login
		if($valid['user_seed'] === false){
			return true;
		}
		$valid['password_hash'] = sha1($cfg['password_seed'] . $_POST['password'] . $valid['user_seed']);
		$user_data = DBAdminLogin::checkLogin($valid['username'], $valid['password_hash']);
		if($user_data === false){
			return true;
		}
		$this->data['logged_in'] = true;
		$this->data['user'] = $user_data;
		foreach($user_data as $key => $value){
			Session::setUser($key, $value);
		}
		Session::setUser('ip', $_SERVER['REMOTE_ADDR']);
		return true;
	}
	
	public function logout(){
		Session::resetUser();
		return true;
	}
	
	public function validateLogin(){
		return isset($_POST['username'], $_POST['password']) &&
		       trim($_POST['username']) != '' && trim($_POST['password']) != '';
	}
	
}