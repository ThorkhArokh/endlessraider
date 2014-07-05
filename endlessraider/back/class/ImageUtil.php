<?php
// Définie le répertoire d'upload des images pour les différents objets (jeu, event...)
define('REP_IMG_UPLOAD', 'front/images/upload/');
define('REP_IMG_UPLOAD_PREFIX', $_SERVER["DOCUMENT_ROOT"].'/endlessraider/');

// Fonction qui supprime une image en fonction de son chemin+nom
function deleteImage($pathImage) {
	return unlink(REP_IMG_UPLOAD_PREFIX.$pathImage);
}

?>