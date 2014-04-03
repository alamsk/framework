<?php 
namespace Druto\HTML\Form\Elements;
abstract class Element
{

	//abstract protected function getHtml($name,$rows=15,$cols=15);
	protected function toHtmlAttributes($data)
	{
		$params=array();
		if(!is_array($data))
		{
			$params[$data]=$data;
		}
		else
		{
			$params=$data;
		}
		$str='';
		foreach ($params as $key => $value) {
			$str.=$key.'="'.$value.'" ';
		}
		return rtrim($str,' ');
	}

}