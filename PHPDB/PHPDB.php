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

class PHPDB extends CF
{
	// @var to save database name
	private $dbName;
	
	// @var to save data base path
	private $dbPath;
	
	// @var to save class error
	private $error;
	
	/**
	* public constructor
	* @param String $dbName database name
	*/
	public function __construct($dbName = null)
	{
		parent::__construct();
		$this->dbName = $dbName;
		$this->dbPath = "{$this->dbsPath}/{$dbName}";
	}
	
	/**
	* public function to call functions from setPHPDB class
	* @param String $functionName function name
	* @param int $arguments number of function arguments
	*/
	public function __call($functionName,$arguments)
	{
		try
		{
			$count = count($arguments);
			if($functionName == 'createDB' and $count == 1)
			{
				require_once("{$this->basePath}/setPHPDB.php");
				$obj = new setPHPDB();
				if($obj->createDB($arguments[0]))
				{
					return true;
				}
				else
				{
					$this->error = $obj->getError();
					return false;
				}
			}
			else if($functionName == 'createBlock' and $count == 1)
			{
				if($this->dbName != null)
				{
					require_once("{$this->basePath}/setPHPDB.php");
					$obj = new setPHPDB();
					if($obj->createBlock($arguments[0], $this->dbName))
					{
						return true;
					}
					else
					{
						$this->error = $obj->getError();
						return false;
					}
				}
				else
				{
					throw new Exception('Select database first or put second arguments with database name');
					return false;
				}
			}
			else if($functionName == 'createBlock' and $count == 2)
			{
				require_once("{$this->basePath}/setPHPDB.php");
				$obj = new setPHPDB();
				if($obj->createBlock($arguments[0], $arguments[1]))
				{
					return true;
				}
				else
				{
					$this->error = $obj->getError();
					return false;
				}
			}
			else if($functionName == 'dbExists' and $count == 1)
			{
				require_once("{$this->basePath}/setPHPDB.php");
				$obj = new setPHPDB();
				if($obj->dbExists($arguments[0]))
				{
					return true;
				}
				else
				{
					$this->error = $obj->getError();
					return false;
				}
			}
			return false;
			throw new Exception("The Function $functionName( with $count arguments) Not Found !");
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/*
	* @param String $dbName databasename
	* @return  PHPDB object if success
	* @return  PHPDB object if success
	* @return  false if failure
	*/
	public function selectDB($dbName)
	{
		require_once("{$this->basePath}/setPHPDB.php");
		$obj = new setPHPDB();
		if($temp = $obj->dbExists($dbName))
		{
			return new PHPDB($dbName);
		}
		else
		{
			$this->error = $obj->getError();
			return false;
		}
	}
	
	/**
	* @param String $blockName block name
	* @return  blockPHPDB object if success
	* @return  false if failure
	*/
	public function selectBlock($blockName)
	{
		if($this->blockExists($blockName))
		{
			require_once("{$this->basePath}/blockPHPDB.php");
			return new blockPHPDB($blockName,$this->dbName);
		}
		else
		{
			return false;
		}
	}
	
	/**
	* @param String $blockName block name
	* @return  true if success
	* @return  false if failure
	*/
	public function blockExists($blockName)
	{
		try
		{
			if(file_exists("{$this->dbPath}/{$this->blocksFileName}"))
			{
				$arr = json_decode(file_get_contents("{$this->dbPath}/{$this->blocksFileName}"),true);
				if(in_array($blockName, $arr))
				{
					return true;
				}
				else
				{
					throw new Exception($blockName." Block Not Found !");
					return false;
				}
			}
			else
			{
				throw new Exception("Blocks File Not Found !");
				return false;
			}
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* function to delete this Database
	* @return  true if success
	* @return  false if failure
	*/
	public function delete()
	{
		try
		{
			if($this->dbName !== null)
			{
				$this->removeDir("{$this->dbPath}/{$this->dbName}");
				$setArr = json_decode(file_get_contents("{$this->dbsPath}/{$this->dbsFileName}"),true);
				foreach($setArr as $key=>$value)
				{
					if($value == $this->dbName)
					{
						unset($setArr[$key]);
					}
				}
				$setArr = array_values($setArr);
				file_put_contents("{$this->dbsPath}/{$this->dbsFileName}",json_encode($setArr));
				return true;
			}
			else
			{
				throw new Exception("Select Database First To delete it !");
				return false;
			}
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	/*
	* @return  error
	*/
	public function getError()
	{
		return $this->error;
	}
}
// تم بحمد الله
?>