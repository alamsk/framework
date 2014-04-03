<?php 
namespace Druto\HTML\Form;
class Form
{
	public static function __callStatic($element, $arguments)
	{
		$elementClass='Druto\HTML\Form\Elements\\FormElement'.ucwords($element);
		$obj=new $elementClass;
		$classMethod='getHtml';
		return call_user_func_array(array($obj, $classMethod),$arguments);
	}
}