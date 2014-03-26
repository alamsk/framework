<?php
namespace Druto;
use Druto\Routes\Route as Route;
class Druto
{
	private $appPath=null;
	private $modulesPath=null;
	function __construct($app_path)
	{
		$this->app_path=$app_path;
		$this->modulesPath=$app_path.'/Modules';
	}
	public function init()
	{
		$this->loadModulesRoute();
		$this->processRoutes();
	}
	public function loadModulesRoute()
	{
		class_alias('Druto\Routes\Route', 'Route');
		$modules=scandir($this->modulesPath);
		unset($modules[0],$modules[1]);
		foreach($modules as $module)
		{
			if(file_exists($this->modulesPath.'/'.$module.'/Route.php'))
			{
				require_once $this->modulesPath.'/'.$module.'/Route.php';
			}
		}
	}
	public function loadVendorRoutes()
	{

	}

	public function processRoutes()
	{
		echo '<pre>';
		$rURI='/'.trim($_SERVER['REQUEST_URI'],'/');
		$tmp=explode('?',$rURI);
		$rURI=$tmp[0];
		$parts=explode('/',$rURI);
		$classMethod=$parts[count($parts) - 1 ];
		
		$method=strtolower($_SERVER['REQUEST_METHOD']);
		$routes=Route::getRoutes($method);

		//print_r($routes);
		foreach($routes as $pattern=>$action)
		{
			if (preg_match_all('~^' . $pattern . '+$~i', $rURI, $matches))
			{
				$tmp=explode('@',$action);
				$class=$tmp[0];
				if(isset($tmp[1]))
				{
					$classMethod=$tmp[1];
				}
				//echo "$class ||  $classMethod";
				$obj=new $action();
				if(isset($obj->restfull) && $obj->restfull)
				{
					$classMethod=$method.$classMethod;
				}
				call_user_func_array(array($obj, $classMethod), array_shift($matches));
				//$obj->$classMethod();
				//print_r($matches);
				//echo "Match";
			}
			else
			{
				echo "404 Page not Found";
			}
		}
	}
}