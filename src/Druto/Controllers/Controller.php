<?php
namespace Druto\Controllers;
use Druto\Views\Render as Render;
class Controller
{
	protected $render=null;
	function __construct()
	{
		$this->render=new Render();
	}
	public function test()
	{
		return "Test Con";
	}
}