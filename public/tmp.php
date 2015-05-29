<?php

/**************** Routing **********************/
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
$request = Request::createFromGlobals();
/***********************************************/

/********************* Model ************************************** */
use \Doctrine\DBAL\Configuration;
use \Doctrine\DBAL\DriverManager;

$config = new Configuration();

$connectionParams = array(
    'dbname' => 'tretyakovpavel',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);

$conn = DriverManager::getConnection($connectionParams, $config);
$messages = $conn->fetchAll('SELECT * FROM messages');
/********************* Model ************************************** */

function printPre($array){
	echo "<pre>";
		print_r($array);
	echo "</pre>";
}

/*
print($request->getMethod());
echo "<pre>";
	print_r($request->query->all());
	print_r($request->getLanguages());
echo "</pre>";
*/