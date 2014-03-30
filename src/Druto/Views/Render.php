<?php
namespace Druto\Views;
use Druto\HTML\HTML as HTML;
class Render
{
	protected $data=array();

	public function __call($viewType, $arguments)
	{
		$viewTypeClass='Druto\Views\\'.ucwords($viewType);
		$viewOBJ = new $viewTypeClass;
		if(isset($arguments[1]))
		{
			$arguments[1]=array_merge($arguments[1],$this->data);
		}
		call_user_func_array(array($viewOBJ, "prepare"),array_merge($arguments,$this->data));
	}
	
	public function addJS($js)
	{
		
		HTML::addJS($js);
	}

	public function addCSS($css)
	{
		HTML::addCSS($css);
	}
	
	function __set($key,$value)
	{
		$this->data[$key]=$value;
	}
	
	function with($key,$value)
	{
		$this->__set($key,$value);
	}
}