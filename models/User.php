<?php
namespace Model;

use \System\Model;

class User extends Model{

	public function set($name , $value){
		if ($name === 'password') {
			$value = $this->security->encodePassword($value , $this->salt);
		}

		$this->$name = $value;
	}

	public function auth(){
		$session = $this->registry->get('session');
		$session->set('auth' , [
			'login' 	=> $this->login ,
			'password' 	=> $this->password
		]);
	}

	public function checkAuth(){
		$session = $this->registry->get('session');		

		if($session->has('auth')){
			$auth 		= $session->get('auth');
			$login 		= $auth['login'];
			$password 	= $auth['password'];

			return $this->login($login , $password);
		}else{
			return false;
		}
	}

	public function login($login , $password){

		# Проверяем, существует ли пользователь с таким логином
		$user_data = $this->connection->fetchAll('SELECT * FROM users WHERE login = ?', array($login));

		if($user_data){
			if($this->security->isPasswordValid($user_data[0]['password'] , $password , $this->salt)){
				$this->login 	= $login;
				$this->password = $password;
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}		
	}

	public function registrate(){
		if (empty($this->login) || empty($this->password)) return false;

		# Проверяем, существует ли пользователь с таким логином
		$check = $this->connection->fetchColumn('SELECT login FROM users WHERE login = ?', array($this->login), 0);
		if($check){
			return false;
		}else{
			return $this->save();			
		}
	}

	public $login;
	public $password;

	private $salt = 'UJb7CAqGIWwReterAIe83N';
	protected $table 	= "users";
	protected $fields 	= ['login' , 'password'];
}