<?php
/*------------------------------------------------------------------------------
    File: php/actions/User.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
class RequestUser extends Request {
	
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

	public function update(){
		$this->valid['id'] = Session::getUser('id', 0);
		$this->valid['name'] = trim($_POST['name']);
		$this->valid['email'] = trim($_POST['email']);
		return DBUser::update($this->valid['id'], $this->valid['name'], $this->valid['email']);
	}
	
	public function password(){
		global $cfg;
		$this->valid['id'] = Session::getUser('id', 0);
		$this->valid['password_hash'] = sha1($cfg['password_seed'] . $_POST['new_password'] . $this->valid['user_seed']);
		return DBUser::changePassword($this->valid['id'], $this->valid['password_hash']);
	}
		
	public function validateLogin(){
		return isset($_POST['username'], $_POST['password']) &&
		       trim($_POST['username']) != '' && trim($_POST['password']) != '';
	}
	
	public function validatePassword(){
		global $cfg;
		if(!(isset($_POST['password'], $_POST['new_password'], $_POST['confirm_password']) &&
		       strlen($_POST['new_password']) >= 6 && $_POST['new_password'] == $_POST['confirm_password'])){
			return false;
		}
		$this->valid['id'] = Session::getUser('id', 0);

		$this->valid['user_seed'] = DBUser::getUserSeed($this->valid['id']);
		if($this->valid['user_seed'] === false){
			return false;
		}
		$this->valid['password_hash'] = sha1($cfg['password_seed'] . $_POST['password'] . $this->valid['user_seed']);
		return DBUser::checkPassword($this->valid['id'], $this->valid['password_hash']);
	}
	
	public function validateUpdate(){
		return isset($_POST['name'], $_POST['email']) && trim($_POST['name']) != '' && 
		       preg_match("/^[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}$/i", trim($_POST['email'])) == 1;
		
	}
	
	
}