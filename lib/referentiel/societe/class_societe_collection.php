<?php

class societe_collection{

	use url_collection;

	public $dir_root 			= '../data';
	public $obj_type			= 'societe_index';

	public $url_tpl	  			= 'http://www.bilansgratuits.fr/index-societe/{lettre}%2c0%2c1000000000';
	public $befor 				= '<li><a href="/';
	public $after 				= ')</a></li>';

	public $raison_sociale		= '';
	public $siret_principal		= '';
	public $code_postal			= '';
	public $commune				= '';
	public $url_details			= '';
	public $enseigne			= '';
	public $siret				= '';
	public $etabl_ident			= '';
	public $siren				= '';
	public $adresse				= '';
	public $region				= '';
	public $departement			= '';
	public $departement_num		= '';
	public $departement_insee	= '';
	public $forme_juridique		= '';
	public $capital				= '';
	public $creation			= '';
	public $naf					= '';
	public $naf_niv1			= '';
	public $naf_niv2			= '';
	public $naf_niv3			= '';
	public $naf_niv4			= '';
	public $naf_niv5			= '';
	public $activite			= '';
	public $effectif			= '';
	public $nb_etabl			= '';
	public $societe_type		= '';
	public $follow				= true;

	public function __construct(){

	}
	public function set_dir(){

		if(is_dir($this->dir_root) === false) mkdir($this->dir_root);

		$this->dir_log 			= $this->dir_root.DIRECTORY_SEPARATOR.self::$DIR_LOG_BASENAME;
		$this->dir_htm 			= $this->dir_root.DIRECTORY_SEPARATOR.self::$DIR_HTM_BASENAME;
		$this->dir_htm_liste	= $this->dir_htm.DIRECTORY_SEPARATOR.self::$DIR_HTM_LISTE_BASENAME;
		$this->dir_htm_details	= $this->dir_htm.DIRECTORY_SEPARATOR.self::$DIR_HTM_DETAILS_BASENAME;
		$this->dir_csv 			= $this->dir_root.DIRECTORY_SEPARATOR.self::$DIR_CSV_BASENAME;
		$this->dir_csv_liste	= $this->dir_csv.DIRECTORY_SEPARATOR.self::$DIR_CSV_LISTE_BASENAME;
		$this->dir_csv_details	= $this->dir_csv.DIRECTORY_SEPARATOR.self::$DIR_CSV_DETAILS_BASENAME;
		$this->dir_csv_update	= $this->dir_csv.DIRECTORY_SEPARATOR.self::$DIR_CSV_UPDATE_BASENAME;

		if(is_dir($this->dir_htm)			=== false) mkdir($this->dir_htm);
		if(is_dir($this->dir_htm_liste) 	=== false) mkdir($this->dir_htm_liste);
		if(is_dir($this->dir_htm_details) 	=== false) mkdir($this->dir_htm_details);
		if(is_dir($this->dir_csv) 			=== false) mkdir($this->dir_csv);
		if(is_dir($this->dir_csv_liste) 	=== false) mkdir($this->dir_csv_liste);
		if(is_dir($this->dir_csv_details) 	=== false) mkdir($this->dir_csv_details);
		if(is_dir($this->dir_csv_update) 	=== false) mkdir($this->dir_csv_update);
	}
	public function societe_collection_from_url_tpl(){

		$replace			= array();
		$replace['lettre'] 	= self::url_lettre_all();

		$this->url_collection_get_tpl($replace, true);

		if($this->follow === true) {

			$this->l(__LINE__.' FOLLOW');

			$this->url_collection_follow_details_obj($replace);
		}
		else {

			$this->l(__LINE__.' NO FOLLOW');
		}
		return true;
	}
	public function extract_details($liste_brut){

		$url_tmp 					 = explode('">', $liste_brut);
		$url						 = $url_tmp[0];
		$raison_sociale_tmp			 = explode(' (', $url_tmp[1]);
		$raison_sociale				 = $raison_sociale_tmp[0];

		if(substr($raison_sociale, 0, 2) === '- ') $raison_sociale = substr($raison_sociale, 2);

		while(substr($raison_sociale, 0, 1) === '-') $raison_sociale = substr($raison_sociale, 1);

		$code_postal				 = $raison_sociale_tmp[1];
		$siret_principal_tmp		 = self::url_extract_between($liste_brut, '-', '.htm');
		$siret_principal_tmp2 		 = explode('-', $siret_principal_tmp[0]);
		$siret_principal 			 = end($siret_principal_tmp2);

		$part		 				 = array();
		$part['raison_sociale']		 = $raison_sociale;
		$part['code_postal']		 = $code_postal;
		$part['siret_principal']	 = $siret_principal;
		$part['url_details']		 = $url;

		return $part;
	}
	public static function set_options($keys = false){

		$vars 						= array();
		$vars['raison_sociale']		= '';
		$vars['code_postal']		= '';
		$vars['commune']			= '';
		$vars['region']				= '';
		$vars['departement']		= '';
		$vars['departement_num']	= '';
		$vars['departement_insee']	= '';
		$vars['activite']			= '';
		$vars['naf']				= '';
		$vars['naf_niv1']			= '';
		$vars['naf_niv2']			= '';
		$vars['naf_niv3']			= '';
		$vars['naf_niv4']			= '';
		$vars['naf_niv5']			= '';
		$vars['siren']				= '';
		$vars['siret']				= '';
		$vars['etabl_ident']		= '';
		$vars['siret_principal']	= '';
		$vars['url_details']		= '';
		$vars['adresse']			= '';
		$vars['forme_juridique']	= '';
		$vars['capital']			= '';
		$vars['creation']			= '';
		$vars['effectif']			= '';
		$vars['nb_etabl']			= '';
		$vars['enseigne']			= '';
		$vars['societe_type']		= '';

		if($keys === true){

			$vars = array_keys($vars);
		}
		return $vars;
	}
	public function recherche_from_csv_to_csv(){

		if(
				isset($_REQUEST['from_csv_to_csv']) === false 	|| empty($_REQUEST['from_csv_to_csv']) === true ||
				isset($_REQUEST['pos']) === false 		|| empty($_REQUEST['pos']) === true
		) {

			return '';
		}
		$csv				= urldecode($_REQUEST['from_xml_to_xml']);
		$pos				= $_REQUEST['pos'];
		$csv				= trim($csv);
		$list				= explode($this->csv_eol, $csv);
		$vars	 			= self::set_options(true);
		$csv_obj 			= new societe_index();
		$csv_obj->csv_file 	= false;

		$csv_obj->csv_set('WS', $list);

		$csv_obj_update 			= new societe_index();
		$csv_obj_update->csv_file 	= false;

		$csv_obj_update->csv_set('UPDATE', $list);

		foreach ($list as $id => $csv){

			$update = false;
			$csv	= explode($this->csv_sep, $csv);

			foreach($csv as $k => $v){

				if(isset($pos[$k]) === false || isset($this->$k) === false) continue;

				$var 		= $pos[$k];
				$this->$k 	= $v;
			}
			$datas = $this->recherche();
			$datas = end($datas);

			foreach ($datas as $k2 => $v2){

				if(empty($this->$k)  === true) $this->$k = $v2;
				if(empty($v2) === true) {

					$update = true;
				}
			}
			if($update === true){

			}
			$return = $csv_obj->csv_to($id, false);
		}
		return $return;
	}
	public function recherche_from_xml_to_xml(){

		if(isset($_REQUEST['from_xml_to_xml']) === false || empty($_REQUEST['from_xml_to_xml']) === true) {

			return '<?xml version="1.0" ?>'."\n".'<etablissement />'."\n";
		}
		$xml = urldecode($_REQUEST['from_xml_to_xml']);
		$xml = simplexml_load_string($xml);

		foreach ($xml->children() as $k => $v){

			$attributs = $v->attributes();

			if(isset($attributs['multiple']) === true) {

				$multiple = $attributs['multiple'];
			}
			$_REQUEST[$k] = (string) $v;
		}
		return $this->recherche_xml();
	}
	public function recherche(){

		$societe 			= new societe_index();
		$raison_sociale		= urldecode($_REQUEST['raison_sociale']);
		$raison_sociale		= $societe->csv_clean($raison_sociale);
		$lettre 			= substr(strtolower($raison_sociale), 0, 1);
		$societe->csv_file	= $this->dir_csv_liste.DIRECTORY_SEPARATOR.'lettre'.$lettre.$this->csv_ext;
		$res				= $societe->csv_from_to_tab();

		if($res === false) return array();

		$vars = self::set_options();
		$vars = array_keys($vars);
		$res2 = self::recherche_r($vars, $societe, $res, 1);

		return $res2;
	}
	public function recherche_xml(){

		$res = $this->recherche();
		$xml = '<?xml version="1.0" ?>'."\n";

		foreach($res as $k => $v){

			$xml .= '<etablissement>'."\n";

			foreach($v as $k2 => $v2){

				$xml .= '<'.$k2.'>'.$v2.'</'.$k2.'>'."\n";
			}
			$xml .= '</etablissement>'."\n";
		}
		$xml = tidy_repair_string($xml, array(
				'output-xml' => true,
				'input-xml' => true
		));
		return $xml;
	}
	public static function recherche_r($vars, $societe, $res, $var_id){

		$res2	= array();
		$k 		= $vars[$var_id];

		if(isset($_REQUEST[$k]) === false || empty($_REQUEST[$k]) === true) {

			$res = self::recherche_r($vars, $societe, $res2, $var_id++);
		}
		$v	= urldecode($_REQUEST[$k]);
		$v	= $societe->csv_clean($v);

		foreach($res as $id => $infos){

			if($infos[$k] !== $v) continue;

			$res2[$id] = $infos;

			unset($res[$id]);
		}
		if(count($res2) > 1 && $var_id !== (count($vars)-1)) {

			$res2 = self::recherche_r($vars, $societe, $res2, $var_id++);
		}
		return $res2;
	}
}
