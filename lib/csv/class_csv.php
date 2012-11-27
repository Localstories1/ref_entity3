<?php

class csv extends root{

	CONST DATA_TYPE 	= 'csv';
	CONST DIR_BASENAME 	= 'csv';

	public $ext					= '.csv';
	public $write_direct		= self::WRITE_DIRECT_ENABLE;
	public $dir_basename		= self::DIR_BASENAME;
	public $dir_date_format		= self::DATE_FORMAT_HOUR;
	public $data_date_format	= self::DATE_FORMAT_TIMESTAMP;
	public $data_eol			= self::DATA_EOL_UNIX;
	public $data_sep			= ';';
	public $data_encode			= self::DATA_ENCODE_UTF8;
	public $data_type			= self::DATA_TYPE;

	public $source_id_first		= false;
	public $source_id_last		= false;
	public $source_id_current	= false;

	public $output_tab 			= array();

	public function clean($csv) {

		$csv 	= html_entity_decode($csv, ENT_QUOTES, 'UTF-8');
		$csv 	= htmlspecialchars_decode($csv, ENT_QUOTES);
		$csv	= self::url_suppr_accents($csv);
		$csv 	= strtolower($csv);
		$csv 	= str_replace("\n", '', $csv);
		$csv	= str_replace("\t", '', $csv);
		$csv	= str_replace("\r", '', $csv);
		$csv 	= str_replace($this->data_sep, '-', $csv);

		while(strstr($csv, '\' ')	!== false) $csv = str_replace('\' ', 	'\'', 	$csv);
		while(strstr($csv, '  ')	!== false) $csv = str_replace('  ', 	' ', 	$csv);

		$csv = trim($csv);

		return $csv;
	}
	public function buffer_first(){

		if($this->source_id_current === $this->source_id_first) 	{

			$this->data  = '';

			foreach($this->source_tpl as $k => $v){

				$k 			  = $this->clean($k);
				$this->data  .= $k.$this->data_sep;
			}
			$this->data .= $this->data_eol;
		}
		return true;
	}
	public function buffer_last(){

		if($this->source_id_current === $this->source_id_last) 	{

			$this->update_buffer();

			$this->data = '';
		}
		return true;
	}
	public function buffer(){

		$this->buffer_first();

		foreach($this->source_tab as $k => $v){

			$this->output_tab[$k] 	 = $this->csv_clean($this->output_tab[$k]);
			$this->data 			.= $this->output_tab[$k].$this->csv_sep;
		}
		$this->data  .= $this->data_eol;

		$this->buffer_last();

		return true;
	}
	public function direct_source_id_current($id = false){

		if($id === false) {

			if($this->source_id_current === false) $this->source_id_current = 0;

			$this->source_id_current++;
		}
		else $this->source_id_current = $id;

		return  true;
	}
	public function buffer_id($id){

		$this->direct_source_id_current($id = false);

		return  $this->buffer();
	}
	public function direct_first(){

		if($this->source_id_current === $this->source_id_first) 	{

			foreach($this->source_tpl as $k => $v){

				$k 			  = $this->clean($k);
				$this->data  .= $k.$this->data_sep;
			}
			$this->data .= $this->data_eol;
		}
		return true;
	}
	public function direct(){

		$this->data = '';

		$this->direct_first();

		foreach($this->source_tab as $k => $v){

			$this->output_tab[$k] 	= $this->csv_clean($this->output_tab[$k]);
			$this->data 			= $this->output_tab[$k].$this->csv_sep;
		}
		$this->data  .= $this->data_eol;

		$this->update_direct();

		return true;
	}
	public function direct_id($id = false){

		$this->direct_source_id_current($id = false);

		return  $this->direct();
	}
	public function from_file_to_tab(){

		$this->get_data();

		if($this->data === false) return false;

		$this->data 		= trim($this->data);
		$contenu 			= explode($this->csv_eol, $this->data);
		$this->source_tab 	= array();

		foreach($contenu as $id => $csv){

			$csv = trim($csv);

			if(empty($csv) === true) continue;

			$csv 					= explode($this->data_sep, $csv);
			$this->source_tab[$id]	= array();

			foreach($this->source_tpl as $k => $var){

				$this->source_tab[$id][$var] = $csv[$k];
			}
		}
		return $this->source_tab;
	}
	public function from_file_to_tab_file($file){

		$this->file = $file;

		return $this->from_file_to_tab();
	}
	public function get_data(){

		parent::get_data();

		$this->data = self::data_extact_clean($this->data);

		return true;
	}
}

?>