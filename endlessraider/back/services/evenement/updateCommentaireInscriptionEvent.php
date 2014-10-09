<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

session_start();

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

if(isset($_GET['idEvent']) && isset($_GET['commentaire'])) {
	if(isset($_SESSION['userConnect'])) {
		try {
			// On essaie de mettre à jour le commentaire
			if(updateCommentaireInscription($_GET['idEvent'], $_SESSION['userConnect']->id, $_GET['commentaire'])) {
				$resultat['success'] = true;
				$resultat['message'] = "Modification enregistrée.";
			} else {
				$resultat['success'] = false;
				$resultat['message'] = "erreur lors de l'enregistrement du commentaire.";
			}
		} catch (Exception $e) {
			$resultat['success'] = false;
			$resultat['message'] = $e->getMessage();
		}
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "Perte de session. Veuillez vous reconnecter.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucune mise à jour possible.";
}

echo json_encode($resultat);

?>