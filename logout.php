<?php
require_once('config.php');
if(SESSION_PATH != '')
{
	session_save_path(SESSION_PATH); 
}
if(!isset($_SESSION)) session_start();
if(isset($_SESSION['username'])) unset($_SESSION['username']);
if(isset($_SESSION['password'])) unset($_SESSION['password']);
header('location: index.php');
?>