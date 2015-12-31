<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Affichage des informations concernant un plugin
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @version 1.0.0
 * @package PWD
 * @subpackage includes
 */
// plugin inconnu
if($ePlugin['infos'] == null) {
	$color = 'primary';
	$colorLink = 'primary';

	// pas à jour
} else if($ePlugin['version'] < $ePlugin['infos']['version']) {
	$color = 'red';
	$colorLink = 'danger';

	// plugin à jour
} else {
	$color = 'green';
	$colorLink = 'success';
}
$lastUpdate = viewDate($ePlugin['infos']['last_updated']);

	$html .= '<div class="col-lg-4 col-md-6">
	                    <div class="panel panel-'.$color.'">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-1">
	                                    <i class="fa fa-puzzle-piece fa-2x"></i>
	                                </div>
	                                <div class="col-xs-10 text-right">
	                                    <div><strong>'.$ePlugin['version'].'</strong></div>
	                                    <div class="little">'.$ePlugin['name'].'</div>
									</div>
								</div>
							</div>
							<div>';
	if($ePlugin['infos'] == null) {
		$html .= '				<div class="panel-footer">
									<span class="pull-left little">Pas d\'informations</span>
									<div class="clearfix"></div>
								</div>';
	} else {
		$html .= '				<div class="panel-footer">
									<span class="pull-left little">Dernière version : <strong>'.$ePlugin['infos']['version'].'</strong> le <strong>'.$lastUpdate.'</strong></span>
									<div class="clearfix"></div>
								</div>';
	}
									
	$html .= '				</div>
						</div>
					</div>';

