<?php
/**
 * Prelude WordPress Dashboard (PWD) est un tableau de bord permettant de gérer plusieurs sites sous WordPress.
 *
 * Ajout et modification d'un site
 *
 * @author Jean-François RENAULD - http://www.prelude-prod.fr/
 * @package PWD
 */
include 'config.inc.php'; 

$idSite = getRequest('id');
$groupe = getRequest('groupe');
$groupeNew = getRequest('groupe-new');
$name = getRequest('name');
$url = getRequest('url');
$versionUrl = getRequest('version-url');
$versionPass = getRequest('version-pass');

$eSite = array(
		'name'			=> '',
		'url'			=> '',
		'groupe'		=> '',
		'version_url'	=> '',
		'version_pass'	=> ''
);
if($idSite != '') {
	if(isset($gSettings['sites']['site'][$idSite]) === FALSE) {
		redirectInterne('/');
		
	} else {
		$eSite = $gSettings['sites']['site'][$idSite];
	}
}

$error['groupe'] = FALSE;
$error['groupe-new'] = FALSE;
$error['name'] = FALSE;
$error['url'] = FALSE;
$messageError = '';

$action = getRequest('action');
// est-ce que l'on vient d'envoyer le formulaire ?
if($action == 'ok') {
	if($groupe == '' and $groupeNew == '') {
		$error['groupe'] = TRUE;
		$messageError .= 'Veuillez indiquer un groupe<br />';
	}
	
	if($name == '') {
		$error['name'] = TRUE;
		$messageError .= 'Veuillez indiquer un nom pour ce blog<br />';
	}
	
	if($url == '') {
		$error['url'] = TRUE;
		$messageError .= 'Veuillez indiquer l\'adresse de ce blog<br />';
	}
	
	// ok, on enregistre ?
	if($messageError == '') {
		if($groupeNew != '') {
			$idGroupe = addNewGroupe($groupeNew);
			
		} else {
			$idGroupe = $groupe;
		}
		
		if($versionUrl == '') {
			$versionUrl = '-';
		}
		if($versionPass == '') {
			$versionPass = '-';
		}
		$blog = array(
				'groupe'		=> $idGroupe,
				'name'			=> $name,
				'url'			=> $url,
				'version_url'	=> $versionUrl,
				'version_pass'	=> $versionPass
		);
		if($idSite != '') {
			$gSettings['sites']['site'][$idSite] = $blog;
			
		} else {
			$gSettings['sites']['site'][] = $blog;
		}
		saveSettings($gSettings);
		cacheDel(md5($url));
		redirectInterne('/');
	}	
}
if($eSite['version_url'] == '-') {
	$eSite['version_url'] = '';
}
if($eSite['version_pass'] == '-') {
	$eSite['version_pass'] = '';
}

$metaTitle = 'PWD - Ajouter / modifier un blog';

include 'includes/header.inc.php';

include 'includes/navigation.inc.php';
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Ajouter un blog</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Ajouter un blog WordPress
						</div>
						<div class="panel-body">
							<div class="row">
							<?php if($messageError != '') { ?>
								<div class="col-lg-12">
									<div class="alert alert-danger alert-dismissable">
		                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                <?php echo $messageError; ?>
		                            </div>
								</div>
							<?php } ?>
								<form action="add.php" method="post">
								<input type="hidden" name="action" value="ok">
								<input type="hidden" name="id" value="<?php echo $idSite; ?>">
								<div class="col-lg-6">
									<div class="form-group<?php if($error['groupe'] === TRUE or $error['groupe'] === TRUE) { ?> has-error<?php } ?>">
									<?php if(isset($gSettings['options']['groupes']['groupe']) === TRUE) { ?>
										<label>Groupe</label>
										<select class="form-control" name="groupe">
											<option value="">&nbsp;</option>
										<?php foreach($gSettings['options']['groupes']['groupe'] as $key => $eGroupe) { ?>
											<option value="<?php echo $eGroupe['id']; ?>"<?php if($eGroupe['id'] == $eSite['groupe']) { ?> selected="selected"<?php } ?>><?php echo $eGroupe['name']; ?></option>
										<?php } ?>
										</select>
										<label>Ou un nouveau groupe</label>
									<?php } else { ?>
										<label>Groupe</label>
									<?php } ?>
										<input class="form-control" name="groupe-new">
										<p class="help-block">Par exemple : personnel, client 1, blogs tests...</p>
									</div>
									
									<div class="form-group<?php if($error['name'] === TRUE) { ?> has-error<?php } ?>">
										<label>Nom du blog</label>
										<input class="form-control" name="name" value="<?php echo $eSite['name']; ?>">
										<p class="help-block">Par exemple : Mon blog de jeux</p>
									</div>
									
									<div class="form-group<?php if($error['url'] === TRUE) { ?> has-error<?php } ?>">
										<label>Adresse du blog</label>
										<input class="form-control" name="url" value="<?php echo $eSite['url']; ?>">
										<p class="help-block">Par exemple : http://www.mon-blog.com</p>
									</div>
								</div>
								
								<div class="col-lg-6">
									<div class="form-group">
										<label>Extension Prélude Version - URL</label>
										<input class="form-control" name="version-url" value="<?php echo $eSite['version_url']; ?>">
										<p class="help-block">L'URL indiquée dans les paramètres de l'extension "Prélude Version" ou vide si l'extension n'est pas installée.</p>
										
										<label>Extension Prélude Version - Mot de passe</label>
										<input class="form-control" name="version-pass" value="<?php echo $eSite['version_pass']; ?>">
										<p class="help-block">Le mot de passe indiquée dans les paramètres de l'extension "Prélude Version" ou vide si l'extension n'est pas installée.</p>
										
										<?php if($idSite != '') { 	// modification ?>
										<button type="submit" class="btn btn-success btn-lg btn-block">Modifier ce blog</button>
										
										<?php } else { 				// ajout ?>
										<button type="submit" class="btn btn-success btn-lg btn-block">Ajouter ce blog</button>
										<?php } ?>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include 'includes/footer.inc.php'; ?>
