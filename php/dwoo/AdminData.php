<?php
/*------------------------------------------------------------------------------
    File: php/dwoo/AdminData.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class AdminData extends BaseData {
	public function __construct(){
		parent::__construct();
		$this->assign('user', Session::getUserAll());

		if(User::isLogged()){
			
			// the admin navigation
			// first check to see if the user can view all admin pages, this should
			// speed things up in certain circumstances 
			$a = new Action('admin/view');

			if($a->checkActionAllowedByUser(Session::getUser('id', 1), Session::getUser('group', 1))){
				$nav = DBAdmin::getAdminNavigation();
			}
			else{
				$nav = array();
				foreach(DBAdmin::getAdminNavigation() as $n){
					$a = new Action('admin/view/' . $n['name']);
					if($a->checkActionAllowedByUser(Session::getUser('id', 1), Session::getUser('group', 1))){
						$nav[] = $n;
					}
				}
			}
			$this->assign('nav', $nav);
		}
	}
}
