<?php
require_once('config.php');
if(SESSION_PATH != '')
{
	session_save_path(SESSION_PATH); 
}
require_once('functions.php');
$session_login = session_login();
if(!$session_login)
{
	$out = array('error'=>'يرجى تسجيل الدخول أولاً');
	echo json_encode($out);
	exit();
}
if(isset($_POST['testResult']))
{
	if($_POST['username'] != $_SESSION['username'])
	{
		$out = array('error'=>'بيانات الجلسة غير متطابقة مع البيانات المرسلة');
		echo json_encode($out);
		exit();
	}
	$databaseName = 'myDB1';
	$blockName = 'algorithms';
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
	$algorithm_id = (int)$_POST['algorithm_id'];
	$username = $_SESSION['username'];
	$result = $myBlock->getAll(array('PHPDBID =='=>$algorithm_id));
	if(count($result) == 1)
	{
		if(($answers = $myDB->selectBlock('answers')) === false)
		{
			$out = array('error'=>$myDB->getError());
			echo json_encode($out);
			exit();
		}
		$result2 = $answers->getAll(array('and'=>array('user_name =='=>$username,'algorithm_id =='=>$algorithm_id)));
		if(count($result2) > 0)
		{
			if($result[0]['result'] === $_POST['testResult'])
			{
				$out = array('error'=>false,'testResult'=>true);
			}
			else
			{
				$out = array('error'=>false,'testResult'=>false);
			}
			echo json_encode($out);
			exit();
		}
		else
		{
			$out = array('error'=>'لم تقم بإختيار المسابقة بعد والبدء بها');
			echo json_encode($out);
			exit();
		}
	}
	else
	{
		$out = array('error'=>'هذه الخوارزمية غير موجوده');
		echo json_encode($out);
		exit();
	}
}
?>