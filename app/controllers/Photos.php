<?php

class Photos extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		$this->user = $this->model('User');
	}

	public function index($name = '')
	{
		$this->view = $this->view('photos/index');
		$this->user->name = $name;
		$this->view->params = ['user' => (array)$this->user];
		$this->view->render();
	}

	public function show($id = '')
	{
		$this->view = $this->view('photos/show');
		$this->view->render();
	}
}