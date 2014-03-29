<?php
namespace Druto\Models;
use Druto\DataBase\DataBase as DataBase;
use Druto\Exceptions\ORMException as ORMException;
class ORM
{
	protected $db=null;
	protected $table=null;
	protected $key=null;
	private $data=array();
	private $fields=null;
	private $where=null;
	private $start=0;
	private $limit=0;
	function __construct()
	{
		$this->db=DataBase::getInstance();
		if(!isset($key))
		{
			$this->key='id';
		}
		if(!isset($table))
		{
			$cls=get_called_class();
			$tmp=explode('\\',$cls);
			$this->table=strtolower(array_pop($tmp)).'s';

		}
	}
	public function __get($name)
	{
		return $this->data[$name];
	}
	public function __set($name,$value)
	{
		$this->data[$name]=$value;
	}
	public function __toString()
	{
		$str='';
		$str.= '<h1>'.$this->table.'</h1>';
		$str.= '<dl>';
		foreach ($this->data as $key => $value) {
			$str.= '<dt><strong>'.$key.'</strong></dt>';
			$str.= '<dd>'.$value.'</dd>';
		}
		$str.= '</dl>';
		return $str;
	}
	private function resetFilters()
	{
		$this->fields=null;
		$this->where=null;
		$this->start=0;
		$this->limit=0;
	}
	public function save()
	{
		if(isset($this->data[$this->key]))
		{
			if(!isset($this->where) || $this->where='')
			{
				$this->where="`$this->key`='".$this->data[$this->key]."'";
			}
			$affected_rows=$this->db->update($this->table,$this->data,$this->where); 
			$this->resetFilters();
			return $affected_rows;
		}
		else
		{
			$id=$this->db->save($this->table,$this->data); 
			$this->data[$this->key]=$id;
			$this->resetFilters();
			return $id;
		}		
		
	}

	public function delete($ids=null)
	{

		if(isset($ids))
		{
			if(!is_array($ids))
			{
				$ids=explode(',',$ids);
			}
			$this->where="`$this->key` IN (".implode(',',$ids ).")";
			
		}
		if (empty($this->where))
		{
			$this->where="`$this->key`='".$this->data[$this->key]."'";
		}
		$sql="DELETE FROM ".$this->table." WHERE ".$this->where;
		$this->resetFilters();
		return $this->db->query($sql);
	}

	public function select($fields='*')
	{
		$this->fields.=$fields;
		return $this;
	}

	public function where($column,$operator,$value)
	{
		$this->where.="$column $operator $value";
		return $this;
	}

	public function andwhere($column,$operator,$value)
	{
		$this->where.=" AND $column $operator $value";
		return $this;
	}

	public function orwhere($column,$operator,$value)
	{
		$this->where.=" OR $column $operator $value";
		return $this;
	}

	public function notwhere($column,$operator,$value)
	{
		$this->where.=" NOT $column $operator $value";
		return $this;
	}

	public function get()
	{
		$sql="Select ".$this->fields." FROM ".$this->table;
		if(isset($this->where))
		{
			$sql.=" WHERE ".$this->where;
		}
		if($this->limit > 0)
		{
			$sql.=" LIMIT ".$this->start.",".$this->limit;
		}
		$this->resetFilters();
		return $this->db->fetchObjects($sql);

	}

	public function first()
	{
		$sql="Select ".$this->fields." FROM ".$this->table;
		if(isset($this->where))
		{
			$sql.=" WHERE ".$this->where;
		}
		if($this->limit > 0)
		{
			$sql.=" LIMIT ".$this->start.",".$this->limit;
		}
		$this->resetFilters();
		return $this->db->fetchObject($sql);

	}
}