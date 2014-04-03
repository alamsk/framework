<?php 
namespace Druto\HTML\Form\Elements;
class FormElementTextArea extends Element
{
	public  function getHtml($name=null,$value=null,$attributes=array())
	{   
		if ( !isset($attributes['name']) )
		{
			$attributes['name'] = $name;
		} 
		return '<textarea '.$this->toHtmlAttributes($attributes).'>'.$value.'</textarea>';
	}
}