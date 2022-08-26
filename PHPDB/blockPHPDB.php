<?php
/**
 * @author		Ahmed Aboelsaoud Ahmed <ahmedsaoud31@gmail.com>
 * @copyright	© 2013
 * @license		GPL
 * @license		http://opensource.org/licenses/gpl-license.php
 * @link		https://github.com/ahmedsaoud31/PHPDB
 * @link		http://phpdb.goo2pro.com
 * @version		0.0.1
 */

// include config file
require_once(__DIR__.'/CF.php');

class blockPHPDB extends CF
{
	// @var to save database name
	private $dbName;
	
	// @var to save database path
	private $dbPath;
	
	// @var to save block name
	private $blockName;
	
	// @var to save block path
	private $blockPath;
	
	// @var to save units path
	private $unitsPath;
	
	// @var to save class error
	private $error = null;
	
	/**
	* public constructor
	* @param String $blockName  block name
	* @param String $dbName database name
	*/
	public function __construct($blockName,$dbName)
	{
		parent::__construct();
		$this->dbName = $dbName;
		$this->dbPath = "{$this->dbsPath}/{$dbName}";
		$this->blockName = $blockName;
		$this->blockPath = "{$this->dbPath}/{$blockName}";
		$this->unitsPath = "{$this->blockPath}/{$this->blockDataName}";
	}
	
	/**
	* public function to insert data
	* @param array or JSON String $input input data
	* @return true if sucsses
	* @return false if failure
	*/
	public function put($input)
	{
		try
		{
			if(!is_array($input))
			{
				if(is_string($input))
				{
					$input = json_decode(str_replace("'",'"',$input),true);
				}
				else
				{
					throw new Exception("Data must be array or JSON string !");
					return false;
				}
			}
			$settings = json_decode(file_get_contents("{$this->blockPath}/{$this->blockDataFile}"), true);
			++$settings['lastID'];
			$input['PHPDBID'] = $settings['lastID'];
			file_put_contents("{$this->blockPath}/{$this->blockDataFile}",json_encode($settings));
			$units = scandir("{$this->unitsPath}");
			$lastUnit = $units[count($units)-1];
			if(is_dir($lastUnit))
			{
				$lastUnit = '0000000001.phpdb';
				$input2[] = $input;
				file_put_contents("{$this->unitsPath}/{$lastUnit}",json_encode($input2));
				return true;
			}
			$lastUnitContent = json_decode(file_get_contents("{$this->unitsPath}/{$lastUnit}"), true);
			if(count($lastUnitContent) < $settings['unitItemsNumber'])
			{
				$lastUnitContent[] = $input;
				file_put_contents("{$this->unitsPath}/{$lastUnit}",json_encode($lastUnitContent));
			}
			else
			{
				$unitNumber = explode('.',$lastUnit);
				$unitNumber = (int)$unitNumber[0];
				$unitNumber++;
				$unitNumber = str_pad($unitNumber, 10, "0", STR_PAD_LEFT);
				$input2[] = $input;
				file_put_contents("{$this->unitsPath}/{$unitNumber}.phpdb",json_encode($input2));
			}
			return true;
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* public function to insert array of data
	* @param Array or JAON String $input input data
	* @param Boolean $PHPDBID to put id or not
	* @return true if sucsses
	* @return  false if failure
	*/
	public function putAll($input, $PHPDBID = true)
	{
		try
		{
			if(!is_array($input))
			{
				if(is_string($input))
				{
					$input = json_decode(str_replace("'",'"',$input),true);
				}
				else
				{
					throw new Exception("Data must be array or JSON string !");
					return false;
				}
			}
			$settings = json_decode(file_get_contents("{$this->blockPath}/{$this->blockDataFile}"),true);
			if($PHPDBID === true)
			{
				for($i=0;$i<($temp = count($input));++$i)
				{
					++$settings['lastID'];
					$input[$i]['PHPDBID'] = $settings['lastID'];
				}
				file_put_contents("{$this->blockPath}/{$this->blockDataFile}",json_encode($settings));
			}
			$units = scandir("{$this->unitsPath}");
			$unitsNum = count($units);
			$lastUnit = $units[$unitsNum-1];
			if(is_dir($lastUnit))
			{
				$lastUnit = '0000000001.phpdb';
				$input2 = array();
				file_put_contents("{$this->unitsPath}/{$lastUnit}",json_encode($input2));
			}
			$lastFileContent = json_decode(file_get_contents("{$this->unitsPath}/{$lastUnit}"),true);
			$LFContentNum = count($lastFileContent);
			$inputNum = count($input);
			$outArr = array_slice($input, 0, ($settings['unitItemsNumber']-$LFContentNum));
			$outArr2 = array_slice($input, ($settings['unitItemsNumber']-$LFContentNum));
			$outArr2 = array_chunk($outArr2, $settings['unitItemsNumber']);
			if($outArr != null)
			{
				$lastFileContent = array_merge($lastFileContent, $outArr);
				file_put_contents("{$this->unitsPath}/{$lastUnit}",json_encode($lastFileContent));
			}
			$unitNumber = explode('.',$lastUnit);
			$unitNumber = (int)$unitNumber[0];
			for($i=0;$i<count($outArr2);$i++)
			{
				$unitNumber = (int)$unitNumber;
				$unitNumber++;
				$unitNumber = str_pad($unitNumber, 10, "0", STR_PAD_LEFT);
				file_put_contents("{$this->unitsPath}/{$unitNumber}.phpdb",json_encode($outArr2[$i]));
			}
			return true;
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* public function to get data
	* @param int $numResults number of results
	* @param Array or JSON String $cond  condition
	* @return  Array of Data if success
	* @return  false if failure
	*/
	public function get($numResults, $cond = null)
	{
		if($cond == null)
		{
			return $this->getLimit((int)$numResults);
		}
		else
		{
			return $this->getLimitWhere((int)$numResults, $cond);
		}
	}
	
	/**
	* private function to get limit data
	* @param int $numResults number of results
	* @param Array or JSON String $cond  condition
	* @return  Array of Data if success
	*/
	private function getLimit($input)
	{
		$returnArr = array();
		$units = scandir("{$this->unitsPath}");
		$settings = json_decode(file_get_contents("{$this->blockPath}/{$this->blockDataFile}"),true);
		$numUnits = (int)($input/$settings['unitItemsNumber']);
		$count = 0;
		for($i=1;$i<=$numUnits;$i++)
		{
			$unitNumber = str_pad($i, 10, "0", STR_PAD_LEFT);
			if(file_exists("{$this->unitsPath}/{$unitNumber}.phpdb"))
			{
				$returnArr = array_merge($returnArr, json_decode(file_get_contents("{$this->unitsPath}/{$unitNumber}.phpdb"), true));
			}
		}
		if(($temp = $input%$settings['unitItemsNumber']) != 0)
		{
			$unitNumber = str_pad($i, 10, "0", STR_PAD_LEFT);
			if(file_exists("{$this->unitsPath}/{$unitNumber}.phpdb"))
			{
				$returnArr = array_merge($returnArr,array_slice(json_decode(file_get_contents("{$this->unitsPath}/{$unitNumber}.phpdb"), true),0,$temp));
			}
		}
		return $returnArr;
	}
	
	/**
	* private function to get limit data with condition
	* @param int $numResults number of results
	* @param Array or JSON String $cond  condition
	* @return  Array of Data if success
	* @return  false if failure
	*/
	private function getLimitWhere($numResults,$cond)
	{
		if(($cond = $this->makeCondition($cond)) === false)
		{
			return false;
		}
		$ret = array();
		$str = array();
		$units = scandir("{$this->unitsPath}");
		foreach($units as $value)
		{
			
			if(!is_dir($value))
			{
				$data = json_decode(file_get_contents("{$this->unitsPath}/{$value}"), true);
				$co = count($data);
				for($i=0;$i<$co;$i++)
				{
					$str[] = 'if('.str_replace('$i',$i,$cond).'){$ret[]=$data['.$i.'];}';
				}
				$str = '<?php '.implode(' ',$str).'?>';
				eval("?>".$str);
				$str = array();
				$dataCount = count($ret);
				if($dataCount == $numResults)
				{
					return $ret;
				}
				else if($dataCount > $numResults)
				{
					return array_slice($ret,0,$numResults);
				}
			}
		}
		return $ret;
	}
	
	/**
	* public function to get all data
	* @param Array or JSON String $cond  condition
	* @return  Array of Data if success
	* @return  false if failure
	*/
	public function getAll($cond = null)
	{
		if($cond  === null)
		{
			$returnArr = array();
			$units = scandir("{$this->unitsPath}");
			foreach($units as $value)
			{
				if(!is_dir($value))
				{
					$returnArr = array_merge($returnArr, json_decode(file_get_contents("{$this->unitsPath}/{$value}"), true));
				}
			}
			return $returnArr;
		}
		else
		{
			return $this->getWhere($cond);
		}
	}
	
	/**
	* private function to get some data by condition
	* @param Array or JSON String $cond  condition
	* @return  Array of Data if success
	* @return  false if failure
	*/
	private function getWhere($cond)
	{
		if(($cond = $this->makeCondition($cond)) === false)
		{
			return false;
		}
		$ret = array();
		$str = array();
		$units = scandir("{$this->unitsPath}");
		foreach($units as $value)
		{
			
			if(!is_dir($value))
			{
				$data = json_decode(file_get_contents("{$this->unitsPath}/{$value}"), true);
				$co = count($data);
				for($i=0;$i<$co;$i++)
				{
					$str[] = 'if('.str_replace('$i',$i,$cond).'){$ret[]=$data['.$i.'];}';
				}
				$str = '<?php '.implode(' ',$str).'?>';
				eval("?>".$str);
				$str = array();
			}
		}
		return $ret;
	}
	
	/**
	* public function to update data
	* @param Array $input updated data
	* @param Array or JSON String $cond  condition
	* @return  true if success
	* @return  false if failure
	*/
	public function set($input, $cond = null)
	{
		if($cond == null)
		{
			return $this->setAll($input);
		}
		else
		{
			return $this->setWhere($input, $cond);
		}
	}
	
	/**
	* private function to update all data
	* @param Array $input updated data
	* @return  true if success
	* @return  false if failure
	*/
	private function setAll($input)
	{
		$str = array();
		if(($set = $this->makeSet($input)) == false)
		{
			return false;
		}
		$units = scandir("{$this->unitsPath}");
		foreach($units as $value)
		{
			
			if(!is_dir($value))
			{
				$data = json_decode(file_get_contents("{$this->unitsPath}/{$value}"), true);
				$co = count($data);
				for($i=0;$i<$co;$i++)
				{
					$str[] = str_replace('$i',$i,$set);
				}
				$str = '<?php '.implode(' ',$str).'?>';
				eval("?>".$str);
				file_put_contents("{$this->unitsPath}/{$value}",json_encode($data));
				$str = array();
			}
			
		}
		return true;
	}
	
	/**
	* private function to update data with condition
	* @param Array $input updated data
	* @param JSON String or Array $cond condition
	* @return  true if success
	* @return  false if failure
	*/
	private function setWhere($input, $cond)
	{
		$str = array();
		if(($cond = $this->makeCondition($cond)) == false)
		{
			return false;
		}
		if(($set = $this->makeSet($input)) == false)
		{
			return false;
		}
		$units = scandir("{$this->unitsPath}");
		foreach($units as $value)
		{
			
			if(!is_dir($value))
			{
				$data = json_decode(file_get_contents("{$this->unitsPath}/{$value}"), true);
				$co = count($data);
				for($i=0;$i<$co;$i++)
				{
					$str[] = 'if('.str_replace('$i',$i,$cond).'){'.str_replace('$i',$i,$set).'}';
				}
				$str = '<?php '.implode(' ',$str).'?>';
				eval("?>".$str);
				file_put_contents("{$this->unitsPath}/{$value}",json_encode($data));
				$str = array();
			}
			
		}
		return true;
	}
	
	/**
	* private function to make condition to update data
	* @param JSON Strin or Array updated data
	* @return  the condition if success
	* @return  false if failure
	*/
	private function makeSet($input)
	{
		try
		{
			if(!is_array($input))
			{
				if(is_string($input))
				{
					$input = json_decode(str_replace("'",'"',$input),true);
				}
				else
				{
					throw new Exception("Inputs must be array or JSON string !");
					return false;
				}
			}
			foreach($input as $key=>$value)
			{
				if(is_string($value))
				{
					$value = str_replace('\\','\\\\',$value);
					$value = str_replace('"','\"',$value);
					$value = str_replace('$','\$',$value);
					$ret[] = 'if(isset($data[$i]["'.$key.'"])){$data[$i]["'.$key.'"]="'.$value.'";}';
				}
				else
				{
					$ret[] = 'if(isset($data[$i]["'.$key.'"])){$data[$i]["'.$key.'"]='.$value.';}';
				}
			}
			return implode(' ',$ret);
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* public function to delete data
	* @param JSON String or Array $cond the condition
	* @return  true if success
	* @return  false if failure
	*/
	public function delete($cond = null)
	{
		if($cond == null)
		{
			return $this->deleteBlock();
		}
		else
		{
			return $this->deleteWhere($cond);
		}
	}
	
	/**
	* private function to delete data with condition
	* @param JSON String or Array $cond the condition
	* @return  true if success
	* @return  false if failure
	*/
	private function deleteWhere($cond)
	{
		if(is_string($cond) and $cond == '*')
		{
			return $this->deleteAll();
		}
		if(($cond = $this->makeCondition($cond)) === false)
		{
			return false;
		}
		$ret = array();
		$str = array();
		$count = 0;
		$units = scandir("{$this->unitsPath}");
		foreach($units as $value)
		{
			
			if(!is_dir($value))
			{
				$data = json_decode(file_get_contents("{$this->unitsPath}/{$value}"), true);
				$co = count($data);
				for($i=0;$i<$co;$i++)
				{
					$str[] = 'if('.str_replace('$i',$i,$cond).'){unset($data['.$i.']);$count++;}';
				}
				$str = '<?php '.implode(' ',$str).'?>';
				eval("?>".$str);
				$data = array_values($data);
				$ret = array_merge($ret,$data);
				$str = array();
			}
		}
		if($count !== 0)
		{
			$this->deleteAll();
			$this->putAll($ret, false);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* private function to delete all data in this block
	* @return  true
	*/
	private function deleteAll()
	{
		$units = scandir("{$this->unitsPath}");
		foreach($units as $value)
		{
			
			if(file_exists("{$this->unitsPath}/{$value}") and !is_dir($value))
			{
				unlink("{$this->unitsPath}/{$value}");
			}
			
		}
		return true;
	}
		
	/**
	* private function to delete this block
	* @return  true if success
	* @return  false if failure
	*/
	private function deleteBlock()
	{
		try
		{
			if(file_exists($this->blockPath))
			{
				$this->removeDir($this->blockPath);
				$setArr = json_decode(file_get_contents("{$this->dbPath}/{$this->blocksFileName}"),true);
				foreach($setArr as $key=>$value)
				{
					if($value == $this->blockName)
					{
						unset($setArr[$key]);
					}
				}
				$setArr = array_values($setArr);
				file_put_contents("{$this->dbPath}/{$this->blocksFileName}",json_encode($setArr));
				return true;
			}
			else
			{
				throw new Exception("Block Not Found !");
				return false;
			}
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* function to make condition
	* @param JSON String or Array $cond the condition
	* @return  condition if success
	* @return  false if failure
	*/
	private function makeCondition($cond)
	{
		try
		{
			$ret = array();
			if(!is_array($cond))
			{
				if(is_string($cond))
				{
					$cond = json_decode(str_replace("'",'"',$cond),true);
				}
				else
				{
					throw new Exception("Condition must be array or JSON string !");
					return false;
				}
			}
			foreach($cond as $key=>$value)
			{
				$tempANDOR = strtolower(trim($key));
				if($tempANDOR == 'and' or $tempANDOR == 'or')
				{
					return $this->makeMultiCondition($value,$tempANDOR);
				}
				else
				{
					$key2 = trim(preg_replace('/\s+/',' ',$key));
					$key2 = explode(' ',$key);
					$op = end($key2);
					$key2 = reset($key2);
					if(is_string($value))
					{
						$value = str_replace('\\','\\\\',$value);
						$value = str_replace('"','\"',$value);
						$tmp = 'isset($data[$i]["'.$key2.'"]) AND $data[$i]["'.$key2.'"] '.$op.' "'.$value.'"';
					}
					else
					{
						$tmp = 'isset($data[$i]["'.$key2.'"]) AND $data[$i]["'.$key2.'"] '.$op.' '.$value;
					}
					$ret[] = '('.$tmp.')';
				}
			}
			return implode('',$ret);
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* function to make multi-condition
	* @param JSON String or Array $cond the condition
	* @param String $ANDOR the operator
	* @return  condition if success
	* @return  false if failure
	*/
	private function makeMultiCondition($cond,$ANDOR)
	{
		try
		{
			if(!is_array($cond))
			{
				if(is_string($cond))
				{
					$cond = json_decode(str_replace("'",'"',$cond),true);
				}
				else
				{
					throw new Exception("Condition must be array or JSON string !");
					return false;
				}
			}
			foreach($cond as $key=>$value)
			{
				$tempANDOR = strtolower(trim($key));
				if($tempANDOR == 'and' or $tempANDOR == 'or')
				{
					$arr1[] = $this->makeMultiCondition($value,$tempANDOR);
				}
				else
				{
					$key2 = trim(preg_replace('/\s+/',' ',$key));
					$key2 = explode(' ',$key2);
					$op = end($key2);
					$key2 = reset($key2);
					if(is_string($value))
					{
						//$value2 = str_replace('"','\"',$value2);
						//$value2 = str_replace('\\','\\\\',$value2);
						$arr1[] = 'isset($data[$i]["'.$key2.'"]) AND $data[$i]["'.$key2.'"]  '.$op.' "'.$value.'"';
					}
					else
					{
						$arr1[] = 'isset($data[$i]["'.$key2.'"]) AND $data[$i]["'.$key2.'"] '.$op.' '.$value;
					}
				}
			}
			return '('.implode(" $ANDOR ",$arr1).')';
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
}
// تم بحمد الله
?>
