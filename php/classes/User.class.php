<?php 
/*------------------------------------------------------------------------------
    File: php/classes/User.class.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/
class User extends UserBase {
	public static function verifyAccess($action){
		if(DBCommon::checkActionAccess($action)) return true;
		if(self::isLogged()){
			$user_id = Session::getUser('id');
			if(DBCommon::superUser($user_id) || DBCommon::checkUserAccess($user_id, $action)){
				return true;
			}
		}
		return false;
	}
}