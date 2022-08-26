<?php
	require_once('../config.php');
	if(SESSION_PATH != '')
	{
		session_save_path(SESSION_PATH); 
	}
	require_once('functions.php');
	$admin_login = session_admin_login();
	if(!$admin_login)
	{
		header('location: login.php');
		exit();
	}
	require_once('header.php');
?>

<?php
	require_once('footer.php');
?>