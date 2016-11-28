<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Affichage du thème concernant un blog
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 * @subpackage includes
 */
$version = '?';
$pluginUpdate = FALSE;
$preludeVersion = TRUE;
$themeName = '-';
$themeVersion = '-';

if($eBlog['version_url'] != '-') {
	$blogInfos = getInfoBlog($eBlog['url'], $eBlog['version_url'], $eBlog['version_pass']);
	if($blogInfos != FALSE) {
		if(isset($blogInfos['version']) === TRUE) {
			$version = $blogInfos['version'];
			
		} else {
			$version = '???';
		}
		if(isset($blogInfos['theme']['name']) === TRUE) {
			$themeName = $blogInfos['theme']['name'];
			$themeVersion = $blogInfos['theme']['version'];
		}
		$nbrPlugins = count($blogInfos['plugins']['plugin']);
		foreach($blogInfos['plugins']['plugin'] as $keyPlugins => $ePlugin) {
			$pluginsInfos = getPluginVersion($ePlugin['slug']);
			if($pluginsInfos != null) {
				if($pluginsInfos['version'] > $ePlugin['version']) {
					$pluginUpdate = TRUE;
					break;
				}
			}
		}
	}
		
} else {
	$preludeVersion = FALSE;
	$nbrPlugins = '?';
}

if(strlen($themeName) > 25) {
	$themeNameComplete = $themeName;
	$themeName = substr($themeName, 0, 25).'...';
	$themeName = '<div class="tooltips"><span data-placement="bottom" data-toggle="tooltip" data-original-title="'.$themeNameComplete.'">'.$themeName.'</span></div>';
}


$urlAdmin = getUrlAdmin($eBlog['url']);
// Prelude Version pas installé
if($preludeVersion === FALSE) {
	$color = 'primary';
	$colorLink = 'primary';

	// WordPress Core et Plugins pas à jour
} else if($version < $versionMaster and $pluginUpdate === TRUE) {
	$color = 'red';
	$colorLink = 'danger';

	// WordPress Core
} else if($version < $versionMaster) {
	$color = 'yellow';
	$colorLink = 'warning';
		
} else {
	$color = 'green';
	$colorLink = 'success';
}

if($pluginUpdate === TRUE) {
	$pluginText = '<span class="pluginUpdate">Extensions ('.$nbrPlugins.') <span class="fa fa-warning"></span></span>';
		
} else {
	$pluginText = 'Extension ('.$nbrPlugins.')';
}
if($gViewHuge === TRUE) {
	$html .= '<div class="col-lg-4 col-md-6">';
	
} else {
	$html .= '<div class="col-lg-3 col-md-6">';
}
if($groupeView != '') {
		$html .= '<div class="title-groupe"><h3 style="color:'.$groupeColor.'">'.$groupeView.'</h3></div>';
	}
	if($groupeColor != '') {
		$html .= '<div class="barre-color" style="background-color:'.$groupeColor.'"></div>';
	}
	$html .= '	<div class="panel panel-'.$color.'">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-2">
	                                    <i class="fa fa-wordpress fa-2x"></i>
	                                </div>
	                                <div class="col-xs-10 text-right">
	                                    <div>'.$eBlog['name'].'</div>
									</div>
								</div>
							</div>
							<div>
								<div class="panel-footer">
									<span class="pull-left">Thème</span>
									<span class="pull-right">'.$themeName.'</span>
									<div class="clearfix"></div>
								</div>
							</div>
							<div>
								<div class="panel-footer">
									<span class="pull-left">Version</span>
									<span class="pull-right">'.$themeVersion.'</span>
									<div class="clearfix"></div>
								</div>
							</div>
							<a href="'.$urlAdmin.'" target="_blank">
								<div class="panel-footer">
									<span class="pull-left">Administration</span>
									<span class="pull-right"><i class="fa fa-dashboard"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
					</div>';


