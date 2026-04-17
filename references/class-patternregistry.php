<?php
/**
 * Block pattern registry — registers landing page starter patterns.
 *
 * @package MbnLanders
 */

namespace MbnLanders\Blocks;

/**
 * Registers block patterns that preload full page templates.
 */
class PatternRegistry {

  /**
   * Register all patterns.
   *
   * Hooked to the init action.
   *
   * @return void
   */
  public function register_all() {
    register_block_pattern_category(
      'mbn-landers',
      array( 'label' => __( 'MBN Landers', 'mbn-landers' ) )
    );

    register_block_pattern(
      'mbn-landers/template-1',
      array(
		  'title'       => __( 'Lander Template 1', 'mbn-landers' ),
		  'description' => __( 'Full landing page with banner, coupons, CTA, services, signs, testimonials, service areas, and footer.', 'mbn-landers' ),
		  'categories'  => array( 'mbn-landers' ),
		  'postTypes'   => array( 'mbn_lander' ),
		  'blockTypes'  => array( 'core/post-content' ),
		  'content'     => $this->get_template_1_content(),
      )
    );

    register_block_pattern(
      'mbn-landers/template-2',
      array(
		  'title'       => __( 'Lander Template 2', 'mbn-landers' ),
		  'description' => __( 'Full landing page with top bar, hero, proof sections, service cards, service area, estimate form, and footer.', 'mbn-landers' ),
		  'categories'  => array( 'mbn-landers' ),
		  'postTypes'   => array( 'mbn_lander' ),
		  'blockTypes'  => array( 'core/post-content' ),
		  'content'     => $this->get_template_2_content(),
      )
    );

    register_block_pattern(
      'mbn-landers/template-3',
      array(
		  'title'       => __( 'Lander Template 3', 'mbn-landers' ),
		  'description' => __( 'Full landing page with top bar, banner, services, touchpoint, steps, testimonials, programs, service offers, and footer.', 'mbn-landers' ),
		  'categories'  => array( 'mbn-landers' ),
		  'postTypes'   => array( 'mbn_lander' ),
		  'blockTypes'  => array( 'core/post-content' ),
		  'content'     => $this->get_template_3_content(),
      )
    );
  }

  /**
   * Build serialized block content for Template 1.
   *
   * @return string
   */
  private function get_template_1_content() {
    $blocks = array(
		'mbn/banner-1',
		'mbn/coupons-1',
		'mbn/cta-1',
		'mbn/services-1',
		'mbn/signs-1',
		'mbn/signs-replace-1',
		'mbn/testimonials-1',
		'mbn/service-areas-1',
		'mbn/cta-2',
		'mbn/footer-1',
    );

    $content = '';
    foreach ( $blocks as $block ) {
      $content .= '<!-- wp:' . $block . ' {"align":"full"} /-->' . "\n\n";
    }

    return $content;
  }

  /**
   * Build serialized block content for Template 2.
   *
   * @return string
   */
  private function get_template_2_content() {
    $blocks = array(
		'mbn/top-bar-2',
		'mbn/ticker-2',
		'mbn/banner-2',
		'mbn/market-sector-2',
		'mbn/social-proof-2',
		'mbn/our-clients-2',
		'mbn/content-box-2',
		'mbn/provider-logos-2',
		'mbn/before-after-2',
		'mbn/restoring-2',
		'mbn/our-bonifidies-2',
		'mbn/water-damage-restore-2',
		'mbn/service-area-2',
		'mbn/prefooter-2',
		'mbn/footer-2',
    );

    $content = '';
    foreach ( $blocks as $block ) {
      $content .= '<!-- wp:' . $block . ' {"align":"full"} /-->' . "\n\n";
    }

    return $content;
  }

  /**
   * Build serialized block content for Template 3.
   *
   * @return string
   */
  private function get_template_3_content() {
    $blocks = array(
		'mbn/top-bar-3',
		'mbn/banner-3',
		'mbn/services-3',
		'mbn/touchpoint-3',
		'mbn/steps-3',
		'mbn/testimonials-3',
		'mbn/programs-3',
		'mbn/service-offers-3',
		'mbn/footer-3',
    );

    $content = '';
    foreach ( $blocks as $block ) {
      $content .= '<!-- wp:' . $block . ' {"align":"full"} /-->' . "\n\n";
    }

    return $content;
  }
}
