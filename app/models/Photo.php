<?php

class Photo
{
	public $id;
	public $user;
	public $url;
	public $description;
	public $time_elapse;

	public function __construct(array $params = [])
	{
		array_key_exists('user', $params) ? $this->user = $params['user'] : 0;
		array_key_exists('url', $params) ? $this->url = $params['url'] : 0;
		array_key_exists('description', $params) ? $this->description = $params['description'] : 0;
	}

	static public function get(array $params=[])
	{
		$photo = ORM::getInstance()->findOne('photo', $params);
		if ($photo instanceof Photo)
		{
			$photo = Photo::populate((array)$photo);
			return ($photo);
		}
		else
			return (-1);
		return (NULL);
	}

	static private function like_text($likes)
	{
		if (isset($_SESSION['user']))
			$user = unserialize($_SESSION['user']);
		foreach ($likes as &$like)
		{
			if ($like['user'] == $user->username)
			{
				$like['user'] = 'you';
				break ;
			}
		}
		if (count($likes) == 0)
			return 0;
		else if (count($likes) == 1)
			return $likes[0]['user']. ' like this';
		else if (count($likes) <= 4)
		{
			$text = $likes[0]['user'];
			$i = 1;
			while ($i < count($likes) - 1)
			{
				$text .= ', '.$likes[$i]['user'];
				$i++;
			}
			$text .= ', and '.$likes[$i]['user'];
			return $text. ' like this';
		}
		else
			return count($likes) . ' likes';
	}

	static private function populate($p)
	{
		$user = USER::get(array('username' => $p['user']));
		$p['user_name'] = $user->name;
		$p['user_id'] = $user->id;
		$p['user_username'] = $user->username;
		$p['description_v'] = ($p['description'] == NULL) ? 'hidden' : 'show';
		$likes = Like::find(array('photo' => $p['id']));
		$p['likes'] = Photo::like_text($likes);
		$p['like_logo'] = Like::is_user_like($likes) ? 'fas' : 'far';
		$p['likes_v'] = (count($likes) == 0) ? 'hidden' : 'show';
		$p['time_elapse'] = Photo::time_elapsed_string($p['createdAt']);
		$comments = Comment::find(array('photo' => $p['id']));
		$p['comment_preview_v'] = count($comments) == 0 ? 'hidden' : 'show';
		if (count($comments) > 0)
		{
			$p['comment_preview_username'] =  $comments[count($comments) - 1]['user'];
			$p['comment_preview_message'] =  $comments[count($comments) - 1]['message'];
		}
		$p['comment_more_v'] = count($comments) <= 1 ? 'hidden' : 'show';
		$p['comment_count'] = count($comments);
		return $p;
	}

	static public function find(array $params = [])
	{
		$photos = ORM::getInstance()->findAll('photo', $params, array('createdAt', 'DESC'), []);
		foreach ($photos as &$p)
		{
			$p = Photo::populate($p);
		}
		return $photos;
	}

	public function insert(User $user)
	{
		$this->user = $user->username;
		$this->id = ORM::getInstance()->store('photo', get_object_vars($this));
	}

	static private function time_elapsed_string($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v)
		{
			if ($diff->$k)
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			else
				unset($string[$k]);
		}
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}