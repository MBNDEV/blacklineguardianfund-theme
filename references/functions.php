<?php
/**
 * Plugin Name: MBN Landers
 * Plugin URI: https://github.com/MBNDEV/mbn-landers
 * Description: Gutenberg-based landing page builder with Tailwind CSS, theme isolation, and Gravity Forms integration.
 * Version: 1.0.0
 * Author: My Biz Niche
 * Author URI: https://www.mybizniche.com/
 * License: GPL2
 * Text Domain: mbn-landers
 * Requires at least: 6.4
 * Requires PHP: 8.1
 *
 * @package MbnLanders
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'MBN_LANDERS_VERSION', '1.0.0' );
define( 'MBN_LANDERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'MBN_LANDERS_URL', plugin_dir_url( __FILE__ ) );
define( 'MBN_LANDERS_FILE', __FILE__ );

require_once MBN_LANDERS_PATH . 'vendor/autoload.php';

// Plugin Update Checker.
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

PucFactory::buildUpdateChecker(
  'https://github.com/MBNDEV/mbn-landers',
  __FILE__,
  'mbn-landers'
);

// Activation hook — create Gravity Forms.
register_activation_hook( __FILE__, array( 'MbnLanders\Forms\GravityFormsSetup', 'activate' ) );

// Boot the plugin.
add_action( 'plugins_loaded', array( 'MbnLanders\Plugin', 'instance' ) );
