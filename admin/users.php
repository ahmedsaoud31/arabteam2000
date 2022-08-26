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
	if(isset($_POST['add']))
	{
		if($_POST['newUserName'] != '' && $_POST['newPassword'] != '')
		{
			$databaseName = 'myDB1';
			$blockName = 'users';
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
			}
			$user = $myBlock->getAll(array('username =='=>$_POST['newUserName']));
			if(count($user) > 0)
			{
				$addError = 'هذا المستخدم موجود بالفعل';
			}
			else
			{
				$arr = array('username'=>$_POST['newUserName'],'password'=>crypt($_POST['newPassword']));
				$myBlock->put($arr);
				$addOK = 'تم تسجيل مستخدم جديد';
			}
		}
		else
		{
			$addError = 'يرجى ملئ جميع الحقول';
		}
	}
	else if(isset($_POST['edit']))
	{
		
	}
?>
	<div class="add">
		<form action="" method="POST">
			<input type="text" name="newUserName" placeholder="اسم المستخدم">  <input type="password" name="newPassword" placeholder="كلمة المرور"> <input type="submit" name="add" value="أضف مستخدم جديد">
		</form>
		<div>
			<span class="error"><?php echo isset($addError)?$addError:"";?></span>
			<span class="ok"><?php echo isset($addOK)?$addOK:"";?></span>
		</div>
	</div>
	<table class="users">
			<tr>
				<th>اسم المستخدم</th> <th>تغيير كلمة المرور</th> <th>حذف</th>
			</tr>
				<?php
					$databaseName = 'myDB1';
					$blockName = 'users';
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
					}
					$result = $myBlock->getAll();
					foreach($result as $value)
					{
				?>
				<form action="edit-delete.php" method="POST">
					<tr>
						<td><?php echo $value['username']; ?></td> <td><input type="hidden" value="<?php echo $value['username']; ?>" name="username" /><input type="submit" value="تعديل" name="edit" /></td> <td><input type="submit" value="حذف" name="delete" /></td>
					</tr>
				</form>
				<?php
					}
				?>
	</table>
<?php
	require_once('footer.php');
?>