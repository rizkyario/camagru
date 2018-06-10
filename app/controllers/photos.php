<?php

class Photos extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($id = '')
	{
		if ($id == '')
		{
			$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
			$photos = Photo::find([], $offset);
			$more_v = count($photos) < 5 ? 'hidden' : 'show';
			$this->view = $this->view('photos/index', array('photos' => $photos, 'offset' => count($photos) + $offset, 'more_v' => $more_v));
		}
		else
		{
			$photo = Photo::get(array ('id' => $id));
			if ($photo == NULL)
				$this->redirect('/');
			$comments = Comment::find(array ('photo' => $photo['id']));
			$this->view = $this->view('photos/show', array('photo' => $photo, 'comments' => $comments));
		}
		$this->view->render();
	}

	public function like($id = '')
	{
		if ($id == '' || $this->user == NULL)
			$this->redirect('/account/login');
		$photo = Photo::get(array ('id' => $id));
		$this->user->like($photo);
		$this->redirect('/photos/' . $photo['id']);
	}

	public function delete()
	{
		if (!isset($_POST['photo']))
			$this->redirect('/');
		$photo = Photo::get(array('id' => $_POST['photo']));
		if ($photo == NULL)
			$this->redirect('/');
		if (!$this->authenticate($photo['user']))
			return (false);
		$photo['object']->delete();
	}

	public function insert()
	{
		if (!isset($_POST['img-file']))
			http_response_code(400);
		if ($this->user == NULL)
			http_response_code(401);
		$url = $this->save_photo($_POST['img-file'], $this->generateName());
		if ($url !== NULL)
		{
			$photo = new Photo(array(
				'url' => $url)
			);
			$photo = $this->user->insert_photo($photo);
			$this->redirect('/camera');
		}
		else
			$this->redirect('/');
	}

	public function save_photo($image, $name)
	{
		$filename = 'img/photos/' . $name . '.jpg';
		$comma = strpos($image, ',') + 1;
		$slash = strpos($image, '/') + 1;

		$image_type = substr($image, $slash, strpos($image, ';') - $slash);
		$image = substr($image, $comma);

		$decoded_image = base64_decode($image);

		if (file_exists($filename)) unlink($filename);

		$successful = file_put_contents($filename, $decoded_image);
		if ($successful)
			$url = '/img/photos/' . $name . '.jpg';
		else
			$url = NULL;
		return $url;
	}

	private function generateName()
	{
		$key = "";
		$chaine = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime() * 1000000);
		for ($i = 0; $i < 50; $i++) {
			$key .= $chaine[rand() % strlen($chaine)];
		}
		return $key;
	}
}
