<?php
/*
Plugin Name: Taxonomy Term Listing - Visual Composer Addon
Author: Manisha Makhija
Author URI: https://profiles.wordpress.org/manishamakhija
Version: 1.1.1
Description: Creates nested list of categories
Text Domain: taxonomy-term-listing-visual-composer-addon
Domain path: /languages
*/

if ( ! defined( 'ABSPATH' ) ){
	exit;
}

define( 'TAXONOMY_LISTING_ADDON_VERSION', '1.1.1' );
define( 'TAXONOMY_LISTING_ADDON_REQUIRED_WP_VERSION', '4.3' );
define( 'TAXONOMY_LISTING_ADDON', __FILE__ );
define( 'TAXONOMY_LISTING_ADDON_BASENAME', plugin_basename( TAXONOMY_LISTING_ADDON ) );
define( 'TAXONOMY_LISTING_ADDON_PLUGIN_DIR', plugin_dir_path( TAXONOMY_LISTING_ADDON ) );
define( 'TAXONOMY_LISTING_ADDON_PLUGIN_URL', plugin_dir_url( TAXONOMY_LISTING_ADDON ) );

// Checking for Visual Composer activation
add_action( 'admin_init', 'taxonomy_listing_init_addons' );
function taxonomy_listing_init_addons() {
  if( ! defined( 'WPB_VC_VERSION' ) ) {
    add_action( 'admin_notices', 'admin_notice_tlvs_activation' );
  }
}

// Admin notice for required visual composer.
function admin_notice_tlvs_activation(){
  echo '<div class="error"><p>' . __('The <strong>Taxonomy Term Listing Visual Composer addon </strong> requires <strong>Visual Composer</strong> installed and activated.','taxonomy-term-listing-visual-composer-addon') . '</p></div>';
}

// Register activate hook.
function taxonomy_listing_addon_activate() {
	// write code here.
}
register_activation_hook(  __FILE__, 'taxonomy_listing_addon_activate' );

// Register deactivate hook.
function taxonomy_listing_addon_deactivate() {
	// write code here.
}
register_deactivation_hook( __FILE__, 'taxonomy_listing_addon_deactivate' );

// Include plugin.php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// Checking for js composer.
if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
	require_once( TAXONOMY_LISTING_ADDON_PLUGIN_DIR . '/taxonomy-listing.php' );
}