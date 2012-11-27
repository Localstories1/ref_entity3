<?php

class htm_collection extends htm{

	use collection;

	CONST DATA_TYPE 	= 'htm_collection';
	CONST DIR_BASENAME 	= 'htm/collection';

	public function get_data($replace = array(), $extract = true){

		foreach($replace as $tag => $vals){

			$replace2 = array();

			foreach($vals as $k => $v){

				$replace2[$tag] 			= array();
				$replace2[$tag][$k] 		= $v;
				$url 						= new htm_index();
				$url->url 					= $this->url_tpl;
				$url->url_replace 			= $replace2;
				$url->basename_date_format	= dat::FORMAT_NONE;
				$url->basename 				= $tag.$k;
				$url->dir_date_format		= dat::FORMAT_DAY;
						
				$url->update_file();
				
				$contenu			= $this->url_get($extract);

				if($contenu === false) {

					$this->l(__LINE__.' BAD URL '.$this->url);

					continue;
				}
				if($extract === true)	{

					$this->url_contenu_to_extract = $contenu;

					unset($contenu);

					$count0 = $this->url_collection_extract_add($tag.$k, true, true);

					$this->url_count_file = $this->dir_csv_liste.DIRECTORY_SEPARATOR.$tag.$k.$this->url_count_basename.'_'.$count0.$this->csv_ext;
					file_put_contents($this->url_count_file, $count0);

					$count 							= $count + $count0;
					$this->url_contenu_to_extract	= '';
				}
				else {

					$this->l(__LINE__.' NO EXTRACT '.$tag.$k);

					$this->url_contenu = $contenu;

					unset($contenu);
				}
			}
		}
		$this->url_count_file = $this->dir_csv_liste.DIRECTORY_SEPARATOR.$this->url_count_basename.'_'.$count.$this->csv_ext;
		file_put_contents($this->url_count_file, $count);

		return $count;
	}
	public function url_collection_extract_add($basename, $details = false, $csv = false){

		$this->csv_file = $this->dir_csv_liste.DIRECTORY_SEPARATOR.$basename.$this->csv_ext;

		if(is_file($this->csv_file) === true && $this->csv_cache === true && $csv === true){

			$count = count(explode($this->csv_eol, file_get_contents($this->csv_file)));

			return $count;
		}
		$this->url_extract_add(false, false, false);

		if($details === true && $csv === true){

			$this->csv_set($basename, $this->url_extract_res);

			$this->csv	= '';
			$count 		= count($this->url_extract_res);

			foreach($this->url_extract_res as $id => $str){

				$part = $this->extract_details($str);

				foreach($part as $k2 => $v2){

					$this->$k2 = $v2;
				}
				$this->csv_to($id);

				unset($this->url_extract_res[$id]);
			}
		}
		return $count;
	}
	public function url_collection_follow_details_obj($replace){

		$class		= $this->obj_type;
		$extract 	= $class::get_extract();

		foreach($replace as $tag => $vals){

			foreach($vals as $k => $v){

				$this->csv_file	= $this->dir_csv_liste.DIRECTORY_SEPARATOR.$tag.$k.$this->csv_ext;
				$lignes 		= $this->csv_from_to_tab();

				if($lignes === false) {

					$this->l(__LINE__.' NO DATA '.$this->csv_file);

					continue;
				}
				foreach($lignes as $id => $tab){

					$obj				= new $class();
					$obj->url 			= $obj->url_tpl;
					$obj->csv_file 		= $this->dir_csv.DIRECTORY_SEPARATOR.'liste_details'.DIRECTORY_SEPARATOR.$tag.$k.$this->csv_ext;

					$obj->csv_set($tag.$k, $lignes);

					foreach($tab as $var => $val){

						$obj->$var	= $val;
						$obj->url 	= str_replace('{'.$var.'}', $val, $obj->url);
					}
					$obj->htm_fichier = $this->dir_htm.DIRECTORY_SEPARATOR.'details'.DIRECTORY_SEPARATOR.$tag.$k.$obj->siret_principal.$this->htm_ext;

					$url = $obj->url_get(true);

					if($url !== false && $id !== 0) {

						foreach($extract as $id2 => $tab){

							$obj->befor = $tab['befor'];
							$obj->after = $tab['after'];

							$res = $obj->url_extract_add($id2, false, true);
						}
					}
					$obj->clean_csv();
					$obj->csv_to($id, true);
				}
			}
		}
		return true;
	}
}

?>