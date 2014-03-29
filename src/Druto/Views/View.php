<?php
namespace Druto\Views;
class View
{
	protected $data=array();
	protected $viewName=null;

	function display($viewName,$data)
	{
		$this->viewName=$viewName;
		$this->data=$data;
		extract($this->data);
		$viewPath=APPDIR.'/'.str_replace('.','/',$this->viewName);
		if(file_exists($viewPath))
		{
			include $viewPath;
		}
		else if(file_exists($viewPath.'.php'))
		{
			include $viewPath.'.php';
		}
		else if(file_exists($viewPath.'.html'))
		{
			include $viewPath.'.html';
		}
	}
}