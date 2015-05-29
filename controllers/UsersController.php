<?php namespace Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\RedirectResponse;

use \Model\User;

class UsersController extends \System\Controller{
	public function index(){
		$template = $this->twig->loadTemplate('index.html.twig');
		echo $template->render([]);
	}

	public function logout(){
		$session = $this->registry->get('session');
		if($session->has('auth')){
			$session->remove('auth');			
		}

		$response = new RedirectResponse('http://'.$_SERVER['SERVER_NAME'].'/');
		return $response;
	}

	# Вывод формы и авторизация
	public function login(){
		$form 			= $this->constructLoginForm();
		$user 			= new User($this->registry);
		$login_control 	= false;	# Выводить ли форму в шаблон

		# Авторизуем пользователя
		if (isset($_POST[$form->getName()])) {
			$form->bind($_POST[$form->getName()]);

			if ($form->isValid()) {
				$formdata 	= $form->getData();
				$login 		= urldecode(trim($formdata['login']));
				$password 	= trim($formdata['password']);

				if($user->login($login , $password)){					
					$this->registry->get('session')->getFlashBag()->add('success', 'Вы успешно авторизовались!');
					# Авторизуем пользователя
					$user->auth();
					# Указатель на активный пункт меню
					$login_control = true;
				}else{
					$this->registry->get('session')->getFlashBag()->add('error', 'Вы ввели неверный логин\\пароль');
				};
			}
		}

		# Возвращаем шаблон
		return $this->view->bridgeRender('login.html.twig', [
			'form' 			=> $form->createView() ,
			'login_menu' 	=> true ,
			'login_control' => $login_control
		]);

	}	

	# Вывод формы и регистрация
	public function registration(){		
		$user 			 = new User($this->registry);
		$form 			 = $this->constructRegistrForm();
		$registr_control = false;	# Выводить ли форму в шаблон

		# Регистрируем пользователя
		if (isset($_POST[$form->getName()])) {
			$form->bind($_POST[$form->getName()]);

			if ($form->isValid()) {
				$formdata 		= $form->getData();
				$user->set('login' , urldecode(trim($formdata['login'])));
				$user->set('password' , trim($formdata['password']));

				if($user->registrate()){
					$this->registry->get('session')->getFlashBag()->add('success', 'Вы успешно зарегестрировались!');
					$registr_control = true;
				}else{
					$this->registry->get('session')->getFlashBag()->add('error', 'Пользователь с таким логином уже существует');
				}
			}
		}		

		# Возвращаем шаблон
		return $this->view->bridgeRender('registr.html.twig', [
			'form' 				=> $form->createView() ,
			'registr_menu' 		=> true ,
			'registr_control' 	=> $registr_control
		]);
	}

	# Собираем форму для регистрации
	private function constructRegistrForm(){
		$formFactory 	= $this->registry->get('formFactory');
		$form 			= $formFactory->createBuilder()
			->add('login', 'text', array(
				'label' 		=> 'Логин' ,
				'constraints' 	=> array(
					new NotBlank(),
					new Length(['min' => 4]),
				),
			))
			->add('password', 'repeated', [
				'first_options' => ['label' => 'Пароль'] ,
				'second_options' => ['label' => 'Повторите пароль'] ,
				'type' 			=> 'password' ,
				
				'constraints' 	=> [
					new NotBlank(),
					new Length(['min' => 4]),
				],
			])
			->getForm();
		return $form;
	}

	# Собираем форму для регистрации
	private function constructLoginForm(){
		$formFactory 	= $this->registry->get('formFactory');
		$form 			= $formFactory->createBuilder()
			->add('login', 'text', [
				'label'			=> 'Логин' ,

				'constraints' 	=> [
					new NotBlank() ,
					new Length(['min' => 4]) ,
				],
			])
			->add('password', 'password', [
				'label' 		=> 'Пароль' ,

				'constraints' 	=> [
					new NotBlank(),
					new Length(['min' => 4]),
				],
			])
			->getForm();

		return $form;
	}
}

# TMP DATA
# echo $view->render('login.html.twig.' , ['form' => $form->createView()]);