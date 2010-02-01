<?php
/*------------------------------------------------------------------------------
    File: php/actions/Pages.php
 Project: MUN CS Website
 Version: 0.1.0
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
 Purpose: Handles admin page requests
------------------------------------------------------------------------------*/
class RequestPages extends Request {
	
	public function __construct(){
		self::useParam('id');
	}

	public function get(){
		
		if(self::validId()){
			$this->valid['id'] = (int)$_GET['id'];
			$this->data = DBAdminPages::getById($this->valid['id']);
		}
		else{
			$this->data = DBAdminPages::getAll();
		}
		return true;
	}
	
	public function add(){
		$this->valid = array('public' => 'no');
		$this->valid['title'] = htmlentities($_POST['title'], ENT_QUOTES);
		$this->valid['url'] = htmlentities($_POST['url'], ENT_QUOTES);
		$this->valid['id'] = $_POST['id'];
		if(isset($_POST['public']) && trim($_POST['public']) == 'yes'){
			$this->valid['public'] = 'yes';
		}

		$this->data = $this->valid;
		
		return DBAdminPages::add($this->valid['id'], $this->valid['title'], $this->valid['url'], $this->valid['public']);
	}
	
	public function update(){
		$this->valid['title'] = htmlentities($_POST['title'], ENT_QUOTES);
		$this->valid['url'] = htmlentities($_POST['url'], ENT_QUOTES);
		$this->valid['id'] = $_POST['id'];

		$this->data = $this->valid;
		
		return DBAdminPages::update($this->valid['id'], $this->valid['title'], $this->valid['url']);
	}
	
	public function delete(){
		$this->valid['id'] = (int)$_GET['id'];
		return DBAdminPages::delete($this->valid['id']);
	}
	
	public function idExists(){
		$this->valid['id'] = (int)$_GET['id'];
		if(DBAdminPages::idExists($this->valid['id'])){
			$this->data = array('valid' => true);
		}
		else{
			$this->data = array('valid' => false);
		}
		return true;
	}
	
	public function moveFirst(){
		$this->valid['id'] = (int)$_GET['id'];
		return DBAdminPages::moveFirst($this->valid['id']);
	}
	
	public function moveLast(){
		$this->valid['id'] = (int)$_GET['id'];
		return DBAdminPages::moveLast($this->valid['id']);
	}
	
	public function moveUp(){
		$this->valid['id'] = (int)$_GET['id'];
		return DBAdminPages::moveUp($this->valid['id']);
	}
	
	public function moveDown(){
		$this->valid['id'] = (int)$_GET['id'];
		return DBAdminPages::moveDown($this->valid['id']);
	}
	
	public function setPublic(){
		$this->valid['id'] = (int)$_GET['id'];
		$this->valid['public'] = strtolower($_GET['public']);
		return DBAdminPages::setPublic($this->valid['id'], $this->valid['public']);		
	}

	public function validateAdd(){
		return isset($_POST['title'], $_POST['url'], $_POST['id']) &&
		       strlen(trim($_POST['title'])) >= 3 &&
		       strlen(trim($_POST['url'])) >= 1 &&
		       (int)$_POST['id'] > 0 && !DBAdminPages::idExists((int)$_POST['id']);
	}
	
	public function validateUpdate(){
		return isset($_POST['title'], $_POST['url'], $_POST['id']) &&
		       strlen(trim($_POST['title'])) >= 3 &&
		       strlen(trim($_POST['url'])) >= 1 &&
		       (int)$_POST['id'] > 0 && !DBAdminPages::idExists((int)$_POST['id']);
	}

	public function validateDelete(){
		return self::validId();
	}
	
	public function validateIdExists(){
		return self::validId();
	}
	
	public function validateMoveFirst(){
		return self::validId();
	}
	
	public function validateMoveLast(){
		return self::validId();
	}
	
	public function validateMoveDown(){
		return self::validId();
	}
	
	public function validateMoveUp(){
		return self::validId();
	}
	
	public function validateSetPublic(){
		return self::validId() && isset($_GET['public']) && (strtolower($_GET['public']) == 'yes' || strtolower($_GET['public']) == 'no');
	}
	
	private function validId(){
		return isset($_GET['id']) && (int)$_GET['id'] > 0;
	}
}