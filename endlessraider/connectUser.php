<?php 
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
include($phpbb_root_path.'UserEndless.'. $phpEx);

define('IN_LOGIN', true);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('ucp');

$template->assign_var('S_IN_UCP', true);

$module = new p_master();
$default = false;

if(isset($_POST['loginEndless']) && !empty($_POST['loginEndless']) 
	&& isset($_POST['passEndless']) && !empty($_POST['passEndless']))
{
	global $db, $user, $template, $auth, $phpEx, $phpbb_root_path, $config;
	
	$autologin	= (!empty($_POST['autologin'])) ? true : false;
	$viewonline = (!empty($_POST['viewonline'])) ? 0 : 1;
	$admin 		= ($admin) ? 1 : 0;
	
	$result = $auth->login($_POST['loginEndless'], $_POST['passEndless'], $autologin, $viewonline, $admin);
	
	if ($result['status'] == LOGIN_SUCCESS)
	{
		$userEndless = getInfosUserEndless();
	
		if(isset($userEndless) && $userEndless->id != 1) {
			$reponse["statut"] = "success";
			$reponse["userEndless"] = $userEndless;
		} else { 
			$reponse["statut"] = "error";
			$reponse["errorMess"] = "Informations utilisateur non trouvées.";
		} 
	} else {
		$reponse["statut"] = "error";
		$reponse["errorMess"] = "Login ou password incorrect.";
	}
} else {
	$reponse["statut"] = "error";
	$reponse["errorMess"] = "Les champs 'login' et 'password' sont obligatoires.";
}

echo json_encode($reponse);
?>


