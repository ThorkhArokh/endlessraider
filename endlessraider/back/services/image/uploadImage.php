<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/ImageUtil.php');

// Constantes
define('TARGET', REP_IMG_UPLOAD_PREFIX.REP_IMG_UPLOAD); // Repertoire cible
define('MAX_SIZE', 1000000); // Taille max en octets du fichier
define('WIDTH_MAX', 900); // Largeur max de l'image en pixels
define('HEIGHT_MAX', 900); // Hauteur max de l'image en pixels
// Tableaux de donnees
$tabExt = array('jpg','gif','png','jpeg'); // Extensions autorisees
$infosImg = array();
// Variables
$extension = '';
$message = '';
$nomImage = '';

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

/************************************************************
* Creation du repertoire cible si inexistant
*************************************************************/
if( !is_dir(TARGET) ) {
	if( !mkdir(TARGET, 0755) ) {
		exit('Erreur : le répertoire cible ne peut-être créé ! Vérifiez que vous diposiez des droits suffisants pour le faire ou créez le manuellement !');
	}
}
/************************************************************
* Script d'upload
*************************************************************/
if(!empty($_POST)) {
	// On verifie si le champ est rempli
	if( !empty($_FILES['file']['name']) ) {
		// Recuperation de l'extension du fichier
		$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		// On verifie l'extension du fichier
		if(in_array(strtolower($extension),$tabExt)) {
			// On recupere les dimensions du fichier
			$infosImg = getimagesize($_FILES['file']['tmp_name']);
			// On verifie le type de l'image
			if($infosImg[2] >= 1 && $infosImg[2] <= 14) {
				// On verifie les dimensions et taille de l'image
				if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) 
					&& (filesize($_FILES['file']['tmp_name']) <= MAX_SIZE)) {
					// Parcours du tableau d'erreurs
					if(isset($_FILES['file']['error'])
						&& UPLOAD_ERR_OK === $_FILES['file']['error']) {
						// On renomme le fichier
						$nomImage = md5(uniqid()) .'.'. $extension;
						// Si c'est OK, on teste l'upload
						if(move_uploaded_file($_FILES['file']['tmp_name'], TARGET.$nomImage)) {
							$resultat['success'] = true;
							$resultat['message'] = 'Upload réussi.';
							$resultat['pathImage'] = REP_IMG_UPLOAD.$nomImage;
						} else {
							$resultat['success'] = false;
							// Sinon on affiche une erreur systeme
							$resultat['message'] = 'Problème lors de l\'upload.';
						}
					} else {
						$resultat['success'] = false;
						$resultat['message'] = 'Une erreur interne a empêché l\'uplaod de l\'image';
					}
				} else {
					$resultat['success'] = false;
					// Sinon erreur sur les dimensions et taille de l'image
					$resultat['message'] = 'Erreur dans les dimensions de l\'image (taille maximum : '.(MAX_SIZE/1000).'ko, dimension maximum : '.WIDTH_MAX.'px/'.HEIGHT_MAX.'px)';
				}
			} else {
				$resultat['success'] = false;
				// Sinon erreur sur le type de l'image
				$resultat['message'] = 'Le fichier à uploader n\'est pas une image !';
			}
		} else {
			$resultat['success'] = false;
			// Sinon on affiche une erreur pour l'extension
			$resultat['message'] = 'L\'extension du fichier est incorrecte !';
		}
	} else {
		$resultat['success'] = false;
		// Sinon on affiche une erreur pour le champ vide
		$resultat['message'] = 'Veuillez remplir le formulaire svp !';
	}
}	

echo json_encode($resultat);

?>