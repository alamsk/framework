<?php
namespace Druto\Views;
class Pdf
{
	function display($viewName,$data)
	{
		$this->viewName=$viewName;
		$this->data=$data;
		echo "From Normal PDF View ".$this->viewName;
	}
}