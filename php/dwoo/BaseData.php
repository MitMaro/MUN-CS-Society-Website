<?php
/*------------------------------------------------------------------------------
    File: php/dwoo/BaseData.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class BaseData extends Dwoo_Data {
	public function __construct(){
		$this->assign('request', array(
			'action' => Session::getOnce('action', false),
			'status' => Session::getOnce('status', false),
			'data' => Session::getOnce('data', false)
		));
	}
}
