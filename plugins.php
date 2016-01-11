<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Visualisation des plugins pour un site donné
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 */
include 'config.inc.php';

$metaTitle = 'PWD - Plugins';

$id = getRequest('id');
if(is_numeric($id) === FALSE) {
	redirectInterne('./');
}

if(isset($gSettings['sites']['site'][$id]) === FALSE) {
	redirectInterne('./');
}

$blog = $gSettings['sites']['site'][$id];
if($blog['version_url'] == '-') {
	redirectInterne('./');
}

$blogInfos = getInfoBlog($blog['url'], $blog['version_url'], $blog['version_pass']);
$plugins = array();
foreach($blogInfos['plugins']['plugin'] as $key => $ePlugin) {
	$pluginsInfos = getPluginVersion($ePlugin['slug']);
	$plugins[$key] = $ePlugin;
	$plugins[$key]['infos'] = $pluginsInfos;
}

include 'includes/header.inc.php';

include 'includes/navigation.inc.php';

?>
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo $blog['name']; ?> - Extensions</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
            <?php
$html = '';
foreach($plugins as $key => $ePlugin) {
	include 'includes/view-plugin.inc.php';
}
echo $html;

?>
			</div>
            
		</div>
		<!-- /#page-wrapper -->

<?php include 'includes/footer.inc.php'; ?>