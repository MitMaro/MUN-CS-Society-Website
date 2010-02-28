<?php
/*------------------------------------------------------------------------------
    File: php/classes/Action.class.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class Action {
	
	private $nodes = array();
	private $action_id;
	private $action_name;
	private $command;
	private $class;
	private $valid = true;
	private $all_allowed = false;
	
	public function __construct($action_string){
		$node_strings = explode("/", strtolower($action_string));

		$parent_node_id = 1;
		// add the root node
		$this->nodes[] = array('id' => 1, 'parent' => 0, 'allow_all' => 'no', 'name' => 'actions');
		for($i = 0; $i < count($node_strings) - 1; $i++){
			$n = $node_strings[$i];
			
			$r = DBAdmin::getActionNode($n, $parent_node_id);
			// node not found so invalid action
			if($r === false){
				$this->valid = false;
				return;
			}
			
			$r['name'] = $n;
			
			$this->nodes[] = $r;
			if($r['allow_all'] === 'yes'){
				$this->all_allowed = true;
			}
			$parent_node_id = $r['id'];
		}
		// set the action name
		$this->action_name = $n;
		// make the class name, converting underscore delimiting to camelCase
		$this->class = ucfirst(preg_replace_callback('/_([a-z])/', create_function('$c', 'return strtoupper($c[1]);'), $this->action_name));
		$this->action_id = $r['id'];
		
		
		// get the command node (ie. the last action node)
		$r = DBAdmin::getActionNode(end($node_strings), $parent_node_id);
		if($r === false){
			$this->valid = false;
			return;
		}
		
		if($r['allow_all'] === 'yes'){
			$this->all_allowed = true;
		}

		$r['name'] = end($node_strings);
		$this->nodes[] = $r;
		$this->command = $r;
	}
	
	public function checkActionAllowedByUser($user_id, $group_id){
		if(!$this->isValid()){
			return false;
		}
		$allowed = $this->all_allowed;
		// cycle through the action tree and look for an allowed permission
		foreach($this->nodes as $n){

			// check user permissions
			$a = DBAdmin::getUserPermissionForAction($n['id'], $user_id);
			// if an allowed was found then the action is allowed
			if($a === 'yes'){
				$allowed = true;
			}
			// if a never is found in the action path then the action is not
			// allowed
			if($a === 'never'){
				return false;
			}
			
			// check group permissions
			$a = DBAdmin::getGroupPermissionForAction($n['id'], $group_id);
			// if an allowed was found then the action is allowed
			if($a === 'yes'){
				$allowed = true;
			}
			// if a never is found in the action path then the action is not
			// allowed
			if($a === 'never'){
				return false;
			}
		}
		return $allowed;
	}
	
	public function getActionAsPath(){
		if(!$this->isValid()){
			return "";
		}
		$path = 'php/';
		for($i = 0; $i< count($this->nodes) - 2; $i++){
			$path .= $this->nodes[$i]['name'] . '/';
		}
		
		$path .= $this->class . '.php';
		return $path;
	}
	
	public function getActionClassName(){
		if(!$this->isValid()){
			return "";
		}
		return 'Request' .  $this->class;
	}
	
	public function getActionName(){
		return $this->action_name;
	}
	
	public function getCommand(){
		return $this->command['name'];
	}
	
	public function isValid(){
		return $this->valid;
	}
	
}
