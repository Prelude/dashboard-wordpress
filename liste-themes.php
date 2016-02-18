<?php 
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Visualisation de thème utilisé pour chaque site
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 */
include 'config.inc.php';

// mise à jour des plugins
getPluginVersionAsynchron();

$metaTitle = 'Prélude WordPress Dashboard (PWD)';

include 'includes/header.inc.php';

include 'includes/navigation.inc.php';
?>
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Liste des thèmes</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
            <?php
$html = '';
$colorIndex = 0;
foreach($gSettings['options']['groupes']['groupe'] as $keyGroupe => $eGroupe) {
	//$html .= '<div class="col-lg-12"><h2>'.$eGroupe['name'].'</h2></div>';
	//$html .= '<div class="col-lg-1 col-md-1"><h3>'.$eGroupe['name'].'</h3></div>';
	$groupeView = $eGroupe['name'];
	$groupeColor = $gPaletteColors[$colorIndex];
	foreach($gSettings['sites']['site'] as $key => $eBlog) {
		if($eBlog['groupe'] != $eGroupe['id']) {
			continue;
		}
		include 'includes/view-theme.inc.php';
		$groupeView = '';
	}
	$colorIndex++;
}
echo $html;

if($gSettings['sites']['site'] < 1) {		// si pas encore de blog
			?>                
				<div class="col-lg-3 col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="glyphicon glyphicon-plus-sign fa-3x"></i>
								</div>

							</div>
						</div>
						<a href="add.php">
							<div class="panel-footer">
								<span class="pull-left">Ajouter un blog</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
<?php } ?>
			</div>
            
		</div>
		<!-- /#page-wrapper -->

<?php include 'includes/footer.inc.php'; ?>