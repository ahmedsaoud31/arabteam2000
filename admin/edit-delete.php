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
		$out = array('error'=>'لم يتم تسجيل الدخول بعد');
		echo json_encode($out);
		exit();
	}
	require_once('header.php');
?>
<div class="editUsers">
<?php
	$databaseName = 'myDB1';
	$blockName = 'users';
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
	if(isset($_POST['submitDeleteNo']))
	{
		header('location: users.php');
	}
	else if(isset($_POST['submitChange']) && isset($_POST['password']) && $_POST['password'] != null)
	{
		$myBlock->set(array('password'=>crypt($_POST['password'])),array('username =='=>$_POST['username']));
		echo 'تم تغيير كلمة المرور <br> جاري التحويل ...';
		echo '
			<script>
				setTimeout(function (){
				   window.location.href = "users.php";
				},1000);
			</script>';
	}
	else if(isset($_POST['submitDeleteYes']))
	{
		$myBlock->delete(array('username =='=>$_POST['username']));
		if(($myBlock2 = $myDB->selectBlock('answers')) === false)
		{
			$out = array('error'=>$myBlock->getError());
			echo json_encode($out);
			exit();
		}
		$myBlock2->delete(array('user_name =='=>$_POST['username']));
		echo 'تم حذف العضو <br> جاري التحويل ...';
		echo '
			<script>
				setTimeout(function (){
				   window.location.href = "users.php";
				},1000);
			</script>';
	}
	else if(isset($_POST['edit']))
	{
	?>
		<table>
			<form action="" method="POST">
				<tr>
					<td><?php echo $_POST['username']; ?></td>
				</tr>
				<tr>
					<td><input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" /></td>
				</tr>
				<tr>
					<td><input type="password" name="password" placeholder="كلمة المرور الجديدة"/></td>
				</tr>
				<tr>
					<td><input type="submit" name="submitChange" value="حفظ"/></td>
				</tr>
			</form>
		</table>
	<?php
	}
	else if(isset($_POST['delete']))
	{
	?>
		<table>
			<form action="" method="POST">
				<tr>
					<td><?php echo $_POST['username']; ?></td>
				</tr>
				<tr>
					<td><input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" /></td>
				</tr>
				<tr>
					<td><span>هل تريد حذف هذا المستخدم ؟</span><input type="submit" name="submitDeleteYes" value="نعم"/> <input type="submit" name="submitDeleteNo" value="لا"/></td>
				</tr>
			</form>
		</table>
	<?php
	}
?>
</div>
<?php
	require_once('footer.php');
?>