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
		$this->assign('site', array(
			'messages' => Session::getOnce('messages', array())
		));
	}
}
