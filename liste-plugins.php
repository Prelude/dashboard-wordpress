<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Visualisation de la liste plugins utilisés sur l'ensemble des sites
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 */
include 'config.inc.php';

$metaTitle = 'PWD - Liste plugins';

$listePlugins = array();
foreach($gSettings['sites']['site'] as $key => $eBlog) {
	if($eBlog['version_url'] == '-') {
		continue;
	}
	$blogInfos = getInfoBlog($eBlog['url'], $eBlog['version_url'], $eBlog['version_pass']);
	$plugins = array();
	foreach($blogInfos['plugins']['plugin'] as $key => $ePlugin) {
		if(isset($listePlugins[$ePlugin['slug']]) === TRUE) {
			$listePlugins[$ePlugin['slug']]['use']++;
			$listePlugins[$ePlugin['slug']]['blogs'] .= $eBlog['name'].'<br />';
			
		} else {
			$listePlugins[$ePlugin['slug']] = $ePlugin;
			$listePlugins[$ePlugin['slug']]['use'] = 1;
			$listePlugins[$ePlugin['slug']]['blogs'] = $eBlog['name'].'<br />';
			$listePlugins[$ePlugin['slug']]['infos'] = getPluginVersion($ePlugin['slug']);
		}
	}
}

usort($listePlugins, 'sortByUse');

include 'includes/header.inc.php';

include 'includes/navigation.inc.php';

?>
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Liste des extensions utilisées</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
            <?php
$html = '';
foreach($listePlugins as $key => $ePlugin) {
	include 'includes/view-plugin-liste.inc.php';
}
echo $html;

?>
			</div>
            
		</div>
		<!-- /#page-wrapper -->

<?php include 'includes/footer.inc.php'; ?>