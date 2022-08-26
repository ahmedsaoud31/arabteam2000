<?php
require_once('config.php');
if(SESSION_PATH != '')
{
	session_save_path(SESSION_PATH); 
}
if(!isset($_SESSION)) session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
	if($_POST['username'] != '' && $_POST['password'] != '')
	{
		$databaseName = 'myDB1';
		$blockName = 'users';
		require_once('PHPDB/PHPDB.php');
		$obj = new PHPDB();
		if(($myDB = $obj->selectDB($databaseName)) === false)
		{
			$out = array('error'=>$obj->getError());
			echo json_encode($out);
			exit();
		}
		if(($myBlock = $myDB->selectBlock($blockName)) === false)
		{
			$out = array('error'=>$myBlock->getError());
			echo json_encode($out);
			exit();
		}
		$user = $myBlock->getAll(array('username =='=>$_POST['username']));
		if(count($user) == 1 && $user[0]['password'] == crypt($_POST['password'], $user[0]['password']))
		{
			$_SESSION['username'] = $user[0]['username'];
			$_SESSION['password'] = $user[0]['password'];
			$out = array('error'=>false);
			echo json_encode($out);
		}
		else
		{
			$out = array('error'=>'تسجيل الدخول لم يتم حاول مجدداً');
			echo json_encode($out);
		}
	}
	else
	{
		$out = array('error'=>'يرجى ملئ جميع الحقول');
		echo json_encode($out);
	}
}
else
{
	$out = array('error'=>'لم يتم إرسال حقل اسم المستخدم وكلمة المرور');
	echo json_encode($out);
}
?>