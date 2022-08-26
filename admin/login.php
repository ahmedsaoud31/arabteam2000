<?php
/*header('Content-Type: text/html; charset=utf-8');
echo "<h2>عذراً عاود المحاولة لاحقاً , يجري الآن عمل تحديثات للموقع , شكراً لزيارتكم</h2>";
exit();*/
require_once('../config.php');
if(SESSION_PATH != '')
{
	session_save_path(SESSION_PATH); 
}
if(!isset($_SESSION)) session_start();
if(isset($_POST['submit']))
{
	if($_POST['admin'] != '' && $_POST['admin_password'] != '')
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
		$admin = $myBlock->getAll(array('username =='=>$_POST['admin']));
		if(count($admin) == 1 && $admin[0]['password'] == crypt($_POST['admin_password'], $admin[0]['password']))
		{
			if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
			if(isset($_SESSION['admin_password'])) unset($_SESSION['admin_password']);
			$_SESSION['admin'] = $admin[0]['username'];
			$_SESSION['admin_password'] = $admin[0]['password'];
			header('location: index.php');
		}
		else
		{
			$error = 'تسجيل الدخول لم يتم حاول مجدداً';
		}
	}
	else
	{
		$error = 'يرجى ملئ جميع الحقول';
	}
}
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
	<meta charset="utf-8">
	<script src="../js/jquery-1.9.1.min.js"></script>
	<link rel="stylesheet" href="css/style.css?id=<?php echo time(); ?>" type="text/css" />
	<script>
		$(function(){
			
		});
	</script>
</head>
<body>
	<header>
		
	</header>
	<article>
		<div>
			<form action="" method="POST">
				<table>
					<tr>
						<td><input type="text" name="admin" /></td>
					</tr>
					<tr>
						<td><input type="password" name="admin_password" /></td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="تسجيل الدخول" /></td>
					</tr>
					<tr>
						<td style="color:#F00;"><?php echo isset($error)?$error:'';?></td>
					</tr>
				</table>
			</form>
		</div>
	</article>
	<footer>
		
	</footer>
</body>
</html>