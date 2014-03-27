<?php
namespace Druto\Database;
use Druto\Configs\Config as Config;
class DataBase
{
	private static $instance=null;
	private $conn;
	private $queries=array();

	public static function getInstance($dbConfig=null)
	{

		if(self::$instance===null)
		{
			self::$instance=new DataBase();
		}
		if(isset($dbConfig))
		{
			self::$instance->connect($dbConfig);
		}
		else
		{
			self::$instance->connect();
		}

		return self::$instance;
	}


	public function connect($dbConfig=null)
	{
		if(isset($dbConfig))
		{
			$this->conn=mysqli_connect($dbConfig['host'],$dbConfig['username'],$dbConfig['password'],$dbConfig['db']);
		}
		else
		{
			$default=Config::get('database.default');
			$dbConfig=Config::get('database.'.$default);
			$this->conn=mysqli_connect($dbConfig['host'],$dbConfig['username'],$dbConfig['password'],$dbConfig['db']);
		}
		return $this;
	}

	public function setQuery($sql)
	{
		array_push($this->queries,$sql);
		return $this;
	}

	public function query($sql=null)
	{
		if(isset($sql))
		{
			$this->setQuery($sql);
		}

		return $this->conn->query($this->queries[count($this->queries) - 1]);
	}

	public function getQueries()
	{
		return $this->queries;
	}

	public function getQuery($pos=null)
	{
		if(isset($pos))
		{
			return $this->queries[$pos];
		}
		else
		{
			return $this->queries[count($this->queries) - 1];
		}
	}

}