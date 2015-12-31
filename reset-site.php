<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Supprime du cache les infos concernant un site
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @version 1.0.0
 * @package PWD
 */
include 'config.inc.php';

$urlReturn = $_SERVER['HTTP_REFERER'];

$id = getRequest('id');
if(is_numeric($id) === FALSE) {
	redirectInterne($urlReturn);
}

if(isset($gSettings['sites']['site'][$id]) === FALSE) {
	redirectInterne($urlReturn);
}

$blog = $gSettings['sites']['site'][$id];
if($blog['version_url'] == '-') {
	redirectInterne($urlReturn);
}

cacheDel(md5($blog['url']));


redirectInterne($urlReturn);