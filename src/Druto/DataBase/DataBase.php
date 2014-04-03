<?php
namespace Druto\Database;
use Druto\Configs\Config as Config;
use Druto\Exceptions\DataBaseException as DataBaseException;
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
		if (mysqli_connect_errno()) {
			throw new DataBaseException('Database connection failed: '  . mysqli_connect_error(), E_USER_ERROR);
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
		$result=$this->conn->query($this->queries[count($this->queries) - 1]);
		if($result=== false)
		{
			throw new DataBaseException('Wrong SQL: ' . $this->queries[count($this->queries) - 1] . ' Error: ' . $this->conn->error, E_USER_ERROR); 
		}
		return $result;
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

	public function fetchObject($sql=null)
	{
		if(isset($sql))
		{
			$this->setQuery($sql);
		}
		$result = $this->query();
		$result->data_seek(0);
		return $result->fetch_object();
	}

	public function fetchObjects($sql=null)
	{
		if(isset($sql))
		{
			$this->setQuery($sql);
		}
		$result = $this->query();
		$result->data_seek(0);
		$rows=array();
		while($row=$result->fetch_object()){
			array_push($rows,$row);
		}
		return $rows;
	}

	public function save($table,$data)
	{
		$columns='';
		$values='';
		foreach($data as $key=>$value)
		{
			$columns.='`'.$key.'`,';
			$values.="'$value',";
		}
		$columns=rtrim($columns,',');
		$values=rtrim($values,',');
		$sql="INSERT INTO $table ($columns) VALUES($values)";
		$this->setQuery($sql);
		$this->query();
		return $this->conn->insert_id;
	}

	public function update($table,$data,$where)
	{
		$setValues='';

		foreach($data as $key=>$value)
		{
			$setValues.="`$key`='$value',";
		}
		$setValues=rtrim($setValues,',');
		$sql="UPDATE $table SET $setValues WHERE $where";
		$this->setQuery($sql);
		$this->query();
		return $this->conn->affected_rows;
	}
	

}