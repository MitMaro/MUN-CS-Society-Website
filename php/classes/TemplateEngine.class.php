<?php
/*------------------------------------------------------------------------------
    File: php/classes/TemplateEngine.class.php
 Project: MMTiny
 Version: 0.1.0
      By: Tim Oram [t.oram@mitmaro.ca]
 Website: http://www.mitmaro.ca/
   Email: mmtiny@mitmaro.ca
 Purpose: Class that extends dwoo object and sets some default data and
          settings.
 Depends: Dwoo Template Engine
------------------------------------------------------------------------------*/

class TemplateEngine {

	private static $template_dir;
	private static $debug_flag = false;
	
	// the instance of this class, used similar to a singleton
	private static $instance;
	// this is a singleton like class
	private function __construct(){}

	// initiate this class
	public static function init($template_dir = null, $compiled_dir = null, $cache_dir = null) {
		if (!self::$instance instanceof DwooEngine) {


			if($template_dir != null){
				self::$template_dir = $template_dir;
			}
			else{
				self::$template_dir = realpath(dirname(__FILE__) .
				                               '/../templates/');
			}
			self::$instance = new DwooEngine(self::$template_dir);
			
			if($cache_dir != null){
				self::$instance->setCacheDir($cache_dir);
			}
			
			if($compiled_dir != null){
				self::$instance->setCompileDir($compiled_dir);
			}
		}
	}
	
	// put the template engine into debug mode
	public static function setDebug(){
		self::$debug_flag = true;
		if (self::$instance instanceof DwooEngine) {
			self::$instance->setDebug();
		}
	}

	// create a template from a template file name
	public static function templateFromFile($name, $cache_time = null,
	                                      $cache_id = null, $compile_id = null){
	    self::init();
		if(self::$debug_flag){
			$cache_time = 0;
		}
		
		if(file_exists(self::$template_dir . $name . '.dwoo')){
		    $file = self::$template_dir . $name . '.dwoo';
		}
		else{
			throw new NoTemplateException('No template found ' . $name .
			                 '.dwoo from ' . self::$template_dir, E_USER_ERROR);
		}
		return new Dwoo_Template_File($file, $cache_time, $cache_id,
		                              $compile_id);
	}

	// create a template from a template as a string, great for loading
	// templates from a database
	public static function templateFromString($template, $cache_time = null,
	                                      $cache_id = null, $compile_id = null){
	    self::init();
		if(self::$debug_flag){
			$cache_time = 0;
		}
		return new Dwoo_Template_String($template, $cache_time, $cache_id,
		                                $compile_id);
	}

	// outputs a template
	public static function output($tpl, $data = array()){
		self::init();
		self::$instance->output($tpl, $data);
	}


	// returns a string of the parsed and processed template
	public static function get($tpl, $data = array()){
		self::init();	
		return self::$instance->get($tpl, $data);
	}

}

class DwooEngine extends Dwoo {
	// this handles includes in templates, I wish there was another way to do
	// this
	
	private $debug_flag;
	private $template_dir = null;
	
	public function __construct($template_dir = null){
		global $cfg;
		$this->globals['SELF'] = $_SERVER['PHP_SELF'];
		$this->globals['config'] = $cfg;
		$this->template_dir = $template_dir;
	}
	
	public function setDebug(){
		$this->debug_flag = true;
	}
	
	public function templateFactory($resourceName, $resourceId, $cacheTime = null,
	                                $cacheId = null, $compileId = null,
	                                Dwoo_ITemplate $parentTemplate = null){
		if($this->debug_flag){
			$cacheTime = 0;
		}
	    
		if(file_exists($this->template_dir . $resourceId . '.dwoo')){
		    $resourceId = $this->template_dir . $resourceId . '.dwoo';
		}
		else{
			throw new NoTemplateException('No template found ' . $resourceId .
			                 '.dwoo from ' . $this->template_dir, E_USER_ERROR);
		}

		return parent::templateFactory($resourceName, $resourceId, $cacheTime,
		                                $cacheId, $compileId, $parentTemplate);
	}
}

class NoTemplateException extends Exception {}
