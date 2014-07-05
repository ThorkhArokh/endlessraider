<?php 
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path.'UserEndless.'. $phpEx);

	$userEndless = getInfosUserEndless();
	
	if(isset($userEndless) && $userEndless->id != 1) 
	{
		?>
		<div class="connectBox connected">
			<figure>
				<a href='<?php $userEndless->showLienProfil() ?>'><?php $userEndless->showAvatar("forumAvatar"); ?></a>
			</figure>
			<span class="userLogin">Bonjour <?php $userEndless->showNom() ?> !</span>
			<ul>
				<li><a target="_blank" title="Nombre de messages" href="http://endlessfr.com/forum/ucp.php?i=pm&folder=inbox">> Vous avez <span class="nbMp"><?php $userEndless->showNbrMp() ?></span> nouveaux messages</a></li>
				<li><a target="_blank" title="Se rendre sur le forum" href="http://forum.endlessfr.com">> Se rendre sur le forum</a></li>
				<li><a title="Déconnexion" href="http://endlessfr.com/forum/ucp.php?mode=logout&sid=<?php echo getSID(); ?>" >> D&eacute;connexion</a></li>
			</ul>
		</div>
	<?php } else { ?>
		 <div class="connectBox">
			<a href="#" id="loginLink" class="login">Se connecter</a>
			<div id="zoneSaisieConnexion" style="display: none;">
			<form id='formConnectEndless'>
				<label for="loginEndless">Login : </label><input type="text" id="loginEndless" name="loginEndless" />
				<label for="passEndless">Password : </label><input type="password" id="passEndless" name="passEndless" />
				<input type="submit" value="Se connecter" id="connectEndless" />
				<div id="encarConnexionErreur"></div>
			</form>
			</div>
			<a href="http://endlessfr.com/forum/ucp.php?mode=register" target="_blank" class="register">S'inscrire</a>
		</div>
	<?php } ?>