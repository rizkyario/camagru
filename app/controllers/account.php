<?php

class Account extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		parent::__construct();
		if (isset($_SESSION['user']))
			$this->user = (object)$_SESSION['user'];
	}

	public function index()
	{
		if ($this->user)
		{
			$this->view = $this->view('account/index');
			$this->view->render();
		}
		else
			$this->login();
	}

	public function login()
	{
		if ($this->method === 'GET')
			$this->view = $this->view('account/login');
		else
		{
			$user = User::Login($_POST['username'], $_POST['password']);
			if ($user instanceof User)
			{
				$_SESSION['user'] = (array)($user);
				$this->redirect('/photo/show');
			}
			else
				$this->view = $this->view('account/login');
		}
		$this->view->render();
	}

	public function register()
	{
		$this->view = $this->view('account/register');
		$this->view->render();
	}

	public function logout()
	{
		unset($_SESSION['user']);
		$this->view = $this->view('photos/index');
		$this->view->render();
	}
}
