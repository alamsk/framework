<?php
namespace Druto\Configs;
class Config
{
	private static $configs=array();
	public static function get($key,$default=null)
	{
		if(count(self::$configs)==0)
		{		
			$configPath=CONFIGSDIR;
			$configfiles=scandir($configPath);
			unset($configfiles[0],$configfiles[1]);
			foreach($configfiles as $configfile)
			{
				$path_parts = pathinfo($configPath.'/'.$configfile);
				$configKey=$path_parts['filename'];
				self::$configs[$configKey]=require_once $configPath.'/'.$configfile;
			}
		}
		//echo '<pre>';
		//print_r(self::$configs);
		return self::getConfigValue($key,$default);
	}

	public static function set($key,$value)
	{
		$tmp=explode('.', $key);
		$tmpConfig=&self::$configs;
		foreach($tmp as $t)
		{
			 $tmpConfig = &$tmpConfig[$t];		
		}
		$tmpConfig=$value;
	}

	private static function getConfigValue($key,$default=null)
	{
		
		$tmp=explode('.', $key);
		$value=self::$configs;
		foreach($tmp as $t)
		{
			if(isset($value[$t]))
			{
				$value=$value[$t];
			}
			else
			{
				return $default;
			}
		}
		return $value;
	}
}