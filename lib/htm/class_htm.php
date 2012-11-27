<?php

class htm extends rep{

	CONST DATA_TYPE 		= 'htm';
	CONST DIR_BASENAME 		= 'htm';

	public $ext				= '.htm';
	public $cache			= self::CACHE_ENABLE;
	public $write_buffer	= self::WRITE_BUFFER_ENABLE;
	public $dir_basename	= self::DIR_BASENAME;
	public $dir_date_format	= self::DATE_FORMAT_HOUR;
	public $data_eol		= self::DATA_EOL_UNIX;
	public $data_encode		= self::DATA_ENCODE_UTF8;
	public $data_type		= self::DATA_TYPE;

	public $url_replace 	= array();
	public $url_tpl;
	public $url;

	public $extract 		= array();
	public $extract_id;
	public $extract_befor;
	public $extract_after;

	public function get_data(){

		$this->file = template::run_st($this->url_tpl, $this->url_replace);

		parent::get_data();

		$this->file = $this->update_file();
		$this->data = self::data_extact_clean($this->data);

		return true;
	}
	public function get_data_replace($url_replace){

		$this->url_replace = $url_replace;

		return $this->get_data();
	}
	public function get_data_replace_options_form_obj($obj){

		$url_replace = $obj->get_options();

		return $this->get_data_replace($url_replace);
	}
	public function extract_add(){

		$this->extract = self::url_extract_between($this->data, $this->extract_befor, $this->extract_after);

		return true;
	}
	public function extract_add_id($id = false){

		if($id === false) $this->extract_id = count($this->url_extract);

		return $this->extract_add();
	}
	public function extract_add_id_set($id, $just0 = true){

		$this->extract_add_id($id);

		if(empty($this->extract) === true || $this->extract === false) return '';

		if($just0 === true) $this->extract = $this->extract[0];

		$id 		= $this->extract_id;
		$this->$id 	= $this->extract;

		return true;
	}
}

?>