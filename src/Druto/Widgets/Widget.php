<?php
namespace Druto\Widgets;
use Druto\Exceptions\WidgetException;

class Widget
{
	public function handleWidgetAjaxRequest($WidgetBaseDir,$widgetName,$filePath)
	{
		$widgetAjaxPath=realpath(APPDIR).'/'.$WidgetBaseDir.'/'.$widgetName.'/'.$filePath;
		if(file_exists($widgetAjaxPath) && !is_dir($widgetAjaxPath))
		{
			include $widgetAjaxPath;
		}
		else
		{
			throw new WidgetException("File not exist at $widgetAjaxPath");
		}
	}
	
}