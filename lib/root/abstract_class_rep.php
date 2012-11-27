<?php

abstract class rep extends tools{
	
	CONST DATA_ENCODE_UTF8 		= 'UTF-8';
	CONST DATA_EOL_UNIX 		= "\n";
	CONST CACHE_ENABLE 			= true;
	CONST CACHE_DISABLE 		= false;
	CONST WRITE_DIRECT_ENABLE 	= true;
	CONST WRITE_DIRECT_DISABLE 	= false;
	CONST WRITE_BUFFER_ENABLE 	= true;
	CONST WRITE_BUFFER_DISABLE 	= false;

	public $root					= '../datas';
	public $dir						= false;
	public $file					= false;
	public $basename				= false;
	public $ext						= false;
	public $basename_date_format	= self::DATE_FORMAT_NONE;
	public $basename_date			= false;
	public $basename_sep			= '_';
	public $id						= false;
	public $cache					= self::CACHE_DISABLE;
	public $write_direct			= self::WRITE_DIRECT_DISABLE;
	public $write_buffer			= self::WRITE_BUFFER_DISABLE;
	public $dir_basename			= false;
	public $dir_sep					= '_';
	public $dir_date_format			= self::DATE_FORMAT_NONE;
	public $dir_date				= false;
	public $data					= false;
	public $data_date_format		= self::DATE_FORMAT_NONE;
	public $data_date				= false;
	public $data_eol				= false;
	public $data_sep				= false;
	public $data_encode				= false;
	public $data_type				= false;
	public $data_header				= false;
	public $source_tpl				= array();
	public $source_tab				= array();

	public function update_dir(){

		if(is_dir($this->root) === false) mkdir($this->root);

		$this->basename = str_replace('/',  DIRECTORY_SEPARATOR, $this->basename);
		$this->basename = str_replace('\\', DIRECTORY_SEPARATOR, $this->basename);
		$date 			= new dat();
		$date->format	= $this->dir_date_format;
		$date->prefix	= $this->dir_sep;

		$date->update();

		$this->dir 		= $this->root.DIRECTORY_SEPARATOR.$date->date_prefix_sufix;

		if(is_dir($this->dir) === false) mkdir($this->dir);

		if(strstr($this->basename, DIRECTORY_SEPARATOR) !== false){

			$parts 		= explode(DIRECTORY_SEPARATOR, $this->basename);
			$dir_tmp 	= '';
			$dir 		= $this->dir;

			foreach($parts as $part){

				$dir = $dir.DIRECTORY_SEPARATOR.$part;

				if(is_dir($dir) === false) mkdir($dir);
			}
		}
		return true;
	}
	public function update_file(){

		$this->update_dir();

		$date 			= new dat();
		$date->format	= $this->basename_date_format;
		$date->prefix	= $this->basename_sep;

		$date->update();

		$this->file 	= $this->dir.DIRECTORY_SEPARATOR.$date->date_prefix_sufix.$this->csv_ext;

		return true;
	}
	public function update_file_basename($basename){

		$this->basename = $basename;

		return $this->update_file();
	}
	public function update_source_tab(){

		$this->source_tpl = array_keys($this->source_tab);

		if(empty($this->source_tpl) === true) return true;

		$this->source_id_first	= $this->source_tpl[0];
		$this->source_id_last	= end($this->source_tpl);

		return true;
	}
	public function update_source_tab_from_tab($tab){

		$this->source_tab = $tab;

		return $this->update_source_tab();
	}
	public function update_buffer(){

		$this->data = file_put_contents($this->file, $this->data);

		return $this->data;
	}
	public function update_buffer_from_data($data){

		$this->data = $data;

		return $this->update_buffer();
	}
	public function update_direct(){

		$fp = @fopen($this->file, 'a+');
		@fwrite($fp, $this->data);
		@fclose($fp);

		return true;
	}
	public function update_direct_from_data($data){

		$this->data = $data;

		return $this->update_direct();
	}
	public function write(){

		$data = $this->cache();

		if($data === true) return true;

		switch($this->write_buffer){

			case self::CACHE_DISABLE: $this->buffer();
				break;
		}
		switch($this->write_direct){

			case self::WRITE_DIRECT_ENABLE: $this->direct();
				break;
		}
		if($this->cache === self::CACHE_ENABLE) $this->cache();

		return true;
	}
	public function write_id($id){

		$data = $this->cache();

		if($data === true) return true;

		switch($this->write_buffer){

			case self::WRITE_BUFFER_ENABLE: $this->buffer_id($id);
				break;
		}
		switch($this->write_direct){

			case self::WRITE_DIRECT_ENABLE: $this->direct_id($id);
				break;
		}
		return true;
	}
	public function cache(){

		if($this->cache === self::CACHE_DISABLE) return false;

		if(is_file($this->file) === true){

			$this->data = file_get_contents($this->file);

			return true;
		}
		return false;
	}
	public function cache_file($file){

		$this->file = $file;

		return $this->file();
	}
	public function get_data(){

		$options 						= array();
		$options['http'] 				= array();
		$options['http']['timeout'] 	= '99999999';
		$ctx 							= stream_context_create($options);
		$this->data 					= @file_get_contents($this->file, 0, $ctx);

		return true;
	}
	public function get_data_from_file($file){

		$this->file = $file;

		return $this->get_data();
	}
	public static function data_suppr_accents($str) {

		$avant = array('Ãƒâ‚¬','Ãƒï¿½','Ãƒâ€š','ÃƒÆ’','Ãƒâ€ž','Ãƒâ€¦','Ã„â‚¬','Ã„â€š','Ã„â€ž','Ã‡ï¿½','Ã‡Âº','Ãƒâ€ ','Ã‡Â¼',
				'Ãƒâ€¡','Ã„â€ ','Ã„Ë†','Ã„Å ','Ã„Å’','Ãƒï¿½','Ã„Å½','Ã„ï¿½',
				'Ãƒâ€°','ÃƒË†','ÃƒÅ ','Ãƒâ€¹','Ã„â€™','Ã„â€�','Ã„â€“','Ã„Ëœ','Ã„Å¡','Ã„Å“','Ã„Å¾','Ã„Â ','Ã„Â¢',
				'Ã„Â¤','Ã„Â¦','ÃƒÅ’','Ãƒï¿½','ÃƒÅ½','Ãƒï¿½','Ã„Â¨','Ã„Âª','Ã„Â¬','Ã„Â®','Ã„Â°','Ã„Âº','Ã„Â¼','Ã„Â¾','Ã…â‚¬','Ã…â€š','Ã‡ï¿½','Ã„Â²','Ã„Â´','Ã„Â¶','Ã„Â¹','Ã„Â»','Ã„Â½','Ã„Â¿','Ã…ï¿½',
				'Ã…Æ’','Ã…â€¦','Ã…â€¡','Ãƒâ€˜','Ãƒâ€™','Ãƒâ€œ','Ãƒâ€�','Ãƒâ€¢','Ãƒâ€“','Ã…Å’','Ã…Å½','Ã…ï¿½','Ã†Â ','Ã‡â€˜','ÃƒËœ','Ã‡Â¾','Ã…â€™','Ã…â€�','Ã…â€“','Ã…Ëœ',
				'Ã…Å¡','Ã…Å“','Ã…Å¾','Ã…Â ','Ã…Â¢','Ã…Â¤','Ã…Â¦','Ã…Â¨','Ãƒâ„¢', 'ÃƒÅ¡','Ãƒâ€º','ÃƒÅ“','Ã…Âª','Ã…Â¬','Ã…Â®','Ã…Â°','Ã…Â²','Ã†Â¯','Ã‡â€œ','Ã‡â€¢','Ã‡â€”','Ã‡â„¢','Ã‡â€º',
				'Ã…Â´','Ãƒï¿½','Ã…Â¶','Ã…Â¸','Ã…Â¹','Ã…Â»','Ã…Â½',
				'ÃƒÂ ','ÃƒÂ¡','ÃƒÂ¢','ÃƒÂ£','ÃƒÂ¤','ÃƒÂ¥','Ã„ï¿½','Ã„Æ’','Ã„â€¦','Ã‡Å½','Ã‡Â»','ÃƒÂ¦','Ã‡Â½','ÃƒÂ§','Ã„â€¡','Ã„â€°','Ã„â€¹','Ã„ï¿½','Ã„ï¿½','Ã„â€˜',
				'ÃƒÂ¨','ÃƒÂ©','ÃƒÂª','ÃƒÂ«','Ã„â€œ','Ã„â€¢','Ã„â€”','Ã„â„¢','Ã„â€º','Ã„ï¿½','Ã„Å¸','Ã„Â¡','Ã„Â£','Ã„Â¥','Ã„Â§',
				'ÃƒÂ¬','ÃƒÂ­','ÃƒÂ®','ÃƒÂ¯','Ã„Â©','Ã„Â«','Ã„Â­','Ã„Â¯','Ã„Â±','Ã‡ï¿½','Ã„Â³','Ã„Âµ','Ã„Â·',
				'ÃƒÂ±','Ã…â€ž','Ã…â€ ','Ã…Ë†','Ã…â€°','ÃƒÂ²','ÃƒÂ³','ÃƒÂ´','ÃƒÂµ','ÃƒÂ¶','Ã…ï¿½','Ã…ï¿½','Ã…â€˜','Ã†Â¡','Ã‡â€™','ÃƒÂ¸','Ã‡Â¿','Ã…â€œ',
				'Ã…â€¢','Ã…â€”','Ã…â„¢','Ã…â€º','Ã…ï¿½','Ã…Å¸','Ã…Â¡','ÃƒÅ¸','Ã…Â£','Ã…Â¥','Ã…Â§',
				'ÃƒÂ¹','ÃƒÂº','ÃƒÂ»','ÃƒÂ¼','Ã…Â©','Ã…Â«','Ã…Â­','Ã…Â¯','Ã…Â±','Ã…Â³','Ã‡â€�','Ã‡â€“','Ã‡Ëœ','Ã‡Å¡','Ã‡Å“','Ã†Â°','Ã…Âµ','ÃƒÂ½','ÃƒÂ¿','Ã…Â·','Ã…Âº','Ã…Â¼','Ã…Â¾','Ã†â€™','Ã…Â¿');
		$apres = array('A','A','A','A','A','A','A','A','A','A','A','AE','AE',
				'C','C','C','C','C','D','D','D',
				'E','E','E','E','E','E','E','E','E','G','G','G','G',
				'H','H','I','I','I','I','I','I','I','I','I','I','I','I','I','I','I','IJ','J','K','L','L','L','L','L',
				'N','N','N','N','O','O','O','O','O','O','O','O','O','O','O','O','OE','R','R','R',
				'S','S','S','S','T','T','T','U','U','U','U','U','U','U','U','U','U','U','U','U','U','U','U',
				'W','Y','Y','Y','Z','Z','Z',
				'a','a','a','a','a','a','a','a','a','a','a','ae','ae','c','c','c','c','c','d','d',
				'e','e','e','e','e','e','e','e','e','g','g','g','g','h','h',
				'i','i','i','i','i','i','i','i','i','i','ij','j','k',
				'n','n','n','n','n',
				'o','o','o','o','o','o','o','o','o','o','o','o','oe',
				'r','r','r','s','s','s','s','s','t','t','t',
				'u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','w','y','y','y','z','z','z','f','s');

		return str_replace($avant, $apres, $str);
	}
	public static function data_extact_clean($str){

		$str 	= strtolower($str);
		$str 	= str_replace("\n", '', $str);
		$str	= str_replace("\t", '', $str);
		$str	= str_replace("\r", '', $str);

		$str = self::data_suppr_accents($str);

		while(strstr($str, '  ') !== false) $str = str_replace('  ', ' ', $str);

		$str = trim($str);

		return $str;
	}
	public static function data_extract_between($data, $avant, $apres){

		$data 		= self::data_extact_clean($data);
		$avant 		= self::data_extact_clean($avant);
		$apres		= self::data_extact_clean($apres);
		$pattern 	= $avant.'{CAPTURE_REGEXP}'.$apres;
		$pattern 	= str_replace('\\', '\\\\', $pattern);
		$pattern 	= str_replace('#', '\#', $pattern);
		$pattern 	= str_replace(':', '\:', $pattern);
		$pattern 	= str_replace('-', '\-', $pattern);
		$pattern 	= str_replace('.', '\.', $pattern);
		$pattern 	= str_replace('*', '\*', $pattern);
		$pattern 	= str_replace('?', '\?', $pattern);
		$pattern 	= str_replace(')', '\)', $pattern);
		$pattern 	= str_replace('(', '\(', $pattern);
		$pattern 	= str_replace(']', '\]', $pattern);
		$pattern 	= str_replace('[', '\[', $pattern);
		$pattern 	= str_replace('{CAPTURE_REGEXP}', '(.*)', '#'.$pattern.'#Ui');

		preg_match_all($pattern , $data, $n);

		return $n[1];
	}
	public static function lettre_all(){

		$t 		= array();
		$t[] 	= 'a';
		$t[] 	= 'b';
		$t[] 	= 'c';
		$t[] 	= 'd';
		$t[] 	= 'e';
		$t[] 	= 'f';
		$t[] 	= 'g';
		$t[] 	= 'h';
		$t[] 	= 'i';
		$t[] 	= 'j';
		$t[] 	= 'k';
		$t[] 	= 'l';
		$t[] 	= 'm';
		$t[] 	= 'n';
		$t[] 	= 'o';
		$t[] 	= 'p';
		$t[] 	= 'q';
		$t[] 	= 'r';
		$t[] 	= 's';
		$t[] 	= 't';
		$t[] 	= 'u';
		$t[] 	= 'v';
		$t[] 	= 'w';
		$t[] 	= 'x';
		$t[] 	= 'y';
		$t[] 	= 'z';

		for($i = 0;$i<10;$i++){

			$t[] = $i;
		}
		$t[] 	= '-';
		$t[] 	= '.';
		$t[] 	= '#';
		$t[] 	= '(';
		$t[] 	= ')';
		$t[] 	= '>';
		$t[] 	= '<';
		$t[] 	= '=';
		$t[] 	= '+';
		$t[] 	= '-';
		$t[] 	= '/';
		$t[] 	= '\\';
		$t[] 	= '*';
		$t[] 	= '$';
		$t[] 	= '}';
		$t[] 	= '{';
		$t[] 	= '[';
		$t[] 	= ']';
		$t[] 	= 'Ã‚Â°';
		$t[] 	= '`';
		$t[] 	= '"';
		$t[] 	= '~';
		$t[] 	= '&';
		$t[] 	= '|';
		$t[] 	= '@';
		$t[] 	= '_';
		$t[] 	= 'Ã¢â€šÂ¬';
		$t[] 	= 'Ã‚Â²';
		$t[] 	= ';';
		$t[] 	= '!';
		$t[] 	= 'Ã‚Â§';
		$t[] 	= '%';
		$t[] 	= 'Ã‚Â£';
		$t[] 	= '?';
		$t[] 	= ':';
		$t[] 	= ',';
		$t[] 	= '^';
		$t[] 	= 'Ã‚Â¤';
		$t[] 	= 'Ã‚Â¨';
		$t[] 	= "\n";
		$t[] 	= '\r';
		$t[] 	= '\t';

		return $t;
	}
}

?>
