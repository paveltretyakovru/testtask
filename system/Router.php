<?php namespace System;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class Router{	

	public function __construct(\System\Registry $registry){
		$this->registry = $registry;
		$this->request 	= $this->getRequest();
		$this->registry->set('request' , $this->request);
	}

	public function delegate(){		
		$this->getController();	# Обрабатываем строку url

		$controller_name 	= '\\Controller\\'.$this->controller.'Controller';
		$method_name 		= $this->method;

		# Проверяем существование контроллера
		if(class_exists($controller_name)){
			$controller = new $controller_name($this->registry);
			if(method_exists($controller, $method_name)){
				$this->registry->set('controller_result' , $controller->$method_name());
			}else{
				die('404 NOT FOUND');
			}
		}else{
			die('404 NOT FOUND');
		}

	}

	public function sendResponse(){
		$response = new Response();
		$response->setContent($this->registry->get('controller_result'));
		$response->send();
	}

	private function getRequest(){
		return Request::createFromGlobals();
	}

	private function getController(){
		# Получаем строку url , вырезаем начальный "/" и пытаемся разбить в массив по "/"
		$url = explode('/', substr($this->request->getPathInfo(), 1));

		if(count($url) > 1){							# Передан url вида controller/method
			$this->controller 	= ucfirst($url[0]);
			$this->method 		= $url[1];
		}
		elseif(count($url) === 1 && $url[0] !== ''){	# Передан url вида controller
			$this->controller 	= ucfirst($url[0]);
			$this->method 		= 'index';
		}
		else{										# Передан пустой url
			$this->controller 	= 'Index';
			$this->method 		= 'index';
		}
	}

	private $path;
	private $method;
	private $request;
	private $registry;
	private $cotroller;
}