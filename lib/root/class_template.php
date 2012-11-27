<?php

class template extends root{

	public $str;
	public $replace;

	public static function run_st($str, $replace = array()){

		foreach($replace as $tag => $vs) {

			foreach($vs as $k => $v) {

				$str = str_replace('{'.$k.'}', $v, $str);
			}
		}
		return $str;
	}
	public function run(){

		return self::run_st($this->str, $this->replace);
	}
}
