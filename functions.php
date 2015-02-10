<?php

add_action('wp_enqueue_scripts', 'fpus_enqueue_scripts');

function fpus_enqueue_scripts()
{
	wp_enqueue_script(
		'flickr-lightbox-js',
		plugins_url('/js/flickr.min.js', __FILE__),
		array('jquery', 'jquery-effects-core'),
		null,
		true
	);

	wp_enqueue_style(
		'flickr-lightbox-css',
		plugins_url('/css/flickr.min.css', __FILE__),
		array(),
		null,
		'screen, print'
	);
}


function flickr_photosets_user_shortcode($atts)
{
	if (isset($_GET['photoset']) && $_GET['photoset'] != "")
	{
		$atts['photoset_id'] = $_GET['photoset'];
		$page = get_query_var('page');
		$page = ($page != 0)? $page : '1';
		$atts['page'] = $page;
		return '<div class="flickr-shortcode">' . fpus_flickr_photos($atts) . '</div>';
	}
	else 
	{
		$page = get_query_var('page');
		$page = ($page != 0)? $page : '1';
		$atts['page'] = $page;
		return '<div class="flickr-shortcode">' . fpus_flickr_photosets($atts) . '</div>';
	}
}

add_shortcode('flickr-pu-shortcode', 'flickr_photosets_user_shortcode');

function flickr_photosets_user_link($links)
{
	$settings_link = '<a href="options-general.php?page=flickr-photosets-user-options">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function fpusGetLinkPage($page, $permalink)
{
	$QUERY_STRING = $_SERVER['QUERY_STRING'];

	if (strpos($permalink, '?') === false)
	{
		if ($QUERY_STRING == "")
		{
			$link = $permalink . '?page=' . $page;
		}
		else
		{
			$link = $permalink . '?' . $QUERY_STRING . '&page=' . $page;
		}
	}
	else
	{
		$pos = strpos($permalink, '?') + 1;
		$substr = substr($permalink, $pos);
		$arr = array($substr => '');
		$QUERY_STRING = strtr($QUERY_STRING, $arr);
		$QUERY_STRING = preg_replace('/\&page\=\d/', '', $QUERY_STRING);

		if ($QUERY_STRING == "")
		{
			$link = $permalink . '&page=' . $page;
		}
		else
		{
			$link = $permalink . '&' . $QUERY_STRING . '&page=' . $page;
		}
	}

	$link = preg_replace('/\&{2,}/', '&', $link);

	return $link;
}

function fpusPaginationFlickr($pages, $current_page = 1)
{
	$content = '<div class="paginate-flickr">';

	$permalink = get_permalink();

	$link = fpusGetLinkPage(1, $permalink);

	$content .= '<a href="' . $link . '" class="fpus-page-number">Primeira página</a>';

	if ($current_page == 1)
	{
		$start = 1;
		$end = (($start + 2) > $pages)? $pages : $start + 2;
	}
	else if($current_page == $pages)
	{
		$end = $pages;
		$start = (($end - 2) < 1)? 1 : $end - 2;
	}
	else
	{
		$start = $current_page - 1;
		$end = $current_page + 1;
	}

	for ($i = $start; $i <= $end; $i++) { 
		$class = 'fpus-page-number';

		if ($i == $current_page)
		{
			$class = 'fpus-current-page ' . $class;
		}

		$link = fpusGetLinkPage($i, $permalink);
		$content .= '<a href="' . $link . '" class="' . $class . '">' . $i . '</a>';
	}

	$link = fpusGetLinkPage($pages, $permalink);

	$content .= '<a href="' . $link . '" class="fpus-page-number">Última página</a>';

	$content .= '</div>';

	return $content;
}