<?php
/**
 * Custom Theme functions and setup.
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
  require_once get_theme_file_path( 'vendor/autoload.php' );
}

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use Carbon_Fields\Carbon_Fields;

// Boot Carbon Fields
if ( class_exists( 'Carbon_Fields\Carbon_Fields' ) ) {
	Carbon_Fields::boot();
}


/**
 * Load global button component
 */
require_once get_theme_file_path( 'template-parts/button.php' );

/**
 * Register navigation menus
 */
function blacklineguardianfund_register_menus() {
	register_nav_menus(
      array(
		  'primary-menu'  => __( 'Primary Menu', 'blacklineguardianfund-theme' ),
		  'footer-menu'   => __( 'Footer Menu', 'blacklineguardianfund-theme' ),
		  'footer-menu-1' => __( 'Footer Menu Column 1', 'blacklineguardianfund-theme' ),
		  'footer-menu-2' => __( 'Footer Menu Column 2', 'blacklineguardianfund-theme' ),
		  'footer-legal'  => __( 'Footer Legal Links', 'blacklineguardianfund-theme' ),
		  'mobile-menu'   => __( 'Mobile Menu', 'blacklineguardianfund-theme' ),
      )
    );
}

add_action( 'after_setup_theme', 'blacklineguardianfund_register_menus' );

PucFactory::buildUpdateChecker(
  'https://github.com/MBNDEV/blacklineguardianfund-theme',
  get_theme_file_path( 'style.css' ),
  'blacklineguardianfund-theme'
);

require_once get_theme_file_path( 'carbon-loader.php' );
require_once get_theme_file_path( 'tailwind-loader.php' );
require_once get_theme_file_path( 'optimize.php' );
/**
 * Register custom block category
 */
function blacklineguardianfund_register_block_category( $categories ) {
	$mbn_category = array(
		'slug'  => 'mbn-blocks',
		'title' => __( 'MBN Blocks', 'blacklineguardianfund-theme' ),
	);

	// Insert at the beginning of the categories list
	array_unshift( $categories, $mbn_category );

	return $categories;
}

add_filter( 'block_categories_all', 'blacklineguardianfund_register_block_category' );
