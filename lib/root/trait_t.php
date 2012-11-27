<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('memory_limit', '4000M');

trait l{

	public static $DIR_LOG_BASENAME = 'log';

	public $dir_log;

	public $log_date				= 'Ymd';
	public $log_ext 				= '.log';

	public function l($data, $clean = false){

		$log_file = $this->dir_log.DIRECTORY_SEPARATOR.date($this->log_date).$this->log_ext;

		if($clean === true && is_file($log_file) === true) unlink($log_file);

		$data		= time().';'.$data."\n";
		$fp 		= @fopen($log_file, 'a+');

		@fwrite($fp, $data);
		@fclose($fp);
	}
}
?>