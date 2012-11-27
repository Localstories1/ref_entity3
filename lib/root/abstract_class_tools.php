<?php

abstract class tools extends root{

	public $tools;
	public $class;

	public static function tools_class($class, $type = '', $sep = '_'){

		if($type === '') $sep = '';

		return $class.$sep.$type;
	}
	public function tools_update($obj, $class, $vars = array()){

		$tools = new $class();

		foreach($vars as $k => $v) $tools->$k = $v;

		$this->tools[$class] = $tools;

		return true;
	}
	public function tools_extract($class, $vars = array()){

		foreach($vars as $k => $v) $vars[$k] = $this->tools[$class]->$k;

		return $vars;
	}
	public  function tools_extract_clean($class, $vars = array()){

		$this->tools_extract($class, $vars);

		$this->tools = '';

		return $vars;
	}
}

?>