<?php
/*
Plugin Name: Flickr Photosets User Shortcode
Plugin URI: https://github.com/pedromarcelojava/Flickr-Photosets-User-Shortcode
Description: O plugin permite que todos os photosets, de forma paginada, sejam exibidos em uma página por meio de único 
shortcode. Dentro de cada photoset estão as imagens que também são exibidas de forma paginada e com lightbox.
Version: 1.3.5
Author: Pedro Marcelo, Beatriz de Paula
Author URI: https://github.com/pedromarcelojava/
License: GPL3
*/

define('FPUS_PLUGIN_DIR', plugin_dir_path(__FILE__));
add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'flickr_photosets_user_link');

include(FPUS_PLUGIN_DIR . 'class.flickr.php');
include(FPUS_PLUGIN_DIR . 'photos.php');
include(FPUS_PLUGIN_DIR . 'photosets.php');
include(FPUS_PLUGIN_DIR . 'plugin-page.php');
include(FPUS_PLUGIN_DIR . 'functions.php');