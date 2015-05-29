<?php namespace Controller;

class IndexController extends \System\Controller{
	public function index(){		
		$view = $this->registry->get('view');
		return $view->render('index.html.twig' , ['main_menu' => true]);
	}	
}