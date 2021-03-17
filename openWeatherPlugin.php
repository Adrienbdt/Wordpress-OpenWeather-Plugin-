<?php
/** 
 * @package openWeatherPlugin
 */
/*
Plugin Name: openWeatherPlugin
Plugin URI: www.wordpress.com
Description: Plugin using the OpenWeatherMap 5 day for Wordpress
Version: 1.0.0
Author: Adrien Bedouillat
Author URI: https://github.com/Adrienbdt?tab=repositories
License: GPLv2 or later
Text Domain: openWeatherPlugin
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

Copyright 2005-2015 Automattic, Inc.
*/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

// Load Scripts
require_once(plugin_dir_path(__FILE__).'/includes/openWeatherPlugin-scripts.php');


// Load Class
require_once(plugin_dir_path(__FILE__).'/includes/openWeatherPlugin-class.php');

// Register
function register_openweather(){
    register_widget('Open_Weather_Widget');
}

// Hook in function
add_action('widgets_init', 'register_openweather');











