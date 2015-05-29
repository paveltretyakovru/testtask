<?php namespace System;

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class Model{

	public function __construct(\System\Registry $registry){
		$this->registry 	= $registry;
		$this->connection 	= $registry->get('connection');
		$this->security 	= new BCryptPasswordEncoder($this->cost);
	}

	public function printData($array){
		echo "<pre>";
			print_r($array);
		echo "</pre>";
	}

	public function __set($name , $value){
		die("Model don't have `{$name}` ");
	}

	public function save(){
		if(isset($this->fields) && !empty($this->fields)){
			$write = [];
			foreach ($this->fields as $field) {				
				if (isset($this->$field) && !empty($this->$field)) {					
					$write[$field]	= $this->$field;
				}
			}

			if(count($write)){				
				$this->connection->insert($this->table , $write);
				return true;
			}else{
				return false;
			}
		}
	}

	protected 	$connection;
	protected 	$field; 		
	protected 	$security;
	private 	$cost = 10;
	public 		$registry;
}