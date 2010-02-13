<?php
/*------------------------------------------------------------------------------
    File: php/classes/UserBase.class.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
 Purpose: Handle user page access
 Depends: Session
------------------------------------------------------------------------------*/

class UserBase {
	
	// check if the current user is logged in
	public final static function isLogged(){
		// assume the user is logged in if their user id is set
		if(!Session::getUser('id', false)){
			return false;
		}
		return true;
	}
	
	// the below methods should be extended upon for use in any application that
	// uses them.
	
	// set the user login information
	public static function login(){
		Session::setUserVariable('id', 1);
	}
	
	// logout the user, does not destroy the session just the user login
	public final static function logout(){
		Session::resetUser();
	}
}
