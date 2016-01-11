<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Affichage des informations concernant un plugin dans une liste
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
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
	                    			<div class="col-xs-10">
	                                    <div><strong>'.$ePlugin['version'].'</strong></div>
	                                    <div class="little">'.$ePlugin['name'].'</div>
									</div>
	                    			<div class="col-xs-2 text-right huge tooltips">
	                                    <span data-placement="bottom" data-toggle="tooltip" data-original-title="'.$ePlugin['blogs'].'">'.$ePlugin['use'].'x</span>
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

