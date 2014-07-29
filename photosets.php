<?php

function flickr_photosets($atts)
{
	$plugin_url = plugin_dir_url( __FILE__ );

	$dir = dirname(__FILE__);
	$link = get_permalink();

	$parameters = shortcode_atts(
		array(
			'user_id' => '',
			'per_page' => '12',
			'page' => '1',
			'max_photosets' => '50'
		),
		$atts
	);

	$max_photosets = $parameters['max_photosets'];

	unset($parameters['max_photosets']);

	if ($_SERVER['QUERY_STRING'] == '')
	{
		$q = '?';
	}
	else
	{
		$q = '&';
	}

	$flickr = new Flickr('435adfc6694a87ec61bc2ae12d4fbe60');
	$photosets = $flickr->photosets_getList($parameters);

	if (isset($photosets['code']))
	{
		return $photosets['msg'];
	}
	else
	{
		if ($max_photosets != '50')
		{
			$photosets['photosets'] = array_slice($photosets['photosets'], 0, (int) $max_photosets);
		}

		$i = 1;
		$countPhotos = count($photosets['photosets']);

		$content = '';
		wp_enqueue_style('flickr-lightbox-css');

		

		$content .= '<div class="flickr-photos">';

		foreach ($photosets['photosets'] as $photoset) :

			if ($i % 3 == 1) :
				$content .= '<div class="flickr-table-row">';
			endif;

			$content .= '<div class="flickr-table-cell">';
			$content .= '	<div class="photo">';
			$content .= "		<a href=\"{$link}{$q}photoset={$photoset['id']}\">";
			$content .= "			<img src=\"{$photoset['photoset_cover']}\">";
			$content .= "		</a>";
			$content .= '	</div>';
			$content .= "	<a href=\"{$link}{$q}photoset={$photoset['id']}\">";
			$content .= "		{$photoset['title']}";
			$content .= "	</a>";
			$content .= '</div>';
		

			if ($i % 3 == 0) :
				$content .= '</div>';
			elseif ($i == $countPhotos) :
				$content .= '</div>';
			endif;

			$i++;
		endforeach;

		$content .= '</div>';
		$content .= '<div style="clear: both; height: 10px;"></div>';
	
		if (intval($photosets['pages']) != 1 && $max_photosets == '50')
		{
			$content .= paginationFlickr(intval($photosets['pages']), intval($parameters['page']));
		}


		return $content;
	}
}