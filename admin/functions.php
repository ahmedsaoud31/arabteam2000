<?php
function session_admin_login()
{
	require_once('../config.php');
	if(SESSION_PATH != '')
	{
		session_save_path(SESSION_PATH); 
	}
	if(!isset($_SESSION)) session_start();
	if(isset($_SESSION['admin']) && isset($_SESSION['admin_password']))
	{
		$databaseName = 'myDB1';
		$blockName = 'admin';
		require_once('../PHPDB/PHPDB.php');
		$obj = new PHPDB();
		if(($myDB = $obj->selectDB($databaseName)) === false)
		{
			echo '<div class="error">'.$obj->getError().'</div>';
			exit();
		}
		if(($myBlock = $myDB->selectBlock($blockName)) === false)
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
			exit();
		}
		$admin = $myBlock->getAll(array('username =='=>$_SESSION['admin']));
		if(count($admin) == 1 && $admin[0]['password'] == $_SESSION['admin_password'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
?>