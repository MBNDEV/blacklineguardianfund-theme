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

/**
 * Load global button component
 */
require_once get_theme_file_path( 'template-parts/button.php' );

/**
 * Theme setup
 */
function blacklineguardianfund_theme_setup() {
  // Add support for block styles
  add_theme_support( 'wp-block-styles' );
  
  // Add support for full and wide align images
  add_theme_support( 'align-wide' );
  
  // Add support for editor styles
  add_theme_support( 'editor-styles' );
  
  // Add support for responsive embedded content
  add_theme_support( 'responsive-embeds' );
  
  // Add support for custom line height
  add_theme_support( 'custom-line-height' );
  
  // Add support for custom spacing
  add_theme_support( 'custom-spacing' );
  
  // Add support for custom units
  add_theme_support( 'custom-units' );
  
  // Register navigation menus
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

add_action( 'after_setup_theme', 'blacklineguardianfund_theme_setup' );

PucFactory::buildUpdateChecker(
  'https://github.com/MBNDEV/blacklineguardianfund-theme',
  get_theme_file_path( 'style.css' ),
  'blacklineguardianfund-theme'
);

require_once get_theme_file_path( 'block-registry.php' );
require_once get_theme_file_path( 'tailwind-loader.php' );
require_once get_theme_file_path( 'optimize.php' );
