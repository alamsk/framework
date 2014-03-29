<?php
namespace Druto\Views;
class Render
{
	protected $data=array();

	public function __call($viewType, $arguments)
	{
		$viewTypeClass='Druto\Views\\'.ucwords($viewType);
		$viewOBJ = new $viewTypeClass;
		call_user_func_array(array($viewOBJ, "display"),$arguments);
	}
}