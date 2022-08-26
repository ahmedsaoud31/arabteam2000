<?php
require_once('../config.php');
if(SESSION_PATH != '')
{
	session_save_path(SESSION_PATH); 
}
if(!isset($_SESSION)) session_start();
if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
if(isset($_SESSION['admin_password'])) unset($_SESSION['admin_password']);
header('location: login.php');
?>