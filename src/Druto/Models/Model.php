<?php
namespace Druto\Models;
use Druto\DataBase\DataBase as DataBase;
class Model
{
	protected $db=null;
	function __construct()
	{
		$this->db=DataBase::getInstance();
	}
}