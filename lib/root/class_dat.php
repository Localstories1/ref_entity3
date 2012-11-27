<?php

date_default_timezone_set('Europe/Paris');

class dat{

	CONST FORMAT_DAY 			= 'Ymd';
	CONST FORMAT_HOUR 			= 'YmdH';
	CONST FORMAT_NONE 			= false;
	CONST FORMAT_TIMESTAMP 		= 'timestamp';
	CONST FORMAT_FORCE 			= 'force';

	CONST PREFIX 				= '_';
	CONST PREFIX_EMPTY 			= '';
	CONST SUFIX 				= '';
	CONST SUFIX_EMPTY 			= '';

	public $date 				= false;
	public $format 				= self::FORMAT_TIMESTAMP;
	public $prefix 				= self::PREFIX;
	public $prefix_empty 		= self::PREFIX_EMPTY;
	public $sufix 				= self::SUFIX;
	public $sufix_empty 		= self::SUFIX_EMPTY;
	public $date_prefix_sufix;

	public function update(){

		switch($this->format){

			case self::FORMAT_NONE:
				$this->date = '';
				$prefix 	= $this->prefix_empty;
				$sufix 		= $this->sufix_empty;
				break;
			case self::FORMAT_TIMESTAMP:
				$this->date = $this->date_format;
				$prefix 	= $this->prefix;
				$sufix 		= $this->sufix;
				break;
			case self::FORMAT_FORCE:
				$prefix 	= $this->prefix;
				$sufix 		= $this->sufix;
				break;
			default:
				$this->date = date($this->date_format);
				$prefix 	= $this->prefix;
				$sufix 		= $this->sufix;
				break;
		}
		$this->date_prefix_sufix = $prefix.$this->date.$sufix;

		return true;
	}
}
?>