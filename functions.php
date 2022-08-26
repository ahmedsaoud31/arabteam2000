<?php
function session_login()
{
	require_once('config.php');
	if(SESSION_PATH != '')
	{
		session_save_path(SESSION_PATH); 
	}
	if(!isset($_SESSION)) session_start();
	if(isset($_SESSION['username']) && isset($_SESSION['password']))
	{
		$databaseName = 'myDB1';
		$blockName = 'users';
		require_once('PHPDB/PHPDB.php');
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
		$user = $myBlock->getAll(array('username =='=>$_SESSION['username']));
		if(count($user) == 1 && $user[0]['password'] == $_SESSION['password'])
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