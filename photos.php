<?php


function flickr_photos($atts)
{
	$plugin_url = plugin_dir_url( __FILE__ );

	$parameters = shortcode_atts(
		array(
			'photoset_id' => '',
			'per_page' => '12',
			'page' => '1'
		),
		$atts
	);

	$flickr = new Flickr('435adfc6694a87ec61bc2ae12d4fbe60');

	$photos = $flickr->photosets_getPhotos($parameters);

	$i = 1;
	$countPhotos = count($photos['photoset']);

	$content = '';

	wp_enqueue_style('flickr-lightbox-css');

	$content .= '<a class="fpus-back-albuns" href="' . get_permalink() . '">Voltar para álbuns</a>';
	$content .= '<div class="flickr-photos">';

	foreach ($photos['photoset'] as $photo) :
		if ($i % 3 == 1) :
			$content .= '<div class="flickr-table-row">';
		endif;

		$content .= '<div class="flickr-table-cell">';
		$content .= '	<div class="photo">';
		$content .= '		<a href="' . $photo['thumbs']['z'] . '" id="photo-flickr-' . $i . '" data-id="' . $i . '" class="lightbox-flickr">';
		$content .= '			<img src="' . $photo['thumbs']['m'] . '">';
		$content .= '		</a>';
		$content .= '	</div>';
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
	
	if (intval($photos['pages']) != 1)
	{
		$content .= paginationFlickr(intval($photos['pages']), intval($parameters['page']));
	}
	
	wp_enqueue_script('flickr-lightbox-js');

	return $content;
}