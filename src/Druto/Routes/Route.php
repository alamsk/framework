<?php
namespace Druto\Routes;
class Route
{
	public static $routes=array();
	public static function get($pat,$action)
	{
		self::$routes['get'][$pat]=$action;
	}
	public static function post($pat,$action)
	{
		self::$routes['post'][$pat]=$action;
	}
	public static function put($pat,$action)
	{
		self::$routes['put'][$pat]=$action;
	}
	public static function patch($pat,$action)
	{
		self::$routes['patch'][$pat]=$action;
	}
	public static function delete($pat,$action)
	{
		self::$routes['delete'][$pat]=$action;
	}

	public static function all($pat,$action)
	{
		self::get($pat,$action);
		self::post($pat,$action);
		self::put($pat,$action);
		self::patch($pat,$action);
		self::delete($pat,$action);
	}
	public static function getRoutes($type='all')
	{
		$type=strtolower($type);
		if($type=='all')
		{
			return self::$routes;
		}
		return self::$routes[$type];
	}
}