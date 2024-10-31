<?php
/**
 * Plugin Name: Logo Slider
 * Plugin URI: http://demo.radiustheme.com/wordpress/plugins/logo-slider/
 * Description: Logo Slider plugin is fully Responsive and Mobile Friendly WordPress Logo Slider plugin to display your clients or partners logo in slider and grid views.
 * Author: RadiusTheme
 * Version: 1.1.5
 * Text Domain: rt-wp-logo-slider
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 */
if ( ! defined( 'ABSPATH' ) )  exit;

$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
define( 'WPL_SP_PLUGIN_VERSION', $plugin_data['Version'] );
define('WPL_SP_PLUGIN_PATH', dirname(__FILE__));
define( 'WPL_SP_PLUGIN_ACTIVE_FILE_NAME', plugin_basename( __FILE__ ) );
define('WPL_SP_PLUGIN_URL', plugins_url('', __FILE__));
define('WPL_SP_PLUGIN_SLUG', basename( dirname( __FILE__ ) ));
define('WPL_SP_PLUGIN_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages');

require ('lib/init.php');