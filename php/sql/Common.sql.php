<?php
/*------------------------------------------------------------------------------
    File: php/sql/Common.sql.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
class DBCommon {
	
	public static function superUser($id){
		$qs = 'SELECT `super` FROM`' . DB::getPrefix() . 'users`'.
		      "WHERE `id` = '$id'";
		$result = DB::fetchRow($qs);
		if(isset($result['super']) && $result['super'] == 'yes'){
			return true;
		}
		return false;
	}
	
	public static function checkUserAccess($id, $action){
		$qs = 'SELECT `allowed` FROM `' . DB::getPrefix() . 'permissions` AS permissions '.
		      'INNER JOIN `' . DB::getPrefix() . 'actions` AS actions ON `actions`.`id` = `permissions`.`action_id`'.
		      "WHERE `permissions`.`user_id` = '$id' AND `actions`.`action` = '$action'";
		$result = DB::fetchRow($qs);
		if(isset($result['allowed']) && $result['allowed'] == 'yes'){
			return true;
		}
		return false;
	}
	
	public static function checkActionAccess($action){
		$qs = 'SELECT `allow_all` FROM `' . DB::getPrefix() . 'actions`'.
		      "WHERE `name` = '$action' LIMIT 1";
		$result = DB::fetchRow($qs);
		if(isset($result['allow_all']) && $result['allow_all'] == 'yes'){
			return true;
		}
		return false;
	}
	
}