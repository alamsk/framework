<?php
namespace Druto\Views;
use Druto\Exceptions\ViewException as ViewException;
class View
{
	protected $data=array();
	protected $viewName=null;
	protected $content=null;
	protected $js=array();
	protected $css=array();
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

	public function addJS($js)
	{

		if(is_array($js))
		{
			foreach($js as $j)
			{
				array_push($this->js, $j);
			}
		}
		else
		{
			array_push($this->js, $js);
		}

	}

	public function addCSS($css)
	{
		if(is_array($css))
		{
			foreach($css as $c)
			{
				array_push($this->css, $c);
			}
		}
		else
		{
			array_push($this->css, $css);
		}
	}
}