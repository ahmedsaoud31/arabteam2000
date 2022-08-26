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
?>
<div id="algorithm">
	<div>
		<a href="?action=add">أضف خوارزمية جديدة</a>
	</div>
	<?php
	if(isset($_GET['action']) && $_GET['action'] == 'add')
	{
		if(isset($_POST['addOK']))
		{
			if($_POST['title'] != '' && $_POST['algorithm'] != '' && $_POST['score'] != '')
			{
				$databaseName = 'myDB1';
				$blockName = 'algorithms';
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
				$myBlock->put(array('title'=>$_POST['title'],'score'=>$_POST['score'],'result'=>$_POST['result'],'algorithm'=>$_POST['algorithm'],'active'=>$_POST['active']));
				header('location: algorithm.php');
			}
			else
			{
				$addError = 'يرجى ملئ جميع الحقول';
			}
		}
	?>
	<div>
		<form action="" method="POST">
			<input type="text" name="title" placeholder="العنوان"/><br>
			<input type="text" name="score" placeholder="الدرجة"/><br>
			<input type="text" name="result" dir="ltr" placeholder="الناتج"/><br>
			<div id="tinymceDiv" style="text-align:left; width: 750px; margin: auto; min-height: 100px;" dir="ltr">
				<textarea name="algorithm" id="textareaContent" style="width:100%" placeholder="الخوارزمية" dir="ltr"></textarea>
			</div>
			<input type="radio" name="active" value="1" checked="checked"><span> متاحة </span>
			<input type="radio" name="active" value="0"><span> غير متاحة </span><br>
			<input type="submit" name="addOK" value="أضف خوارزمية جديدة">
		</form>
		<div class="error"><?php echo isset($addError)?$addError:'';?></div>
	</div>
	<?php
	}
	else if(isset($_POST['delete']) && isset($_POST['algorithm_id']))
	{
		$algorithm_id = (int)$_POST['algorithm_id'];
		$databaseName = 'myDB1';
		$blockName = 'algorithms';
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
		$myBlock->delete(array('PHPDBID =='=>$algorithm_id));
		if(($myBlock2 = $myDB->selectBlock('answers')) === false)
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
			exit();
		}
		$myBlock2->delete(array('algorithm_id =='=>$algorithm_id));
		echo $algorithm_id;
		echo 'تم الحذف';
	}
	else if(isset($_POST['edit']) && isset($_POST['algorithm_id']))
	{
		$algorithm_id = (int)$_POST['algorithm_id'];
		$databaseName = 'myDB1';
		$blockName = 'algorithms';
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
		$result = $myBlock->getAll(array('PHPDBID =='=>$algorithm_id));
		if(count($result) != 1)
		{
			echo '<div class="error">لم يتم إيجاد المسابقة لتعديلها</div>';
			exit();
		}
		if($result[0]['active'] == "1")
		{
			$active = true;
		}
		else
		{
			$active = false;
		}
	?>
		<form action="" method="POST">
			<input type="text" name="title" placeholder="العنوان" value="<?php echo $result[0]['title'];?>"/><br>
			<input type="text" name="score" placeholder="الدرجة" value="<?php echo $result[0]['score'];?>"/><br>
			<input type="text" name="result" placeholder="الناتج" dir="ltr" value="<?php echo $result[0]['result'];?>"/><br>
			<div id="tinymceDiv" style="text-align:left; width: 750px; margin: auto; min-height: 100px;" dir="ltr">
				<textarea name="algorithm" id="textareaContent" style="width:100%" placeholder="الخوارزمية" dir="ltr"><?php echo $result[0]['algorithm'];?></textarea>
			</div>
			<input type="radio" name="active" value="1" <?php echo $active?'checked="checked"':''; ?>><span> متاحة </span>
			<input type="radio" name="active" value="0" <?php echo $active?'':'checked="checked"'; ?>><span> غير متاحة </span><br>
			<input type="hidden" name="algorithm_id" value="<?php echo $algorithm_id; ?>">
			<input type="submit" name="editOK" value="تعديل الخوارزمية">
		</form>
	<?php
	}
	else if(isset($_POST['editOK']) && isset($_POST['algorithm_id']))
	{
		$algorithm_id = (int)$_POST['algorithm_id'];
		$databaseName = 'myDB1';
		$blockName = 'algorithms';
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
		$myBlock->set(array('title'=>$_POST['title'],'score'=>$_POST['score'],'result'=>$_POST['result'],'algorithm'=>$_POST['algorithm'],'active'=>$_POST['active']),array('PHPDBID =='=>$algorithm_id));
		echo 'تم التعديل';
	}
	else
	{
	?>
	<table class="algorithms">
		<tr>
			<th>اسم الخوارزمية</th> <th>الحالة</th> <th>دخول</th>
		</tr>
			<?php
				$databaseName = 'myDB1';
				$blockName = 'algorithms';
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
			<form action="" method="POST">
				<tr>
					<td><?php echo $value['title']; ?></td> <td><input type="hidden" value="<?php echo $value['PHPDBID']; ?>" name="algorithm_id" /><input type="submit" value="تعديل" name="edit" /></td> <td><input type="submit" value="حذف" name="delete" /></td>
				</tr>
			</form>
			<?php
				}
			?>
	</table>
	<?php
	}
	?>
</div>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
var scriptLoader = new tinymce.dom.ScriptLoader();
scriptLoader.add('js/jquery-1.9.1.min.js');
tinymce.init({
	selector: "#textareaContent",
	directionality: 'rtl',
	theme: "modern",
	plugins: [
		"table preview image link media insertdatetime snippet code codeltr directionality"
	],
	toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image snippet | rtl ltr codeltr",
	toolbar2: "print preview media | forecolor backcolor emoticons",
	templates: [
		{title: 'Test template 1', content: 'Test 1'},
		{title: 'Test template 2', content: 'Test 2'}
	]
});
</script>
<?php
	require_once('footer.php');
?>