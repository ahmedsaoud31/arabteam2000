<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style>
		th{
			text-align:left;
		}
		.error {
			border: 1px #F33 solid;
			background-color: #FFEBEB;
			color:#F33; 
			padding:10px;
			margin: 0 0 5px 0;
		}
		.ok {
			border: 1px #0F0 solid;
			background-color: #DBFFDB;
			color:#3A3; 
			padding:10px; 
			margin: 0 0 5px 0;
		}
	</style>
</head>
<body>
	<?php
		$databaseName = 'myDB';
		$blockName = 'users';
		require_once('PHPDB.php');
		$obj = new PHPDB();
		
		echo "<h3>Creating $databaseName database ...</h3>";
		if($obj->createDB('myDB'))
		{
			echo "<div class='ok'>Database $databaseName create Done !</div>";
		}
		else
		{
			echo '<div class="error">'.$obj->getError().'</div>';
		}
		
		echo "<h3>Selectiong $databaseName database ...</h3>";
		if(($myDB = $obj->selectDB('myDB')) !== false)
		{
			echo "<div class='ok'>Selectiong $databaseName database Done !</div>";
		}
		else
		{
			echo '<div class="error">'.$myDB->getError().'</div>';
		}
		
		echo "<h3>Creating $blockName block ...</h3>";
		if($myDB->createBlock($blockName))
		{
			echo "<div class='ok'>Block $blockName create Done !</div>";
		}
		else
		{
			echo '<div class="error">'.$myDB->getError().'</div>';
		}
		
		echo "<h3>Selectiong $blockName block ...</h3>";
		if(($myBlock = $myDB->selectBlock($blockName)) !== false)
		{
			echo "<div class='ok'>Selectiong $blockName block Done !</div>";
		}
		else
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
		}
		
		echo "<h3>Add this data into {$blockName} block ...</h3>";
		?>
		<table>
			<tr>
				<th>Username</th><th>Password</th><th>E-mail</th><th>Phone</th>
			</tr>
			<tr>
				<td>ahmedsaoud31</td><td>123456</td><td>ahmedsaoud31@gmail.com</td><th>201148024524</td>
			</tr>
			<tr>
				<td>mohammed</td><td>000000</td><td>mohammed@gmail.com</td><th>201100000000</td>
			</tr>
			<tr>
				<td>mostafa</td><td>55555</td><td>mostafa@yahoo.com</td><th>201111111111</td>
			</tr>
			<tr>
				<td>kamal</td><td>666666</td><td>kamal@hotmail.com</td><th>2011445452222</td>
			</tr>
		</table>
		<?php
		// save data
		$data = array(	array('username'=>'ahmedsaoud31','password'=> '123456','email'=>'ahmedsaoud31@gmail.com','phone'=>'201148024524'),
						array('username'=>'mohammed','password'=> '000000','email'=>'mohammed@gmail.com','phone'=>'201100000000'),
						array('username'=>'mostafa','password'=> '55555','email'=>'mostafa@yahoo.com','phone'=>'201111111111'),
						array('username'=>'kamal','password'=> '666666','email'=>'kamal@hotmail.com','phone'=>'2011445452222')
						);
		if($myBlock->putAll($data))
		{
			echo "<div class='ok'>Data inserting Done !</div>";
		}
		else
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
		}
		
		// --------------------------------------------------------------------------------------------
		
		echo "<h3>Selectiong All Data from $blockName block ...</h3>";
		if(($temp = $myBlock->getAll()) !== false)
		{
			
			?>
			<table>
				<tr>
					<th>Username</th><th>Password</th><th>E-mail</th><th>Phone</th>
				</tr>
			<?php
			foreach($temp as $value)
			{
				?>
				<tr>
					<td><?php echo $value['username'];?></td><td><?php echo $value['password'];?></td><td><?php echo $value['email'];?></td><th><?php echo $value['phone'];?></td>
				</tr>
				<?php
			}
			?>
			</table>
			<?php
			echo "<div class='ok'>Get All Data Done !</div>";
		}
		else
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
		}
		
		// --------------------------------------------------------------------------------------------
		
		echo "<h3>Selecting Data from $blockName block Where username=ahmedsaoud31 ...</h3>";
		if(($temp = $myBlock->getAll(array('username =='=>'ahmedsaoud31'))) !== false)
		{
			if($temp != null)
			{
				?>
				<table>
					<tr>
						<th>Username</th><th>Password</th><th>E-mail</th><th>Phone</th>
					</tr>
				<?php
				foreach($temp as $value)
				{
					?>
					<tr>
						<td><?php echo $value['username'];?></td><td><?php echo $value['password'];?></td><td><?php echo $value['email'];?></td><th><?php echo $value['phone'];?></td>
					</tr>
					<?php
				}
				?>
				</table>
				<?php
				echo "<div class='ok'>Get Data Where username=ahmedsaoud31 Done !</div>";
			}
			else
			{
				echo '<div class="error">No Data to show it !</div>';
			}
		}
		else
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
		}
		
		// --------------------------------------------------------------------------------------------
		
		echo "<h3>Selecting Data from $blockName block Where username=ahmedsaoud31 or username=mohammed ...</h3>";
		//if(($temp = $myBlock->getAll(['or'=>[['username =='=>'ahmedsaoud31'],['username =='=>'mohammed']]])) !== false)
		if(($temp = $myBlock->getAll("{'or':[{'username ==':'ahmedsaoud31'},{'username ==':'mohammed'}]}")) !== false)
		//if(($temp = $myBlock->getAll(array('or'=>array(array('username =='=>'ahmedsaoud31'),array('username =='=>'mohammed'))))) !== false)
		{
			if($temp != null)
			{
				?>
				<table>
					<tr>
						<th>Username</th><th>Password</th><th>E-mail</th><th>Phone</th>
					</tr>
				<?php
				foreach($temp as $value)
				{
					?>
					<tr>
						<td><?php echo $value['username'];?></td><td><?php echo $value['password'];?></td><td><?php echo $value['email'];?></td><th><?php echo $value['phone'];?></td>
					</tr>
					<?php
				}
				?>
				</table>
				<?php
				echo "<div class='ok'>Get Data Where username=ahmedsaoud31 or username=mohammed  Done !</div>";
			}
			else
			{
				echo '<div class="error">No Data to show it !</div>';
			}
		}
		else
		{
			echo '<div class="error">'.$myBlock->getError().'</div>';
		}
		// --------------------------------------------------------------------------------------------
		
		//echo '<h3>Save Data Done !</h3>';
		// get data
		//$result = $myBlock->get();
		$count = 0;
		//$myBlock->getWhere("{'name ==':'Ahmed'}")
		//echo '<h3>Set Data!<br>----------------------------<br></h3>';
		//$myBlock->set("{'email':'rrr@mmm.com','name':'boo'}");
		//$myBlock->delete("{'name ==':'Ahmed'}");
		/*echo '<h3>Get Data!<br>----------------------------<br></h3>';
		foreach($myBlock->get(100,"{'or':[{'emails ==':'Ahmed'},{'name !=':'Ahmed'}]}") as $value)
		{
			$count++;
			echo "{$count}<br>";
			echo "Name: {$value['name']}<br>";
			echo "Password: {$value['password']}<br>";
			echo "E-mail: {$value['email']}<br>";
			echo '<h3>-----------------------------------------</h3>';
		}*/
	/*$arr1 = array('a','b','c','d','e','f','g','h','i','j','k','l');
	$arr1 = array('a','b','c','d','e','f','g','h','i','j','k','l');
	$output1 = array_slice($arr1, 0, 3);
	$output2 = array_slice($arr1, 100);
	$output2 = array_chunk($output2, 100);
	if($output2 == null)
		echo 'yes';
	for($i=0;$i<count($output2);$i++)
	foreach($output2[$i] as $value)
	{
		echo "<h1>$value</h1>";
	}
	$deciPart = 120%100;
	$intPart = (int)(120/100);
	$output = array_slice($arr1, 0, $deciPart);
	echo (int)(120/100);*/
	// تم بحمد الله
	?>
</body>
</html>