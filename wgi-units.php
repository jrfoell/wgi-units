<?php
/**
 * Plugin Name: WGI Units
 * Description: Custom functionality for WGI circuits
 * Version: 1.0
 * Author: Justin Foell
 * Author URI: https://github.com/jrfoell
 * License: GPLv2 or later
 * Text Domain: wgi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

define( 'WGI_PLUGIN_DIR', trailingslashit( dirname( __FILE__) ) );
define( 'WGI_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

require_once WGI_PLUGIN_DIR . 'includes/post-types-taxonomies.php';