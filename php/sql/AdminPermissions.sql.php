<?php
/*------------------------------------------------------------------------------
    File: php/sql/AdminPermissions.sql.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class DBAdminPermissions {
	public static function getUsers(){
		$qs = 'SELECT`id`,`username` FROM `' . DB::getPrefix() . 'users` ORDER BY `username`';
		return DB::fetchAll($qs);
	}

	public static function getUserPermissions($id){
		$qs = 'SELECT `pages`.`id` as "page_id", `perms`.`allowed`' .
			'FROM `' . DB::getPrefix() . 'admin_pages` AS `pages`' .
			'LEFT JOIN `' . DB::getPrefix() . 'admin_permissions` AS `perms`' .
				"ON `pages`.`id` = `perms`.`page_id` and `perms`.`user_id` = '$id'";
		return DB::fetchAll($qs);
	}

	public static function update($user, $page, $allowed){
		$qs = 'INSERT INTO  `' . DB::getPrefix() . 'admin_permissions`' .
			"(`page_id`, `user_id`, `allowed`) VALUES ('$page', '$user', '$allowed')".
			"ON DUPLICATE KEY UPDATE `allowed` = '$allowed'";
		return DB::query($qs);
	}
}
