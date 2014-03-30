<?php
namespace Druto\HTML;
class HTML
{
	private static $js=array();
	private static $css=array();
	public static function addJS($js)
	{
		if(is_array($js))
		{
			foreach($js as $j)
			{
				array_push(self::$js, $j);
			}
		}
		else
		{
			array_push(self::$js, $js);
		}
	}
	public static function addCSS($css)
	{
		if(is_array($css))
		{
			foreach($css as $c)
			{
				array_push(self::$css, $c);
			}
		}
		else
		{
			array_push(self::$css, $css);
		}
	}
	public static function getCSS($htmlformat=false)
	{
		if($htmlformat)
		{
			$cssHTML='';
			foreach(self::$css as $c)
			{
				$cssHTML.= '<link href="'.$c.'" rel="stylesheet">';
			}
			return $cssHTML;
		}
		return self::$css;
	}
	public static function getJS($htmlformat=false)
	{
		if($htmlformat)
		{
			$jsHTML='';
			foreach(self::$js as $j)
			{
				$jsHTML.= '<script src="'.$j.'"></script>';
			}
			return $jsHTML;
		}
		return self::$js;
	}
}