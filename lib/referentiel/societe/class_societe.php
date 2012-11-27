<?php
class societe_index extends societe_collection{

	public $url_tpl	 = 'http://www.bilansgratuits.fr/{url_details}';

	public function verif_raison_sociale(){

		return true;
	}
	public function verif_code_postal(){

		if($this->code_postal === '') return true;

		$list = file_get_contents('communes.csv');
		$list = trim($list);
		$list = explode($this->csv_eol, $list);

		foreach($list as $id => $csv){

			$csv = explode($this->csv_sep, $csv);

			if($this->code_postal !== $csv[1]) continue;

			if($this->departement !== '' && strstr('(', $this->departement) !== false){

				$departement			= explode('(', $this->departement);
				$departement 			= explode(')', $departement[1]);
				$this->departement_num 	= $departement[0];
			}
			$this->commune 				= $csv[0];
			$code_postal 				= $csv[1];
			$this->departement 			= $csv[2];
			$this->departement_insee 	= $csv[3];
		}
		return true;
	}
	public function verif_commune(){

		if($this->commune === '') return true;

		$list = file_get_contents('communes.csv');
		$list = trim($list);
		$list = explode($this->csv_eol, $list);

		foreach($list as $id => $csv){

			$csv = explode($this->csv_sep, $csv);

			if($this->commune !== $csv[0]) continue;

			if($this->departement !== '' && strstr('(', $this->departement) !== false){

				$departement			= explode('(', $this->departement);
				$departement 			= explode(')', $departement[1]);
				$this->departement_num 	= $departement[0];
			}
			$this->commune 				= $csv[0];
			$code_postal 				= $csv[1];
			$this->departement 			= $csv[2];
			$this->departement_insee 	= $csv[3];
		}
		return true;
	}
	public function verif_departement(){

		return true;
	}
	public function verif_departement_num(){

		return true;
	}
	public function verif_departement_insee(){

		return true;
	}
	public function verif_activite(){

		if($this->activite === '') return true;

		if(strstr($this->activite) === false) return true;

		$act 			= explode('<br />', $this->activite);
		$this->naf 		= $act[0];
		$this->activite = $act[1];

		return true;
	}
	public function verif_naf(){

		if($this->naf === '')  return true;

		$list = file_get_contents('naf2008_5_niveaux_liste.csv');
		$list = trim($list);
		$list = explode($this->csv_eol, $list);

		$n1 = file_get_contents('naf2008_5_niveaux_Nivau_1.csv');
		$n1 = trim($n1);
		$n1 = explode($this->csv_eol, $n1);

		$n2 = file_get_contents('naf2008_5_niveaux_Nivau_2.csv');
		$n2 = trim($n2);
		$n2 = explode($this->csv_eol, $n2);

		$n3 = file_get_contents('naf2008_5_niveaux_Nivau_3.csv');
		$n3 = trim($n3);
		$n3 = explode($this->csv_eol, $n3);

		$n4 = file_get_contents('naf2008_5_niveaux_Nivau_4.csv');
		$n4 = trim($n4);
		$n4 = explode($this->csv_eol, $n4);

		$n5 = file_get_contents('naf2008_5_niveaux_Nivau_5.csv');
		$n5 = trim($n5);
		$n5 = explode($this->csv_eol, $n5);

		foreach($list as $id => $csv){

			$csv = substr($csv, 1, -1);
			$csv = explode(".$this->csv_sep.", $csv);

			if($this->naf !== $csv[0]) continue;

			$naf_niv1	= $csv[0];
			$naf_niv2 	= $csv[1];
			$naf_niv3 	= $csv[2];
			$naf_niv4	= $csv[3];
			$naf_niv5 	= $csv[4];

			foreach($n1 as $id1 => $csv1){

				$csv1 = substr($csv1, 1, -1);

				if($naf_niv1 !== $csv1[0]) continue;

				$this->naf_niv1	= $csv1[1];
			}
			foreach($n2 as $id2 => $csv2){

				$csv2 = substr($csv2, 1, -1);

				if($naf_niv2 !== $csv2[0]) continue;

				$this->naf_niv2	= $csv2[1];
			}
			foreach($n3 as $id3 => $csv3){

				$csv3 = substr($csv3, 1, -1);

				if($naf_niv3 !== $csv3[0]) continue;

				$this->naf_niv3	= $csv3[1];
			}
			foreach($n4 as $id4 => $csv4){

				$csv4 = substr($csv4, 1, -1);

				if($naf_niv4 !== $csv4[0]) continue;

				$this->naf_niv4	= $csv4[1];
			}
			foreach($n5 as $id5 => $csv5){

				$csv5 = substr($csv5, 1, -1);

				if($naf_niv5 !== $csv5[0]) continue;

				$this->naf_niv5	= $csv5[1];
			}
		}
		return true;
	}
	public function verif_naf_niv1(){

		if($this->naf_niv1 === '') return true;

		return true;
	}
	public function verif_naf_niv2(){

		if($this->naf_niv2 === '') return true;

		return true;
	}
	public function verif_naf_niv3(){

		if($this->naf_niv3 === '') return true;

		return true;
	}
	public function verif_naf_niv4(){

		if($this->naf_niv4 === '') return true;

		return true;
	}
	public function verif_naf_niv5(){

		if($this->naf_niv5 === '') return true;

		return true;
	}
	public function verif_siret(){

		if($this->siret === '') return true;

		$this->siren 		= substr($this->siret, 0, 9);
		$this->etabl_ident 	= substr($this->siret, 9, 5);

		return true;
	}
	public function verif_siren(){

		if($this->siren === '') return true;

		return true;
	}
	public function verif_etabl_ident(){

		if($this->etabl_ident === '') return true;

		return true;
	}
	public function verif_region(){

		if($this->region === '') return true;

		return true;
	}
	public function verif_siret_principal(){

		if($this->siret_principal === '') return true;

		return true;
	}
	public function verif_url_details(){

		if($this->siret_principal === '') return true;

		return true;
	}
	public function verif_adresse(){

		if($this->adresse === '') return true;

		$adresse 		= explode('<br />', $this->adresse);
		$this->adresse 	= $adresse[0];

		return true;
	}
	public function verif_forme_juridique(){

		if($this->forme_juridique === '') return true;

		return true;
	}
	public function verif_capital(){

		if($this->capital === '') return true;

		$capital 		= explode(' ', $this->capital);
		$this->adresse 	= $capital[0];

		return true;
	}
	public function verif_creation(){

		if($this->creation === '') return true;

		return true;
	}
	public function verif_effectif(){

		if($this->effectif === '') return true;

		$this->effectif = str_replace(' salarié', 	'', $this->effectif);
		$this->effectif = str_replace(' salariés', 	'', $this->effectif);
		$this->effectif = str_replace(' salariÃ©', 	'', $this->effectif);
		$this->effectif = str_replace(' salariÃ©s', '', $this->effectif);

		return true;
	}
	public function verif_nb_etabl(){

		if($this->nb_etabl === '') return true;

		return true;
	}
	public function verif_enseigne(){

		if($this->enseigne === '') return true;

		return true;
	}
	public function verif_societe_type(){

		if($this->societe_type === '') return true;

		return true;
	}
	public function clean_csv(){

		$vars = self::set_options();
		$vars = array_keys($vars);

		foreach($vars as $k){

		}
		return true;
	}
	public static function get_extract(){

		$extract 							= array();
		$extract['enseigne']				= array();
		$extract['enseigne']['befor']		= 'Enseigne
	</th>
	<td>';
		$extract['enseigne']['after']		= '</td>';

		$extract['siret']					= array();
		$extract['siret']['befor']			= 'Siret
	</th>
	<td>';
		$extract['siret']['after']			= '</td>';

		$extract['tva']						= array();
		$extract['tva']['befor']			= 'intracommunautaire
	</th>
	<td>';
		$extract['tva']['after']			= '</td>';

		$extract['adresse']					= array();
		$extract['adresse']['befor']		= 'Adresse
	</th>
	<td>';
		$extract['adresse']['after']		= '</td>';

		$extract['region']					= array();
		$extract['region']['befor']			= 'gion
	</th>
	<td>';
		$extract['region']['after']			= '</td>';

		$extract['departement']				= array();
		$extract['departement']['befor']	= 'partement
					</th>
					<td>';
		$extract['departement']['after']	= '</td>';

		$extract['forme_juridique']				= array();
		$extract['forme_juridique']['befor']	= 'juridique
					</th>
					<td>';
		$extract['forme_juridique']['after']	= '</td>';

		$extract['capital']						= array();
		$extract['capital']['befor']			= 'Capital
					</th>
					<td>';
		$extract['capital']['after']			= '</td>';

		$extract['creation']					= array();
		$extract['creation']['befor']			= 'ation
					</th>
					<td>';
		$extract['creation']['after']			= '</td>';

		$extract['activite']					= array();
		$extract['activite']['befor']			= 'ActivitÃƒÂ©
					</th>
					<td>';
		$extract['activite']['after']			= '</td>';

		$extract['effectif']					= array();
		$extract['effectif']['befor']			= 'Effectif
							</th>
							<td>';
		$extract['effectif']['after']			= '</td>';

		$extract['nb_etabl']					= array();
		$extract['nb_etabl']['befor']			= 'tablissement(s)
							</th>
							<td>';
		$extract['nb_etabl']['after']			= '</td>';

		$extract['type']						= array();
		$extract['type']['befor']				= 'Type
							</th>
							<td>';
		$extract['type']['after']				= '</td>';

		return $extract;
	}
}
?>