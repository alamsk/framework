<?php
namespace Druto\Views;
use Druto\Configs\Config as Config;
use Druto\Exceptions\ViewException as ViewException;
class Template extends View
{
	protected $templateBaseURL=null;
	function display()
	{
		
		$defaultTemplate=Config::get('app.template.default','default');
		$this->templateBaseURL=BASEURL.'/Templates/'.$defaultTemplate;
		$templatePath=TEMPLATESDIR.'/'.$defaultTemplate.'/index.php';		
		$this->content=$this->getViewContent();		
		ob_start();
		include $templatePath;
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}
}