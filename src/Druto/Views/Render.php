<?php
namespace Druto\Views;
class Render
{
	protected $data=array();
	protected $js=array();
	protected $css=array();

	public function __call($viewType, $arguments)
	{
		$viewTypeClass='Druto\Views\\'.ucwords($viewType);
		$viewOBJ = new $viewTypeClass;
		$viewOBJ->addJS($this->js);
		$viewOBJ->addCSS($this->css);
		if(isset($arguments[1]))
		{
			$arguments[1]=array_merge($arguments[1],$this->data);
		}
		call_user_func_array(array($viewOBJ, "prepare"),array_merge($arguments,$this->data));
	}
	
	public function addJS($js)
	{
		array_push($this->js, $js);
	}

	public function addCSS($css)
	{
		array_push($this->css, $css);
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