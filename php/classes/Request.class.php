<?php
/*------------------------------------------------------------------------------
    File: php/classes/Request.class.php
 Project: MMTiny
 Version: 0.1.0
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
 Purpose: Handles page request. Supports four actions, get, add, update, delete.
------------------------------------------------------------------------------*/

class Request{
	
	// status codes
	const OK = 200;
	const ERROR = 201;
	const INVALID_DATA = 202;
	const METHOD_NOT_ALLOWED = 205;
	const NOT_IMPLEMENTED = 206;
	const INVALID_REQUEST = 207;
	const ACTION_REQUIRED = 208;
	const REQUEST_TIMEOUT = 209;
	const MYSQL_OFFLINE = 210;
	const MYSQL_ERROR = 211;
	const UNAUTHORIZED = 250;
	const UNKNOWN_STATUS = 299;
	
	private $status = self::UNKNOWN_STATUS;
	// data to return if any
	protected $data = array();
	// valid input data from the user only
	protected $valid = array();
	
	// status code to text lookup array
	protected $status_lookup = Array(
		200 => 'Ok',
		201 => 'Error',
		202 => 'Invalid Data',
		205 => 'Method Not Allowed',
		206 => 'Action Not Implemented',
		207 => 'Invalid Request',
		208 => 'Action is Required',
		209 => 'Request Timed Out',
		210 => 'MySql Server Offline',
		211 => 'MySql Error',
		250 => 'User Not Authorized',
		299 => 'Status is Unknown'
	);
	
	// sets the request status
	public final function setStatus($status){
		if(array_key_exists($status, $this->status_lookup)){
			$this->status = $status;
		}
		else{
			$this->status = self::UNKNOWN_STATUS;
		}
	}
	
	// returns the status information
	public final function getStatus(){
		return array('code' => $this->status, 'message' => $status_lookup[$this->status]);
	}
	
	// returns the data information
	public final function getData(){
		return $data;
	}
	
	public function useParam($index){
		if(isset($_GET['_param'])){
			$_POST[$index] = $_GET['_param'];
			$_GET[$index] = $_GET['_param'];
		}
	}
	
	// process the request
	public final function process($action){
		try {
			switch($action){
				case 'get':
					if(!$this->validateGet()){
						$this->setStatus(self::INVALID_DATA);
						break;
					}
					if($this->get()){
						$this->setStatus(self::OK);
						break;
					}
					$this->setStatus(self::ERROR);
					break;
				case 'add':
					if(!$this->validateAdd()){
						$this->setStatus(self::INVALID_DATA);
						break;
					}
					if($this->add()){
						$this->setStatus(self::OK);
						break;
					}
					$this->setStatus(self::ERROR);
					break;
				case 'update':
					if(!$this->validateUpdate()){
						$this->setStatus(self::INVALID_DATA);
						break;
					}
					if($this->update()){
						$this->setStatus(self::OK);
						break;
					}
					$this->setStatus(self::ERROR);
					break;
				case 'delete':
					if(!$this->validateDelete()){
						$this->setStatus(self::INVALID_DATA);
						break;
					}
					if($this->delete()){
						$this->setStatus(self::OK);
						break;
					}
					$this->setStatus(self::ERROR);
					break;
				default:
					if(method_exists($this, $action)){
						if(method_exists($this, 'validate' . $action)){
							if(!call_user_func(array($this, 'validate' . $action))){
								$this->setStatus(self::INVALID_DATA);
								break;
							}
						}
						if(call_user_func(array($this, $action))){
							$this->setStatus(self::OK);
							break;
						}
						$this->setStatus(self::ERROR);
						break;
					}
					else{
						$this->setStatus(self::NOT_IMPLEMENTED); 
					}
					break;	
			}
		}
		catch(InvalidRequest $e){
			$this->setStatus(self::INVALID_DATA);
		}
		catch(DBConnectionError $e){
			$this->setStatus(self::MYSQL_OFFLINE);
		}
		catch(DBError $e){
			$this->setStatus(self::MYSQL_ERROR);
		}
	}

	// writes the request data structure as a json string
	public function json(){
		FB::log(array('status' => $this->status,
		                  'message' => $this->status_lookup[$this->status],
		                  'data' => $this->data));
		return json_encode(array('status' => $this->status,
		                  'message' => $this->status_lookup[$this->status],
		                  'data' => $this->data));
	}
	
	// determine if the request was an ajax based request, this is supported
	// by all the major libaries, the correct header should be added if using
	// xmlHttpRequest directly
	public static function isAjaxRequest(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

	// default get action, this should be overridden
	protected function get(){
		$this->setStatus(self::NOT_IMPLEMENTED); 
	}
	
	// default add action, this should be overridden
	protected function add(){
		$this->setStatus(self::NOT_IMPLEMENTED); 
	}
	
	// default update action, this should be overridden
	protected function update(){
		$this->setStatus(self::NOT_IMPLEMENTED); 
	}
	
	// default delete action, this should be overridden
	protected function delete(){
		$this->setStatus(self::NOT_IMPLEMENTED); 
	}
	
	// default get validation, this should be overridden if any validation is
	// required
	protected function validateGet(){
		return true;
	}
	
	// default add validation, this should be overridden if any validation is
	// required
	protected function validateAdd(){
		return true;
	}
	
	// default update validation, this should be overridden if any validation is
	// required
	protected function validateUpdate(){
		return true;
	}
	
	// default delete validation, this should be overridden if any validation is
	// required
	protected function validateDelete(){
		return true;
	}
}

class InvalidRequest extends Exception {}
