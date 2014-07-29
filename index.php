<?php
/*
Plugin Name: Flickr Photosets User Shortcode
Plugin URI: https://github.com/pedromarcelojava/Flickr-Photosets-User-Shortcode
Description: O plugin permite que todos os photosets, de forma paginada, sejam exibidos em uma página por meio de único 
shortcode. Dentro de cada photoset estão as imagens que também são exibidas de forma paginada e com lightbox.
Version: 1.3.1
Author: Pedro Marcelo, Beatriz de Paula
Author URI: https://www.facebook.com/pedro.marcelo.50
License: GPL3
*/

$plugin_dir = plugin_dir_path(__FILE__);

add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'flickr_photosets_user_link');

function flickr_photosets_user_link($links) {
	$settings_link = '<a href="options-general.php?page=flickr-photosets-user-options">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function getLinkPage($page, $permalink)
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

function paginationFlickr($pages, $current_page = 1)
{
	$content = '<div class="paginate-flickr">';

	$permalink = get_permalink();

	$link = getLinkPage(1, $permalink);

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

		$link = getLinkPage($i, $permalink);
		$content .= '<a href="' . $link . '" class="' . $class . '">' . $i . '</a>';
	}

	$link = getLinkPage($pages, $permalink);

	$content .= '<a href="' . $link . '" class="fpus-page-number">Última página</a>';

	$content .= '</div>';

	return $content;
}

include($plugin_dir . 'class.flickr.php');

include($plugin_dir . 'photos.php');
include($plugin_dir . 'photosets.php');
include($plugin_dir . 'plugin-page.php');
include($plugin_dir . 'register-scripts.php');

function flickr_photosets_user_shortcode($atts)
{
	if (isset($_GET['photoset']) && $_GET['photoset'] != "")
	{
		$atts['photoset_id'] = $_GET['photoset'];
		$page = get_query_var('page');
		$page = ($page != 0)? $page : '1';
		$atts['page'] = $page;
		return '<div class="flickr-shortcode">' . flickr_photos($atts) . '</div>';
	}
	else 
	{
		$page = get_query_var('page');
		$page = ($page != 0)? $page : '1';
		$atts['page'] = $page;
		return '<div class="flickr-shortcode">' . flickr_photosets($atts) . '</div>';
	}
}

add_shortcode('flickr-pu-shortcode', 'flickr_photosets_user_shortcode');