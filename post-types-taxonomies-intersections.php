<?php
/*
Plugin Name: Post Types - Taxonomies intersections
Plugin URI: http://www.beapi.fr
Description: Allow multiple intersection between CPT and Taxo
Version: 2.1
Author: Benjamin Niess
Author URI: http://www.benjamin-niess.fr
Text Domain: ptti
Domain Path: /languages/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die('-1');
}

define( 'PTTI_VERSION', '2.1' );
define( 'PTTI_URL', plugin_dir_url( __FILE__ ) );
define( 'PTTI_DIR', plugin_dir_path( __FILE__ ) );

require( PTTI_DIR . 'inc/functions-tpl.php');
require( PTTI_DIR . 'inc/class-client.php');
require( PTTI_DIR . 'inc/class-widget.php');

// Init PTTI
function ptti_init() {
	// Load up the localization file if we're using WordPress in a different language
	// Place it in this plugin's "lang" folder and name it "ptti-[value in wp-config].mo"
	load_plugin_textdomain( 'ptti', false, basename( rtrim( dirname( __FILE__ ), '/' ) ) . '/languages' );

	// Init client
	new PTTI_Client();

	// Init widget
	add_action( 'widgets_init', create_function( '', 'return register_widget("PTTI_Widget_Custom_Terms_List");' ) );
}
add_action( 'plugins_loaded', 'ptti_init' );
