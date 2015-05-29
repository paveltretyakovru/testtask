<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../system/config.inc.php';

$registry 	= new \System\Registry();

# Configs from config.inc.php
$registry->set( 'formFactory' 	, $formFactory );
$registry->set( 'twig' 			, $twig );

$registry->set( 'connection' 	, $connection );
$registry->set( 'session' 		, $session);

$router  	= new \System\Router($registry);
$view 		= new \System\View($registry);

$registry->set( 'router' , $router );
$registry->set( 'view' 	, $view );

$router->delegate();
$router->sendResponse();