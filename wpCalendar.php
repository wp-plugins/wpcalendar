<?php
/*
Plugin Name: wpCalendar
Description:  Calendar for Wordpress
Author: Thomas Rose
Version: 0.1
*/

setlocale(LC_ALL, 'de_DE.UTF-8','de_DE@euro', 'de_DE', 'de', 'ge');

require_once('bios.php');
require_once('metaboxes.php');
require_once('output.php');
require_once('widget.php');
require_once('shortcode.php');

function wpcalendar_add_scripts() {
  wp_enqueue_style( 'wp-cal-leaflet-css', plugin_dir_url( __FILE__ ) . '/map/leaflet.css' );
  wp_enqueue_script( 'wp-cal-leaflet-js', plugin_dir_url( __FILE__ ) . '/map/leaflet.js' );

}

function wpcalendar_add_adminscripts() {
  wp_enqueue_style( 'wp-cal-datepicker-css', plugin_dir_url( __FILE__ ) . 'css/jquery.datetimepicker.css' );
  wp_enqueue_script( 'wp-cal-datepicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.datetimepicker.js' );
  wp_enqueue_script( 'wp-cal-js', plugin_dir_url( __FILE__ ) . 'js/wpCalendar.js' );
}

add_action( 'wp_enqueue_scripts', 'wpcalendar_add_scripts' );
add_action( 'admin_enqueue_scripts', 'wpcalendar_add_adminscripts' ); 





