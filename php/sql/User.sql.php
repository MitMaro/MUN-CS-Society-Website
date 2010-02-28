<?php
/*------------------------------------------------------------------------------
    File: php/sql/AdminPermissions.sql.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class DBUser {
	
	public static function getUserData($id){
		$qs = 'SELECT `name`, `email`, `username`, `super` FROM `' . DB::getPrefix() . "users`".
		      "WHERE `id` = '$id' LIMIT 1";
		return DB::fetchRow($qs);
	}
	
	public static function getUserPermissions($user_id, $group_id){
		$qs = 'SELECT `actions`.`parent`, `actions`.`id`, `actions`.`name`, `actions`.`allow_all` AS action_permission, `user_permissions`.`permission` AS user_permission, `group_permissions`.`permission` AS group_permission '.
		      'FROM `' . DB::getPrefix() . "actions` AS actions ".
		      'LEFT OUTER JOIN `' . DB::getPrefix() . 'user_permissions` AS user_permissions ON (`user_permissions`.`action_id` = `actions`.`id`'. 
		          "AND `user_permissions`.`user_id` = '$user_id') " .
		      'LEFT OUTER JOIN `' . DB::getPrefix() . 'group_permissions` AS group_permissions ON (`group_permissions`.`action_id` = `actions`.`id`'.
		          "AND `group_permissions`.`group_id` = '$group_id')" . 
		      "WHERE `actions`.`id` != 1";
		return DB::fetchAll($qs);
	}
	
	public static function getUserSeed($id){
		$qs = 'SELECT `seed` FROM `' . DB::getPrefix() . 'users`'.
		      "WHERE `id` = '$id' LIMIT 1";
		$result = DB::fetchrow($qs);
		if(isset($result['seed'])){
			return $result['seed'];
		}
		return false;
	}
	
	public static function update($id, $name, $email){
		DB::makeSafe($name);
		DB::makeSafe($email);
		$qs = 'UPDATE `' . DB::getPrefix() . "users` SET `name` = '$name', `email` = '$email'".
		      "WHERE `id` = '$id'";
		return DB::query($qs);
	}
	
	public static function changePassword($id, $password){
		$qs = 'UPDATE `' . DB::getPrefix() . "users` SET `password` = '$password' WHERE `id` = '$id'";
		return DB::query($qs);
	}
	
	public static function checkPassword($id, $password){
		$qs = 'SELECT `id` FROM `' . DB::getPrefix() . "users` WHERE `id` = '$id' AND `password` = '$password' LIMIT 1";
		$r = DB::fetchRow($qs);
		return isset($r['id']);
	}
	
}
