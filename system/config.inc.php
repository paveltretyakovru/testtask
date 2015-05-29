<?php
use Symfony\Component\Validator\Validation;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\XliffFileLoader;

use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;

use \Doctrine\DBAL\Configuration;
use \Doctrine\DBAL\DriverManager;

use Symfony\Component\HttpFoundation\Session\Session;

define('CSRF_SECRET', 'DVJMVAOgelhh8NnAnSe6inV5L3xoOENwcUNAiuqH');
define('DEFAULT_FORM_THEME', 'bootstrap_form.html.twig');

define('VENDOR_DIR', realpath(__DIR__ . '/../vendor'));
define('VENDOR_FORM_DIR', VENDOR_DIR . '/symfony/form/Symfony/Component/Form');
define('VENDOR_VALIDATOR_DIR', VENDOR_DIR . '/symfony/validator/Symfony/Component/Validator');
define('VENDOR_TWIG_BRIDGE_DIR', VENDOR_DIR . '/symfony/twig-bridge/Symfony/Bridge/Twig');
define('VIEWS_DIR', realpath(__DIR__ . '/../views'));

$csrfProvider 	= new 	DefaultCsrfProvider(CSRF_SECRET);
$validator 		= 		Validation::createValidator();
$translator 	= new 	Translator('en');
$twig 			= new 	Twig_Environment(
	new Twig_Loader_Filesystem(
		[
    		VIEWS_DIR,
    		VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form',
		]
	)
);

$formEngine = new TwigRendererEngine(array(DEFAULT_FORM_THEME));
$formEngine->setEnvironment($twig);

$twig->addExtension(new FormExtension(new TwigRenderer($formEngine, $csrfProvider)));
$twig->addExtension(new TranslationExtension($translator));

$session    = new Session();
$session->start();


$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new CsrfExtension($csrfProvider))
    ->addExtension(new ValidatorExtension($validator))
    ->getFormFactory();


# Create database connection
$config 			= new Configuration();
$connectionParams 	= [
    'dbname' 	=> 'tz',
    'user' 		=> 'tz',
    'password' 	=> 'tz12345',
    'host' 		=> 'localhost',
    'driver' 	=> 'pdo_mysql',
];
$connection = DriverManager::getConnection($connectionParams, $config);