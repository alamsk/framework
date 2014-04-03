<?php
namespace Druto;
use Druto\Exceptions\ControllerException;

use Druto\Routes\Route as Route;
use Druto\Exceptions\RouteException as RouteException;
class Druto
{
	private $appPath=null;
	private $modulesPath=null;
	function __construct()
	{
		$this->app_path=APPDIR;
		$this->modulesPath=MODULESDIR;
	}
	public function init()
	{
		$this->loadGlobalConfigs();
		$this->loadModulesRoute();
		$this->processRoutes();
	}
	public function loadGlobalConfigs()
	{
		$globalConfigDir=$this->app_path.'/Configs';

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
		//echo '<pre>';
		$rURI='/'.trim($_SERVER['REQUEST_URI'],'/');
		$tmp=explode('?',$rURI);
		$rURI=$tmp[0];
		$parts=explode('/',$rURI);
		$classMethod=$parts[count($parts) - 1 ];

		$method=strtolower($_SERVER['REQUEST_METHOD']);
		$routes=Route::getRoutes($method);
		$widgetAjaxRoutes=array();
		$widgetAjaxRoutes["/widgetajax/(Widgets|Modules)/([a-zA-Z0-9_-]+)/([a-zA-z0-9-_./]+)"]='Druto\Widgets\Widget@handleWidgetAjaxRequest';
		$routes=array_merge($widgetAjaxRoutes,$routes);
		foreach($routes as $pattern=>$action)
		{
			if (preg_match_all('~^' . $pattern . '+$~i', $rURI, $matches))
			{
				$params=array();
				$tmpmatches=array_shift($matches);
				foreach($matches as $tm)
				{
					if(is_array($tm))
					{
						foreach($tm as $m)
						{
							array_push($params,$m);
						}
					}
					else
					{
						array_push($params,$tm);
					}
				}

				if(is_object($action) && is_callable($action))
				{
					call_user_func_array($action,$params);
				}
				else
				{
					$tmp=explode('@',$action);
					$class=$tmp[0];
					if(!class_exists($class))
					{
						throw new ControllerException($class." Not Found");
					}
					if(isset($tmp[1]))
					{
						$classMethod=$tmp[1];
					}
					//echo "$class ||  $classMethod";
					$obj=new $class();
					if(isset($obj->restfull) && $obj->restfull)
					{
						$classMethod=$method.$classMethod;
					}
					
					if(!method_exists($obj,$classMethod))
					{
						throw new ControllerException("Method $classMethod() not exist in ".$class);
					}

					call_user_func_array(array($obj, $classMethod),$params);
				}
				exit();
				//$obj->$classMethod();
				//print_r($matches);
				//echo "Match";
			}
		}
		throw new RouteException("404 Page not Found");
	}
}