<?php
namespace Druto\Views;
use Druto\Exceptions\ViewException as ViewException;
class View
{
	protected $data=array();
	protected $viewName=null;
	protected $content=null;
	protected $viewPath=null;
	protected $viewContent=null;

	public function prepare($viewName,$data)
	{
		$this->viewName=$viewName;
		$this->data=$data;
		$viewPath=APPDIR.'/'.str_replace('.','/',$this->viewName);
		if(file_exists($viewPath))
		{
			$this->viewPath = $viewPath;
		}
		else if(file_exists($viewPath.'.php'))
		{
			$this->viewPath = $viewPath.'.php';
		}
		else if(file_exists($viewPath.'.html'))
		{
			$this->viewPath = $viewPath.'.html';
		}
		else
		{
			throw new ViewException("View $viewName not found");
		}
		
		$this->display();
	}
	function display()
	{
		echo $this->getViewContent();
	}
	function getViewContent()
	{
		ob_start();
		extract($this->data);
		include $this->viewPath;
		$this->viewContent = ob_get_contents();
		ob_end_clean();
		return $this->viewContent;
	}
}