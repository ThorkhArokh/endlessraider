<?php
//$phpbb_admin_path = $_SERVER["DOCUMENT_ROOT"]."/forum/adm/";
//$phpbb_root_path = $_SERVER["DOCUMENT_ROOT"]."/forum/";
//$phpEx = substr(strrchr(__FILE__, '.'), 1);

// Start session management
$user->session_begin();

function getSID()
{
	global $user;

	return $user->session_id;
}

class UserEndless
{
	// Identifiant
	var $id;
	// Login
	var $nom;
	// Avatar
	var $avatar;
	var $avatarType;
	var $avatarWidth;
	var $avatarHeight;
	// Nombre de nouveau messages privés non lu
	var $nbrMp;
	// lien vers le profil du forum
	var $lienProfil;

	function UserEndless($idIn, $nomIn, $avatarIn, $nbrMpIn, $avatarTypeIn, $avatarWidthIn, $avatarHeightIn)
	{
		global $phpbb_root_path;
	
		$this->id = $idIn;
		$this->nom = $nomIn;
		$this->avatar = $avatarIn;
		$this->nbrMp = $nbrMpIn;
		$this->avatarType = $avatarTypeIn;
		$this->avatarWidth = $avatarWidthIn;
		$this->avatarHeight = $avatarHeightIn;
	
		$this->lienProfil = "http://www.endlessfr.com/forum/memberlist.php?mode=viewprofile&u=".$idIn;
	}
	
	function show() 
	{
		global $phpbb_root_path;
	
		echo "Id : ".$this->id;
		echo "<br/>Nom : ".$this->nom;
		echo "<br/>Avatar : <img src='http://www.endlessfr.com/forum/download/file.php?avatar=".$this->avatar."' >";
		echo "<br/>MP : ".$this->nbrMp;
		echo "<br/><a href='".$this->lienProfil."' >Profil</a>";
	}
	
	function showNom() 
	{
		echo $this->nom;
	}
	
	function showNbrMp() 
	{
		echo $this->nbrMp;
	}
	
	function showLienProfil() 
	{
		echo $this->lienProfil;
	}
	
	function showAvatar($class)
	{
		global $phpbb_root_path, $phpEx, $phpbb_admin_path;
		include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
	
		if(isset($this->avatar) && $this->avatar != '') {
			echo "<img class='".$class."' src='http://www.endlessfr.com/forum/download/file.php?avatar=".$this->avatar."' >";
		} else {
			echo '<img src="http://www.endlessfr.com/forum/adm/images/no_avatar.gif" alt="" />';
		}
	}
}

// Méthode qui récupère les informations d'un utilisateur via son identifiant
function getInfosUserEndless() {
	global $user, $db, $phpbb_root_path, $phpEx;
	
	$user_id = $user->data['user_id'];
	
	$reqUser = 'SELECT u.user_id as id, u.username as nom, u.user_avatar as avatar, u.user_new_privmsg as nbrMp, u.user_avatar_type as avatarType, u.user_avatar_width as avatarWidth, u.user_avatar_height as avatarHeight FROM '.USERS_TABLE.' u where u.user_id = '.$user_id;
	$resUser = $db->sql_query($reqUser);
	
	if ($row = $db->sql_fetchrow($resUser)) 
	{
		$userConnect = new UserEndless($row['id'], $row['nom'], $row['avatar'], $row['nbrMp'], $row['avatarType'], $row['avatarWidth'], $row['avatarHeight']);
	} else {
		unset($userConnect);
	}
	$db->sql_freeresult($resUser);
	
	return $userConnect;
}

?>