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

class setPHPDB extends CF
{
	// @var to save databases path
	private $dbsFilePath;
	
	// @var to save class error
	private $error;
	
	
	/**
	* public constructor
	*/
	public function __construct(){
		parent::__construct();
		$this->dbsFilePath = "{$this->dbsPath}/{$this->dbsFileName}";
	}
	
	/**
	* public function to create new database
	* @param	String $blockName block name
	* @return	true if success
	* @return	false if failure
	*/
	public function createDB($dbName)
	{
		try
		{
			if($dbName == null)
			{
				throw new Exception('DB Name Can\'t be NULL !');
				return false;
			}
			
			if(!is_string($dbName))
			{
				throw new Exception('DB name must be string !');
				return false;
			}
			
			if(!file_exists($this->dbsFilePath))
			{
				$arr[] = $dbName;
				file_put_contents($this->dbsFilePath,json_encode($arr));
				mkdir("{$this->dbsPath}/{$dbName}", 0770);
				return true;
			}
			else
			{
				$arr = array();
				$arr = json_decode(file_get_contents($this->dbsFilePath),true);
				if(in_array($dbName, $arr))
				{
					throw new Exception($dbName.' database already exists !');
					return false;
				}
				else
				{
					$arr[] = $dbName;
					file_put_contents($this->dbsFilePath,json_encode($arr));
					mkdir("{$this->dbsPath}/{$dbName}", 0770);
					return true;
				}
			}
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* public function to create new block
	* @param	String $blockName blockname
	* @param	String $dbName data basename
	* @return	true if success
	* @return	false if failure
	*/
	public function createBlock($blockName, $dbName)
	{
		try
		{
			$blocksFilePath = "{$this->dbsPath}/{$dbName}/{$this->blocksFileName}";
			if(file_exists($this->dbsFilePath) and is_dir("{$this->dbsPath}/{$dbName}"))
			{
				if(in_array($dbName, json_decode(file_get_contents($this->dbsFilePath),true)))
				{
					if(!file_exists($blocksFilePath))
					{
						$arr[] = $blockName;
						file_put_contents($blocksFilePath,json_encode($arr));
						mkdir("{$this->dbsPath}/{$dbName}/{$blockName}", 0770);
						mkdir("{$this->dbsPath}/{$dbName}/{$blockName}/{$this->blockDataName}", 0770);
						$arr = null;
						$arr['unitItemsNumber'] = 100;
						$arr['lastID'] = -1;
						file_put_contents("{$this->dbsPath}/{$dbName}/{$blockName}/{$this->blockDataFile}",json_encode($arr));
						return true;
					}
					else
					{
						$arr = json_decode(file_get_contents($blocksFilePath),true);
						if(in_array($blockName, $arr))
						{
							throw new Exception($blockName.' Block already exists !');
							return false;
						}
						else
						{
							$arr[] = $blockName;
							file_put_contents($blocksFilePath,json_encode($arr));
							mkdir("{$this->dbsPath}/{$dbName}/{$blockName}", 0770);
							mkdir("{$this->dbsPath}/{$dbName}/{$blockName}/{$this->blockDataName}", 0770);
							$arr = null;
							$arr['unitItemsNumber'] = 100;
							$arr['lastID'] = -1;
							file_put_contents("{$this->dbsPath}/{$dbName}/{$blockName}/{$this->blockDataFile}",json_encode($arr));
							return true;
						}
					}
				}
				else
				{
					throw new Exception($dbName.' database Not Created yet !');
					return false;
				}
			}
			else
			{
				throw new Exception($dbName.' database Not Created yet !');
				return false;
			}
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* public function to check if database exist
	* @param	String $dbName databasename
	* @return	true if success
	* @return	false if failure
	*/
	public function dbExists($dbName)
	{
		try
		{
			if(file_exists($this->dbsFilePath))
			{
				$arr = json_decode(file_get_contents($this->dbsFilePath),true);
				if(in_array($dbName, $arr))
				{
					return true;
				}
				else
				{
					throw new Exception($dbName.' database Not Found !');
					return false;
				}
			}
			else
			{
				throw new Exception('DBs File Not Found !');
				return false;
			}
		}
		catch(Exception $Ex)
		{
			$this->error = $Ex->getMessage().' | Error Line: '.$Ex->getLine().' in File: '.$Ex->getFile();

		} 
	}
	
	/**
	* public function to get error
	* @return  error
	*/
	public function getError()
	{
		return $this->error;
	}
}
// تم بحمد الله
?>