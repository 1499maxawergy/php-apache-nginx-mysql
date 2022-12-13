<?php

class Controller_About extends Controller
{

	function __construct()
	{
		$this->view = new View();
	}
	
	function action_index()
	{
		$this->view->generate('about_view.php', 'template_view.php');
	}
}
