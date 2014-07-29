<?php


wp_register_script(
	'flickr-lightbox-js',
	plugins_url('/js/flickr.min.js', __FILE__),
	array('jquery', 'jquery-effects-core'),
	null,
	true
);

wp_register_style(
	'flickr-lightbox-css',
	plugins_url('/css/flickr.min.css', __FILE__),
	array(),
	null
);