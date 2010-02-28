<?php
/*------------------------------------------------------------------------------
    File: php/sql/AdminPages.sql.php
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

class DBAdminPages {
	public static function getAll(){
		$qs = 'SELECT`id`,`title`,`url`,`order`,`public`FROM`' . DB::getPrefix() .
		      'admin_pages` ORDER BY `order`';
		return DB::fetchAll($qs);
	}
	
	public static function getById($id){
		$qs = 'SELECT`id`,`title`,`url`,`order`,`public`FROM`' . DB::getPrefix() .
	      "admin_pages` WHERE `id` = $id";
		return DB::fetchRow($qs);
	}
	public static function add($id, $title, $url, $public){

		DB::makeSafe($title);
		DB::makeSafe($url);
		
		if(($order = self::getHighestOrder()) === false){
			$order = -1;
		}

		$order++;

		$qs = 'INSERT INTO`' . DB::getPrefix() . 'admin_pages`'.
		      '(`id`,`title`, `url`, `order`,`public`)'.
		      "VALUES ('$id','$title', '$url', '$order', '$public')";
		return DB::query($qs);
	}
	
	public static function update($id, $title, $url){
		
		DB::makeSafe($title);
		DB::makeSafe($url);
		
		$qs = 'UPDATE`' . DB::getPrefix() . 'admin_pages`'.
		      "SET `title` = '$title', `url` = '$url' WHERE `id` = '$id'";
		return DB::query($qs);
	}
	
	public static function delete($id){
		$order = self::getOrderById($id);

		$qs = 'UPDATE `' . DB::getPrefix() . "admin_pages` SET `order` = `order` - 1 WHERE `order` > '$order'";
		if(!DB::query($qs)) return false;

		$qs = 'DELETE FROM`' . DB::getPrefix() . "admin_pages` WHERE`id`='$id'";
		return DB::query($qs);
	}

	public static function idExists($id){
		$qs = 'SELECT`id`FROM`' . DB::getPrefix() . "admin_pages` WHERE `id` = '$id' LIMIT 1";
		$r = DB::fetchRow($qs);
		if(isset($r['id']) && $r['id'] > 0) return true;
		
		return false;
	}
	
	public static function moveFirst($id){
		
		$order = self::getOrderById($id);
		if($order === false) return false;
		if($order == 0) return true;
		
		$qs = 'UPDATE `' . DB::getPrefix() . 'admin_pages` SET `order` = `order` + 1 '.
		      "WHERE `order` < '$order'";
		if(!DB::query($qs)) return false;
		
		$qs = 'UPDATE `' . DB::getPrefix() . 'admin_pages` SET `order` = "0"'.
		      "WHERE `id` = '$id' LIMIT 1";
		return DB::query($qs);
	}
	
	public static function moveLast($id){
		if(($new_order = self::getHighestOrder()) === false) return false;
		if(($order = self::getOrderById($id)) === false) return false;

		// already last
		if($order == $new_order) return true;

		$qs = 'UPDATE `' . DB::getPrefix() . 'admin_pages` SET `order` = `order` - 1 '.
		      "WHERE `order` > '$order'";
		if(!DB::query($qs)) return false;

		$qs = 'UPDATE `' . DB::getPrefix() . "admin_pages` SET `order` = '$new_order'".
		      "WHERE `id` = '$id' LIMIT 1";
		return DB::query($qs);
	}

	public static function moveUp($id){

		if(($high_order = self::getHighestOrder()) === false) return false;
		if(($order = self::getOrderById($id)) === false) return false;
		
		// already highest order
		if($order == $high_order) return true;
		
		$plusone = $order + 1;

		$qs = 'UPDATE `' . DB::getPrefix() . 'admin_pages` SET'.
		      "`order` = if(`order` = $order, $plusone, $order)".
		      "WHERE `order` = '$plusone' or `order` = '$order'";
		return DB::query($qs);
	}
	
	public static function moveDown($id){

		if(($order = self::getOrderById($id)) === false) return false;
		
		// already as low as it can go
		if($order== 0) return true;
		
		$lessone = $order - 1;

		$qs = 'UPDATE `' . DB::getPrefix() . 'admin_pages` SET'.
		      "`order` = if(`order` = $order, $lessone, $order)".
		      "WHERE `order` = '$lessone' or `order` = '$order'";
		return DB::query($qs);
	}
	
	public static function setPublic($id, $value){
		$qs = 'UPDATE`' . DB::getPrefix() . "admin_pages` SET`public`='$value' WHERE`id`='$id'";
		return DB::query($qs);
	}
	
	
	private static function getOrderById($id){
		$qs = 'SELECT `order` FROM`' . DB::getPrefix() . "admin_pages` WHERE `id` = '$id' LIMIT 1";
		$r = DB::fetchRow($qs);
		if(!isset($r['order'])) return false;
		
		return $r['order'];
	}
	
	private static function getHighestOrder(){
		$qs = 'SELECT `order` FROM`' . DB::getPrefix() . 'admin_pages` ORDER BY `order` DESC LIMIT 1';
		$r = DB::fetchRow($qs);
		if(!isset($r['order'])) return false;
		
		return $r['order'];
	}
	
}