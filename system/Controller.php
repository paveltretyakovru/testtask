<?php namespace System;

Class Controller{
	public function __construct(\System\Registry $registry){
		$this->registry = $registry;

		$this->view = $registry->get('view');
	}

	public $registry;
	public $view;
}