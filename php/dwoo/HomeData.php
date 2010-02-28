<?php
/*------------------------------------------------------------------------------
    File: php/dwoo/HomeData.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class HomeData extends BaseData {
	
	private $perms = array(); 
	
	public function __construct(){
		parent::__construct();
		$user_id = Session::getUser('id', 0);
		$group_id = Session::getUser('group', 0);
		$this->assign('user', DBUser::getUserData($user_id));

		// order the permission tree
		$permissions = array(1 => array('id' => 0, 'name' => 'root', 'parent' => 0, 'permission' => false));
		foreach(DBUser::getUserPermissions($user_id, $group_id) as $permission){
			$permissions[$permission['id']]['id'] = $permission['id'];
			$permissions[$permission['id']]['name'] = $permission['name'];
			if ($permission['action_permission'] === 'yes' || $permission['group_permission'] === 'yes' || $permission['user_permission'] === 'yes'){
				$permissions[$permission['id']]['permission'] = true;
			}
			else {
				$permissions[$permission['id']]['permission'] = false;
			}
			$permissions[$permission['id']]['parent'] = $permission['parent'];
		}
		// create the permission tree
		foreach($permissions as &$permission){
			if($permission['parent'] != 0){
				$permissions[$permission['parent']]['children'][$permission['id']] = &$permission;
			}
		}
		// remove some of the temp data
		unset($permission);
		$permissions = $permissions[1];
		
		// collapse the tree. ie. if admin/user/* all have the same value then set
		// admin/user to the value
		$this->collapsePermissions($permissions);

		// make the permissions into strings of text, this will be skipped if 
		// permissions are collapsed down to root
		if(isset($permissions['children'])){
			$this->permissionsToString($permissions['children']);
		}
		else{
			$this->perms[] = array('name' =>  'All', 'allowed' => $permissions['permission']);	
		}
		$this->assign('permissions', $this->perms);
	}
	
	
	private function collapsePermissions(&$permissions){
		
		// collapse children is permission on current is allowed
		if($permissions['permission']){
			unset($permissions['children']);
			return;
		}
		
		// depth first so recurse to the deepest depth
		foreach($permissions['children'] as &$p){
			if(isset($p['children'])){
				$this->collapsePermissions($p);
			}
		}
		unset($p);
		
		// check if every value of the children is equal
		$start_value = reset($permissions['children']);
		$start_value = $start_value['permission'];
		$equal = true;
		foreach($permissions['children'] as $p){
			if($p['permission'] !== $start_value || isset($p['children'])){
				$equal = false;
			}
		}
			
		// if equal shrink the array down
		if($equal){
			$permissions['permission'] = $start_value;
			unset($permissions['children']);
		}
	}		
	
	private function permissionsToString($permissions, $action = ''){
		foreach($permissions as $p){
			if(isset($p['children'])){
				$this->permissionsToString($p['children'], $action . '/'. $p['name']);
			}
			// a value was found to set the actions value
			else{
				$this->perms[] = array('name' =>  $action . '/' . $p['name'], 'allowed' => $p['permission']);	
			}
		}
	}
	
}
