<?php
/**
 * Block registry — auto-discovers and registers all Gutenberg blocks.
 *
 * Each block lives in blocks/build/{block-name}/ and contains a block.json
 * file that WordPress uses for registration via register_block_type().
 *
 * @package MbnLanders
 */

namespace MbnLanders\Blocks;

/**
 * Registers all plugin Gutenberg blocks.
 */
class BlockRegistry {

  /**
   * Register every block found in blocks/build/.
   *
   * Hooked to the init action.
   *
   * @return void
   */
  public function register_all() {
    $build_dir = MBN_LANDERS_PATH . 'build/blocks';

    if ( ! is_dir( $build_dir ) ) {
      return;
    }

    $blocks = glob( $build_dir . '/*/*/block.json' );

    if ( ! $blocks ) {
      return;
    }

    foreach ( $blocks as $block_json ) {
      register_block_type( dirname( $block_json ) );
    }

    $this->inline_critical_styles();

    add_action( 'enqueue_block_editor_assets', array( $this, 'pass_plugin_url' ) );
  }

  /**
   * Mark above-the-fold block stylesheets for inlining.
   *
   * Uses wp_style_add_data( …, 'path', … ) so WordPress outputs a
   * <style> tag in <head> instead of an external <link>, eliminating
   * render-blocking CSS and CLS for the initial viewport.
   *
   * @return void
   */
  private function inline_critical_styles() {
    $critical_blocks = array(
		'mbn/top-bar-1',
		'mbn/banner-1',
		'mbn/top-bar-2',
		'mbn/banner-2',
		'mbn/top-bar-3',
		'mbn/banner-3',
    );

    $build_dir = MBN_LANDERS_PATH . 'build/blocks';

    foreach ( $critical_blocks as $block_name ) {
      // WordPress generates the handle: mbn/top-bar-3 → mbn-top-bar-3-style.
      $handle = str_replace( '/', '-', $block_name ) . '-style';
      // Derive template folder from the slug's trailing digit.
      $slug = explode( '/', $block_name )[1] ?? '';
      preg_match( '/-([0-9]+)$/', $slug, $m );
      $tpl_num  = $m[1] ?? '';
      $css_path = $build_dir . '/template-' . $tpl_num . '/' . $slug . '/style.css';

      if ( file_exists( $css_path ) ) {
        wp_style_add_data( $handle, 'path', $css_path );
      }
    }
  }

  /**
   * Expose the plugin URL to all block editor scripts.
   *
   * @return void
   */
  public function pass_plugin_url() {
    wp_add_inline_script(
      'wp-blocks',
      'window.mbnLandersUrl = window.mbnLandersUrl || ' . wp_json_encode( MBN_LANDERS_URL ) . ';',
      'after'
    );
  }
}
