<?php
/*
Plugin Name: Category Carousel
Plugin URI: http://www.e-verbavolant.it
Description: Category Carousel
Author: Gianmarco Leone
Author URI: http://www.e-verbavolant.it
Version: 0.2
Contains code by Max Rolon from www.barrelny.com
Text Domain: category-carousel
Domain Path: /languages
*/
require_once('class-CategoryCarousel.php');

$GLOBALS['CategoryCarousel'] = new CategoryCarousel();

function category_carousel_load_plugin_textdomain() {
  load_plugin_textdomain( 'category-carousel', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
 
add_action( 'plugins_loaded', 'category_carousel_load_plugin_textdomain' );


?>