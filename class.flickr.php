<?php

/**
*
*	@author Pedro Marcelo
*	@version 1.0
*/

class Flickr
{
	
	private $url_rest = 'https://api.flickr.com/services/rest/';

	private $farm = 'https://farm';

	private $static_flickr = '.staticflickr.com/';

	private $api_key;

	private $api_secret;

	public function __construct($api_key, $api_secret = '')
	{
		$this->api_key = $api_key;

		$this->api_secret = $api_secret;
	}

	public function get($method, $arguments)
	{
		$arguments['method'] = $method;
		$arguments['api_key'] = $this->api_key;

		$query = http_build_query($arguments);

		$result = file_get_contents($this->url_rest . '?' . $query);

		return new SimpleXMLElement($result);
	}

	public function photosets_getList($arguments)
	{
		$xml = $this->get('flickr.photosets.getList', $arguments);

		if ($xml['stat'] == 'fail')
		{
			return $xml->err;
		}

		$photosets = array(
			'page' => (string) $xml->photosets['page'],
			'pages' => (string) $xml->photosets['pages'],
			'perpage' => (string) $xml->photosets['perpage'],
			'total' => (string) $xml->photosets['total'],
			'photosets' => array()
		);

		foreach ($xml->photosets->photoset as $photoset) {
			$photoset_cover = $this->buildPhoto(
				$photoset['primary'],
				$photoset['secret'],
				$photoset['farm'],
				$photoset['server']
			);

			$photosets['photosets'][] = array(
				'id' => (string) $photoset['id'],
				'title' => (string) $photoset->title,
				'description' => (string) $photoset->description,
				'primary' => (string) $photoset['primary'],
				'secret' => (string) $photoset['secret'],
				'server' => (string) $photoset['server'],
				'farm' => (string) $photoset['farm'],
				'photos' => (string) $photoset['photos'],
				'videos' => (string) $photoset['videos'],
				'needs_interstitial' => (string) $photoset['needs_interstitial'],
				'visibility_can_see_set' => (string) $photoset['visibility_can_see_set'],
				'count_views' => (string) $photoset['count_views'],
				'count_comments' => (string) $photoset['count_comments'],
				'can_comment' => (string) $photoset['can_comment'],
				'date_create' => (string) $photoset['date_create'],
				'date_update' => (string) $photoset['date_update'],
				'photoset_cover' => $photoset_cover,
				'photoset_url' => $this->getUrlPhotoset($arguments['user_id'], $photoset)
			);
		}

		return $photosets;
	}

	public function photosets_getPhotos($arguments)
	{
		$xml = $this->get('flickr.photosets.getPhotos', $arguments);

		if ($xml['stat'] == 'fail')
		{
			return $xml->err;
		}

		$photoset = array(
			'id' => (string) $xml->photoset['id'],
			'primary' => (string) $xml->photoset['primary'],
			'owner' => (string) $xml->photoset['owner'],
			'ownername' => (string) $xml->photoset['ownername'],
			'page' => (string) $xml->photoset['page'],
			'per_page' => (string) $xml->photoset['per_page'],
			'perpage' => (string) $xml->photoset['perpage'],
			'pages' => (string) $xml->photoset['pages'],
			'total' => (string) $xml->photoset['total'],
			'title' => (string) $xml->photoset['title'],
			'photoset' => array()
		);

		foreach ($xml->photoset->photo as $photo) {
			$photoset['photoset'][] = array(
				'id' => (string) $photo['id'],
				'title' => (string) $photo['title'],
				'isprimary' => (string) $photo['isprimary'],
				'secret' => (string) $photo['secret'],
				'server' => (string) $photo['server'],
				'farm' => (string) $photo['farm'],
				'thumbs' => array(
					'm' => $this->buildPhoto(
						$photo['id'],
						$photo['secret'],
						$photo['farm'],
						$photo['server']
					),
					'n' => $this->buildPhoto(
						$photo['id'],
						$photo['secret'],
						$photo['farm'],
						$photo['server'],
						'n'
					),
					'z' => $this->buildPhoto(
						$photo['id'],
						$photo['secret'],
						$photo['farm'],
						$photo['server'],
						'z'
					),
					'c' => $this->buildPhoto(
						$photo['id'],
						$photo['secret'],
						$photo['farm'],
						$photo['server'],
						'c'
					),
					'b' => $this->buildPhoto(
						$photo['id'],
						$photo['secret'],
						$photo['farm'],
						$photo['server'],
						'b'
					)
				)
			);
		}

		return $photoset;
	}

	private function buildPhoto($primary, $secret, $farm, $server, $size = 'm')
	{
		return $this->farm . $farm . $this->static_flickr . $server . '/' . $primary . '_' . $secret . '_' . $size . '.jpg';
	}

	private function getUrlPhotoset($user_id, $photoset)
	{
		$id = $photoset['id'];
		return "http://flickr.com/photos/$user_id/sets/$id";
	}

}