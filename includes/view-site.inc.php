<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Affichage des informations concernant un blog
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 * @subpackage includes
 */
$version = '?';
$pluginUpdate = FALSE;
$preludeVersion = TRUE;
if($eBlog['version_url'] != '-') {
	$blogInfos = getInfoBlog($eBlog['url'], $eBlog['version_url'], $eBlog['version_pass']);
	if($blogInfos != FALSE) {
		$version = $blogInfos['version'];
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
	$html .= '<div class="col-lg-3 col-md-6">
	                    <div class="panel panel-'.$color.'">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-2">
	                                    <i class="fa fa-wordpress fa-2x"></i>
	                                </div>
	                                <div class="col-xs-10 text-right">
	                                    <div class="huge">'.$version.'</div>
	                                    <div>'.$eBlog['name'].'
	    								<a href="add.php?id='.$key.'" class="btn btn-'.$colorLink.' btn-circle btn-mini" type="button">
												<i class="fa fa-pencil"></i>
										</a></div>
									</div>
								</div>
							</div>
							<a href="'.$eBlog['url'].'" target="_blank">
								<div class="panel-footer">
									<span class="pull-left">Voir le site</span>
									<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>
							<a href="plugins.php?id='.$key.'" target="_blank">
								<div class="panel-footer">
									<span class="pull-left">'.$pluginText.'</span>
									<span class="pull-right"><i class="fa fa-puzzle-piece"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>
							<a href="'.$urlAdmin.'" target="_blank">
								<div class="panel-footer">
									<span class="pull-left">Administration</span>
									<span class="pull-right"><i class="fa fa-dashboard"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
					</div>';

} else {
	$html .= '<div class="col-lg-2 col-md-4">
	                    <div class="panel panel-'.$color.'">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-1">
	                                    <i class="fa fa-wordpress fa"></i>
	                                </div>
	                                <div class="col-xs-14 text-right">
	                                    <div><strong><a href="reset-site.php?id='.$key.'" title="Revérifier ce site">'.$version.'</strong></a></div>
	                                    <div class="little">'.$eBlog['name'].'
	    								<a href="add.php?id='.$key.'" class="btn btn-'.$colorLink.' btn-circle btn-mini" type="button">
												<i class="fa fa-pencil"></i>
										</a></div>
									</div>
								</div>
							</div>
							<a href="'.$eBlog['url'].'" target="_blank">
								<div class="panel-footer">
									<span class="pull-left little">Voir le site</span>
									<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>';
	if($preludeVersion === TRUE) {
		$html .= '<a href="plugins.php?id='.$key.'">
								<div class="panel-footer">
									<span class="pull-left little">'.$pluginText.'</span>
									<span class="pull-right"><i class="fa fa-puzzle-piece"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>';
	} else {
		$html .= '<div>
								<div class="panel-footer">
									<span class="pull-left little">'.$pluginText.'</span>
									<span class="pull-right"><i class="fa fa-puzzle-piece"></i></span>
									<div class="clearfix"></div>
								</div>
							</div>';
	}
		$html .= '<a href="'.$urlAdmin.'" target="_blank">
								<div class="panel-footer">
									<span class="pull-left little">Administration</span>
									<span class="pull-right"><i class="fa fa-dashboard"></i></span>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
					</div>';
}
