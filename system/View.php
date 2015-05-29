<?php namespace System;
class View{

	public function __construct(\System\Registry $registry){
		$this->registry = $registry;

		# Регистрируем twig
		$this->registrateTwig();
		$this->registrateTwigBridge();
	}

	# Проверяем авторизован ли пользователь
	private function getAuthStatus(){
		$user = new \Model\User($this->registry);
		return $user->checkAuth();
	}

	# Собираем глобальные переменные доступные во всех шаблонах
	private function collectVariables(){
		$host 		= 'http://'.$_SERVER['SERVER_NAME'].'/';
		$errors 	= $this->registry->get('session')->getFlashBag()->get('error', []);
		$success 	= $this->registry->get('session')->getFlashBag()->get('success', []);
		$auth_status= $this->getAuthStatus();

		return compact('host' , 'errors' , 'success' , 'auth_status');
	}

	private function registrateTwigBridge(){		
		$this->twig_bridge = $this->registry->get('twig');		
	}

	# Регистрируем Twig
	private function registrateTwig(){
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem(dirname(__DIR__).'/views');		
		$this->twig	= new \Twig_Environment($loader, array());
	}

	public function render($template_name , $vars){		
		# Загружаем шаблон
		$template 	= $this->twig->loadTemplate($template_name);
		# Собираем вспомогательные переменные
		$add_vars 	= $this->collectVariables();
		# Объединяем вспомогательные переменные с переменными контроллера
		$compact 	= array_merge($add_vars , $vars);

		return $template->render($compact);
	}

	public function bridgeRender($template_name , $vars){		
		# Собираем вспомогательные переменные
		$add_vars 	= $this->collectVariables();
		# Объединяем вспомогательные переменные с переменными контроллера
		$compact 	= array_merge($add_vars , $vars);

		return $this->twig_bridge->render($template_name , $compact);
	}

	public $twig;
	public $twig_bridge;
	public $registry;
}

/*
	# Параетр если кэширования в \Twig_Environment
	# 'cache' => __DIR__.'\cach'
*/