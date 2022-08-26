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
	$out = array('error'=>'يرجى تسجيل الدخول أولاً');
	echo json_encode($out);
	exit();
}
if(isset($_POST['answer_id']) && isset($_POST['evaluation']))
{
	$_POST['answer_id'] = (int)$_POST['answer_id'];
	$_POST['evaluation'] = (int)$_POST['evaluation'];
	$databaseName = 'myDB1';
	$blockName = 'answers';
	require_once('../PHPDB/PHPDB.php');
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
	$result = $myBlock->getAll(array('PHPDBID =='=>$_POST['answer_id']));
	if(count($result) == 1)
	{
		if($result[0]['answer_submit'])
		{
			$myBlock->set(array('evaluation'=>$_POST['evaluation']),array('PHPDBID =='=>$_POST['answer_id']));
			$out = array('error'=>false);
			echo json_encode($out);
			exit();
		}
		else
		{
			$out = array('error'=>'هذا المستخدم لم يقم بتقديم حل لهذه المسابقة بعد');
			echo json_encode($out);
			exit();
		}
	}
	else
	{
		$out = array('error'=>'لا توجد إجابة لتقييمها');
		echo json_encode($out);
		exit();
	}
}
else
{
	$out = array('error'=>'البيانات المرسلة غير كاملة');
	echo json_encode($out);
	exit();
}
?>