<?php
/*------------------------------------------------------------------------------
    File: php/sql/Admin.sql.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
class DBAdmin {
	public static function getAdminNavigation(){
		$qs = 'SELECT `title`, `name` FROM `' . DB::getPrefix() . 'admin_pages`';
		return DB::fetchAll($qs);
	}
	
	public static function getActionNode($node, $parent_id){
		DB::makeSafe($node);
		$qs = 'SELECT `id`, `parent`, `allow_all` FROM  `' . DB::getPrefix() . 'actions`' .
		      "WHERE `name` = '$node' AND `parent` = $parent_id LIMIT 1";
		$r = DB::fetchRow($qs);
		if(isset($r['id'])){
			return $r;
		}
		return false;
	}
	
	public static function getUserPermissionForAction($action_id, $user_id){
		$qs = 'SELECT `permission` FROM `' . DB::getPrefix() . 'user_permissions`'.
		      "WHERE `action_id` = $action_id AND `user_id` = '$user_id'";
		$r = DB::fetchRow($qs);
		if(isset($r['permission'])){
			return $r['permission'];
		}
		return false;
	}
	
	public static function getGroupPermissionForAction($action_id, $group_id){
		$qs = 'SELECT `permission` FROM `' . DB::getPrefix() . 'group_permissions`'.
		      "WHERE `action_id` = $action_id AND `group_id` = '$group_id'";
		$r = DB::fetchRow($qs);
		if(isset($r['permission'])){
			return $r['permission'];
		}
		return false;
	}
	
}