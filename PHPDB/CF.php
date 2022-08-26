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
class CF
{
	// @var to save base path
	protected $basePath;
	
	// @var to save databases path
	protected $dbsPath;
	
	// @var to save databases folder name
	protected $dbsFolder = 'DBs';
	
	// @var to save databases file settings name
	protected $dbsFileName = 'dataBases.phpdb';
	
	// @var to save blocks file settings name
	protected $blocksFileName = 'blocks.phpdb';
	
	// @var to save units folder name
	protected $blockDataName = 'units';
	
	// @var to save units file settings name
	protected $blockDataFile = 'settings.phpdb';
	
	/**
	* public constructor
	*/
	public function __construct()
	{
		$this->basePath = __DIR__;
		chmod($this->basePath, 0770);
		$this->dbsPath = "{$this->basePath}/{$this->dbsFolder}";
		if(!file_exists($this->dbsPath))
		{
			mkdir($this->dbsPath, 0770);
			$htaccess = '<Files "*">order allow,denydeny from all</Files>';
			file_put_contents("{$this->dbsPath}/.htaccess",$htaccess);
		}
	}
	
	/**
	* protected function to remove dir with all content data
	* @param String $dir dir path
	*/
	protected function removeDir($dir) {
		chmod($dir, 0770);
		foreach(glob($dir . '/*') as $file)
		{ 
			if(is_dir($file))
			{
				$this->removeDir($file);
			}
			else 
			{
				unlink($file); 
			}
		}
		if(is_dir($dir))
		{
			rmdir($dir);
		}
	}
}
// تم بحمد الله
?>