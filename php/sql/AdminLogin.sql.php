<?php
/*------------------------------------------------------------------------------
    File: php/sql/AdminLogin.sql.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class DBAdminLogin {
	public static function getUserSeed($username){
		$username = DB::makeSafe($username);
		$qs = 'SELECT `seed` FROM `' . DB::getPrefix() . 'users`'.
		      "WHERE `username` = '$username' LIMIT 1";
		$result = DB::fetchrow($qs);
		if(isset($result['seed'])){
			return $result['seed'];
		}
		return false;
	}
	
	public static function checkLogin($username, $password){
		$password = DB::makeSafe($password);
		$username = DB::makeSafe($username);
		$qs = 'SELECT `id`, `username`, `name`, `email` FROM `'. DB::getPrefix() . 'users`'.
		      "WHERE `username` = '$username' AND `password` = '$password'";
		$result = DB::fetchRow($qs);
		if(isset($result['id'])){
			return $result;
		}
		return false;
	}
}