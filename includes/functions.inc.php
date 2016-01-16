<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * L'ensemble des fonctions nécessaires à PWD
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 * @subpackage includes
 */

if (!defined('CONTROLECODE')) exit;

/**
 * Initialisation des paramètres de PWD
 */
function initSettings() {
	global $gSettings;
	$gSettings = loadSettings();
	if($gSettings === FALSE) {
		$gSettings = array();
		$gSettings['options']['groupes']['groupe'] = array();
		$gSettings['sites']['site'] = array();
		saveSettings($gSettings);
	}
}

/**
 * retourne le nom du fichier de base
 */
function getSettingsName() {
	$filename = gBASE_PATH.gBASE_NAME;
	return $filename;
}

/**
 * Charge la liste des sites
 */
function loadSettings() {
	$filename = getSettingsName();
	
	if(file_exists($filename) === FALSE) {
		return FALSE;
	}
	
	$base = join('', file($filename));
	$settings = XMLto($base);
	
	return $settings;
}

/**
 * Sauvegarde la base de données
 * @param array $settings
 */
function saveSettings($settings = array()) {
	$filename = getSettingsName();
	$texte = toXML($settings);
	$file = fopen($filename, 'w+');
	fputs($file, $texte);
	fclose($file);
}

/**
 * Transforme un tableau en XML
 * @param string $tableau
 * @return xml
 */
function toXML($tableau = '') {
	$nl = "\n";
	$xml = '<?xml version="1.0" encoding="utf-8"?>'.$nl;
	$xml .= '<prelude-wordpress-dashboard>'.$nl;
	$xml .= '<options>'.$nl;
	$xml .= '	<groupes>'.$nl;
	foreach($tableau['options']['groupes']['groupe'] as $key => $eGroupe) {
		$xml .= '		<groupe>'.$nl;
		$xml .= '			<id>'.$eGroupe['id'].'</id>'.$nl;
		$xml .= '			<name>'.$eGroupe['name'].'</name>'.$nl;
		$xml .= '		</groupe>'.$nl;
	}
	$xml .= '	</groupes>'.$nl;
	$xml .= '</options>'.$nl;
	
	$xml .= '<sites>'.$nl;
	foreach($tableau['sites']['site'] as $key => $eBlog) {
		$blogName = str_replace('&', '&amp;', $eBlog['name']);
		$blogUrl = str_replace('&', '&amp;', $eBlog['url']);
		$xml .= '	<site>'.$nl;
		$xml .= '		<name>'.$blogName.'</name>'.$nl;
		$xml .= '		<url>'.$blogUrl.'</url>'.$nl;
		$xml .= '		<groupe>'.$eBlog['groupe'].'</groupe>'.$nl;
		$xml .= '		<version_url>'.$eBlog['version_url'].'</version_url>'.$nl;
		$xml .= '		<version_pass>'.$eBlog['version_pass'].'</version_pass>'.$nl;
		$xml .= '	</site>'.$nl;		
	}
	$xml .= '</sites>'.$nl;
	$xml .= '</prelude-wordpress-dashboard>'.$nl;

	return $xml;
}

/**
 * Transforme un texte XML en tableau, plus quelques trucs
 * @param string $filename
 * @return array
 */
function XMLto($xml = '') {
	$xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	$array = json_decode(json_encode((array)$xml), TRUE);
	
	// traiter les cas où il n'y a qu'un seul groupe
	if(isset($array['options']['groupes']['groupe']) === FALSE) {
		$array['options']['groupes']['groupe'] = array();
	
	} else if(isset($array['options']['groupes']['groupe'][0]) === FALSE) {
		$temp = $array['options']['groupes']['groupe'];
		$array['options']['groupes']['groupe'] = array();
		$array['options']['groupes']['groupe'][0] = $temp;
	}
	
	asort($array['options']['groupes']['groupe']);
	
	// traiter les cas où il n'y a qu'un seul site
	if(isset($array['sites']['site']) === FALSE) {
		$array['sites']['site'] = array();
		
	} else if(isset($array['sites']['site'][0]) === FALSE) {
		$temp = $array['sites']['site'];
		$array['sites']['site'] = array();
		$array['sites']['site'][0] = $temp;
	}
	
	return $array;
}


/**
 * Récupération des paramètres d'un formulaire
 * @param string $var
 */
function getRequest($var = '') {
	if(isset($_REQUEST[$var]) === FALSE) {
		return '';
	
	} else {
		if(is_array($_REQUEST[$var]) === TRUE) {
			return $_REQUEST[$var];
				
		} else {
			return trim($_REQUEST[$var]);
		}
	}
}

/**
 * Création d'un id à partir du nom d'un groupe
 * @param string $groupe
 */
function createIdFromGroupe($groupe = '') {
	$id = md5($groupe);
	return $id;
}


/**
 * Ajoute un groupe et retourne l'id correspondant
 * Si le groupe existe déjà, l'id de ce groupe est retourné et aucun groupe n'est ajouté
 * @param string $groupe
 */
function addNewGroupe($groupe = '') {
	global $gSettings;
	if(isset($gSettings['options']['groupes']['groupe']) === TRUE) {
		foreach($gSettings['options']['groupes']['groupe'] as $id => $eGroupe) {
			if($eGroupe['name'] == $groupe) {
				return $id;
			}
		}
	}
	
	$groupeNew = array(
			'id'	=> createIdFromGroupe($groupe),
			'name'	=> $groupe
	);
	
	$gSettings['options']['groupes']['groupe'][] = $groupeNew;
	saveSettings($gSettings);
	return $groupeNew['id'];
}


/**
 * Surcharge de "redirect"
 * @param string $url
 */
function redirectInterne($url = '') {
	header('Location: '.$url);
	exit();
}

/**
 * Renvoie l'url de l'administration d'un blog WordPress
 * @param string $url
 */
function getUrlAdmin($url = '') {
	if($url[strlen($url) - 1] != '/') {
		$url .= '/';
	}
	$urlAdmin = $url.'wp-admin/';
	return $urlAdmin;
}

/**
 * Récupère les informations concernant un blog
 * @param string $url
 * @param string $versionUrl
 * @param string $versionPass
 */
function getInfoBlog($url = '', $versionUrl = '', $versionPass = '') {
	$fichier = $url;
	if($url[strlen($url) - 1] == '/') {
		$fichier .= '?feed='.$versionUrl.'&pass='.$versionPass;
		
	} else {
		$fichier .= '/?feed='.$versionUrl.'&pass='.$versionPass;
	}
	$md5url = md5($url);
	if(cacheState($md5url, gCACHE_TIME_VERSION) === FALSE) {
		$xml = simplexml_load_file($fichier);
		$array = json_decode(json_encode((array)$xml), TRUE);
		cacheSet($md5url, $array);
		
	} else {
		$array = cacheGet($md5url); 
	}
	
	// traiter les cas où il n'y a qu'un seul groupe
	if(isset($array['plugins']['plugin']) === FALSE) {
		$array['plugins']['plugin'] = array();
	
	} else if(isset($array['plugins']['plugin'][0]) === FALSE) {
		$temp = $array['plugins']['plugin'];
		$array['plugins']['plugin'] = array();
		$array['plugins']['plugin'] = $temp;
	}
	
	return $array;
}




/**
 * Charge la liste des plugins de façon asynchron
 */
function getPluginVersionAsynchron() {
	global $gSettings;
	
	// création de la liste des slugs
	$listePlugins = array();
	foreach($gSettings['sites']['site'] as $key => $eBlog) {
		if($eBlog['version_url'] == '-') {
			continue;
		}
		$blogInfos = getInfoBlog($eBlog['url'], $eBlog['version_url'], $eBlog['version_pass']);
		$plugins = array();
		foreach($blogInfos['plugins']['plugin'] as $key => $ePlugin) {
			if(isset($listePlugins[$ePlugin['slug']]) === FALSE) {
				$pos = strpos($ePlugin['slug'], '/');
				$slug = substr($ePlugin['slug'], 0, $pos);
					
				if($slug != '') {
					$listePlugins[$slug] = $slug;
				}
			}
		}
	}
	
	// chargement des infos
	$urlAPI = array();
	foreach($listePlugins as $key => $eSlug) {
		if(cacheState($eSlug, gCACHE_TIME_PLUGINS) === FALSE) {
			$urlAPI[$eSlug] = gWORDPRESS_API_PLUGIN.$eSlug.'.json';
		}
	}
	
	$result = multiCurlAsynchrone($urlAPI);
	
	foreach($result as $slug => $eResult) {
		$infos = json_decode($eResult['content'], TRUE);
		cacheSet($slug, $infos);
	}	
}

/**
 * Chargement asynchrone d'une série d'URLs
 * @param array $data
 */
function multiCurlAsynchrone($data) {
	$curly = array();
	
	$result = array();
	
	// multi handle
	$mh = curl_multi_init();

	// création des demandes
	foreach($data as $id => $url) {
		$curly[$id] = curl_init();
		
		curl_setopt($curly[$id], CURLOPT_URL, $url);
		curl_setopt($curly[$id], CURLOPT_TIMEOUT, 30);
		curl_setopt($curly[$id], CURLOPT_HEADER, 0);
		curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

		curl_multi_add_handle($mh, $curly[$id]);
	}

	// Éxécution des handles
	$running = 9;
	while($running > 0){
		curl_multi_exec($mh, $running);
		curl_multi_select($mh);
	}

	// Récupération des résultats et fermeture des handles
	foreach($curly as $id => $ch) {
		$content = curl_multi_getcontent($ch);
		$info = curl_getinfo($ch);
		$result[$id]['content'] = $content;
		$result[$id]['info'] = $info;
		curl_multi_remove_handle($mh, $ch);
	}

	// on ferme tout
	curl_multi_close($mh);
	
	return $result;
}


/**
 * Renvoie la dernière version d'un plugin en fonction de son "slug"
 * @param string $slugUrl
 */
function getPluginVersion($slugUrl = '') {
	$pos = strpos($slugUrl, '/');
	$slug = substr($slugUrl, 0, $pos);
	
	if($slug == '') {
		return FALSE;
	}
	
	if(cacheState($slug, gCACHE_TIME_PLUGINS) === FALSE) {
		$urlAPI = gWORDPRESS_API_PLUGIN.$slug.'.json';
		
		$json = join('', file($urlAPI));
		$infos = json_decode($json, TRUE);
		cacheSet($slug, $infos);
		
	} else {
		$infos = cacheGet($slug);
	}
	return $infos;
}

/**
 * Renvoie la dernière version de WordPress
 */
function getWordPressVersion() {
	$fileCache = 'wordpress';
	if(cacheState($fileCache, gCACHE_TIME_WORDPRESS) === FALSE) {
		$urlAPI = gWORDPRESS_API_CORE;

		$json = join('', file($urlAPI));
		$infos = json_decode($json, TRUE);
		cacheSet($fileCache, $infos);

	} else {
		$infos = cacheGet($fileCache);
	}
	return $infos;
}


/**
 * Retourne le nom d'un fichier en cache
 * @param string $file
 */
function cacheSetFilename($file = '') {
	$filename = gCACHE_PATH.$file.'.txt';
	return $filename;
}


/**
 * Est-ce qu'un fichier est présent ou non dans le cache
 * @param string $file
 */
function cacheState($file = '', $time = gCACHE_TIME_DEFAULT) {
	$filename = cacheSetFilename($file);
	$timeMax = $time;	// + (mt_rand(0, 3600) - 1800);
	if(file_exists($filename) === TRUE and filemtime($filename) > time() - $timeMax) {
		return TRUE;
	}
	
	return FALSE;
}

/**
 * retourne le fichier en cache
 * @param string $file
 */
function cacheGet($file = '') {
	$filename = cacheSetFilename($file);
	if(file_exists($filename) === TRUE) {
		$cache = join('', file($filename));
		
	} else {
		$cache = '';
	}
	
	return unserialize($cache);
}

/**
 * Mise à jour du cache
 * @param string $file
 * @param string $cache
 */
function cacheSet($file = '', $cache = '') {
	$filename = cacheSetFilename($file);
	$file = fopen($filename, 'w+');
	fputs($file, serialize($cache));
	fclose($file);
}

function cacheDel($file = '') {
	$filename = cacheSetFilename($file);
	if(file_exists($filename) === TRUE) {
		unlink($filename);
	}
}


/**
 * Affiche une date GMT au format ISO
 * @param string $dateGMT
 */
function viewDate($dateGMT = '') {
	$annee = substr($dateGMT, 0, 4);
	$mois = substr($dateGMT, 5, 2);
	$jour = substr($dateGMT, 8, 2);
	if(substr($dateGMT, 0, 10) == date('Y-m-d')) {
		$date = 'Aujourd\'hui';
			
	} else if(substr($dateGMT, 0, 10) == date('Y-m-d', mktime(0,0,0,date('m'), date('d')-1, date('Y')))) {
		$date = 'Hier';
			
	} else {
		$date = $jour.'/'.$mois.'/'.$annee;
	}
	return $date;
}

/**
 * Fonction user de tri par rapport à la valeur 'use'
 * @param string $value1
 * @param string $value2
 */
function sortByUse($value1 = '', $value2 = '') {
	if($value1['use'] < $value2['use']) {
		return 1;
		
	} else if($value1['use'] > $value2['use']) {
		return -1;
		
	} else {
		return 0;
	}
}