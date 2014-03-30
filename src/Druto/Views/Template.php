<?php
namespace Druto\Views;
use Druto\Exceptions\ViewException as ViewException;
class Template extends View
{
	function display()
	{
		$defaultTemplate='default';
		$templatePath=TEMPLATESDIR.'/'.$defaultTemplate.'/index.php';		
		$this->content=$this->getViewContent();		
		ob_start();
		include $templatePath;
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}
}