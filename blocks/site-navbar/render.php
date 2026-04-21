<?php
/**
 * Server-side render for the Site Navbar block.
 *
 * @package BlacklineGuardianFund
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content (empty for this block).
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$logo_url       = $attributes['logoUrl'] ?? '';
$logo_alt       = sanitize_text_field( $attributes['logoAlt'] ?? 'Black Line Guardian Fund' );
$menu_id        = absint( $attributes['menuId'] ?? 3 );
$mobile_menu_id = absint( $attributes['mobileMenuId'] ?? 0 );
$donate_label   = sanitize_text_field( $attributes['donateLabel'] ?? 'Donate Now' );
$donate_url     = $attributes['donateUrl'] ?? '#donate';
$bg_color       = sanitize_hex_color( $attributes['bgColor'] ?? '' );
$bg_color       = $bg_color ? $bg_color : '#F9F5EE';

// Use mobile menu ID if set, otherwise fall back to desktop menu.
$mobile_menu_id = $mobile_menu_id > 0 ? $mobile_menu_id : $menu_id;

$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => 'site-navbar',
	  'style' => 'background-color: ' . $bg_color . ';',
  )
);
?>
<header <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
  <div class="site-navbar__inner">

    <?php // ── Logo ── ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-navbar__logo" aria-label="<?php echo esc_attr( $logo_alt ); ?>">
      <?php if ( $logo_url ) : ?>
        <img
          src="<?php echo esc_url( $logo_url ); ?>"
          alt="<?php echo esc_attr( $logo_alt ); ?>"
          class="site-navbar__logo-img"
          loading="eager"
        >
      <?php else : ?>
        <svg
          class="site-navbar__logo-icon"
          width="36"
          height="44"
          viewBox="83 17 45 58"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          aria-hidden="true"
          focusable="false"
        >
          <path d="M104.929 17.6352C105.556 17.6139 124.824 24.3531 126.718 24.978C126.628 30.6232 126.779 36.3667 126.715 42.0215C126.675 45.5088 126.928 49.0328 126.313 52.4656C125.391 57.6116 122.749 62.1573 118.648 65.4495C117.341 66.4983 115.734 67.4422 114.304 68.3421L108.61 71.9195C107.563 72.5759 106.35 73.2988 105.34 73.9913L105.215 74.0387C105.067 74.0245 93.7273 66.941 92.7545 66.2578C91.7786 65.5746 90.8615 64.812 90.0129 63.9782C86.5028 60.5126 84.2952 55.9595 83.7575 51.0765C83.5742 49.4033 83.6101 47.8561 83.6115 46.1764L83.6125 40.3562L83.6049 24.9266L97.2859 20.26C99.7752 19.4114 102.474 18.5439 104.929 17.6352ZM86.8983 27.4186L86.8968 40.9687L86.8978 46.1077C86.8963 48.0842 86.8538 49.6275 87.1501 51.6063C87.754 55.414 89.5646 58.9322 92.3197 61.6511C93.0424 62.3715 93.828 63.0271 94.6674 63.6101C95.535 64.2165 105.002 70.1174 105.222 70.1261L112.178 65.7216C113.557 64.8504 115.21 63.8783 116.475 62.899C119.816 60.314 122.08 56.4448 122.96 52.3557C123.5 49.8518 123.341 46.8965 123.337 44.3002L123.337 33.3483C123.336 31.5852 123.257 29.1251 123.362 27.4315L110.345 22.9542C109.585 22.6931 105.502 21.217 105.087 21.2212C103.984 21.5316 102.536 22.0677 101.42 22.4467L94.7791 24.7005L89.7553 26.4106C88.949 26.6843 87.6406 27.0867 86.8983 27.4186Z" fill="black"/>
          <path d="M116.995 28.6704C117.254 28.7222 120.302 31.8334 120.698 32.2351C119.434 33.5632 118.004 34.9379 116.701 36.2391L109.417 43.506L94.5791 58.3097C93.4075 57.3316 92.068 55.7929 90.8577 54.7284C91.5989 54.0261 92.4192 53.1592 93.1554 52.4263L98.2991 47.3109L116.995 28.6704Z" fill="black"/>
        </svg>
      <?php endif; ?>
    </a>
    <?php // ── Navigation menu ── ?>
    <?php

    if ( $menu_id > 0 ) {
      // Add custom class to menu links.
      $add_menu_link_class = function ( $atts ) {
        $atts['class'] = 'site-navbar__nav-link';
        return $atts;
      };
      add_filter( 'nav_menu_link_attributes', $add_menu_link_class, 10, 1 );

      // Add custom class to menu items.
      $add_menu_item_class = function ( $classes ) {
        $classes[] = 'site-navbar__nav-item';
        return $classes;
      };
      add_filter( 'nav_menu_css_class', $add_menu_item_class, 10, 1 );

      wp_nav_menu(
        array(
			'menu'                 => $menu_id,
			'container'            => 'nav',
			'container_class'      => 'site-navbar__nav',
			'container_aria_label' => esc_attr__( 'Primary navigation', 'blacklineguardianfund-theme' ),
			'menu_class'           => 'site-navbar__nav-list',
			'items_wrap'           => '<ul class="%2$s">%3$s</ul>',
			'depth'                => 1,
			'fallback_cb'          => false,
        )
      );

      // Remove filters after use.
      remove_filter( 'nav_menu_link_attributes', $add_menu_link_class, 10 );
      remove_filter( 'nav_menu_css_class', $add_menu_item_class, 10 );
    }
    ?>

    <?php // ── Right side actions (donate + mobile toggle) ── ?>
    <div class="site-navbar__actions">
      <?php // ── Donate button ── ?>
      <a href="<?php echo esc_url( $donate_url ); ?>" class="site-navbar__donate">
        <?php echo esc_html( $donate_label ); ?>
        <span class="site-navbar__donate-arrow" aria-hidden="true">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
            <path d="M2.5 8.5L7.5 3.5M7.5 3.5H3M7.5 3.5V8" stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      </a>

      <?php // ── Mobile menu toggle ── ?>
      <button
        class="site-navbar__mobile-toggle"
        aria-label="<?php echo esc_attr__( 'Open mobile menu', 'blacklineguardianfund-theme' ); ?>"
        aria-expanded="false"
        data-mobile-toggle
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>

  </div>

  <?php // ── Mobile menu overlay ── ?>
  <div class="site-navbar__mobile-overlay" data-mobile-overlay>
    <div class="site-navbar__mobile-header">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-navbar__mobile-logo" aria-label="<?php echo esc_attr( $logo_alt ); ?>">
        <?php if ( $logo_url ) : ?>
          <img
            src="<?php echo esc_url( $logo_url ); ?>"
            alt="<?php echo esc_attr( $logo_alt ); ?>"
            class="site-navbar__mobile-logo-img"
            loading="eager"
          >
        <?php else : ?>
          <svg
            class="site-navbar__mobile-logo-icon"
            width="36"
            height="44"
            viewBox="83 17 45 58"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
            focusable="false"
          >
            <path d="M104.929 17.6352C105.556 17.6139 124.824 24.3531 126.718 24.978C126.628 30.6232 126.779 36.3667 126.715 42.0215C126.675 45.5088 126.928 49.0328 126.313 52.4656C125.391 57.6116 122.749 62.1573 118.648 65.4495C117.341 66.4983 115.734 67.4422 114.304 68.3421L108.61 71.9195C107.563 72.5759 106.35 73.2988 105.34 73.9913L105.215 74.0387C105.067 74.0245 93.7273 66.941 92.7545 66.2578C91.7786 65.5746 90.8615 64.812 90.0129 63.9782C86.5028 60.5126 84.2952 55.9595 83.7575 51.0765C83.5742 49.4033 83.6101 47.8561 83.6115 46.1764L83.6125 40.3562L83.6049 24.9266L97.2859 20.26C99.7752 19.4114 102.474 18.5439 104.929 17.6352ZM86.8983 27.4186L86.8968 40.9687L86.8978 46.1077C86.8963 48.0842 86.8538 49.6275 87.1501 51.6063C87.754 55.414 89.5646 58.9322 92.3197 61.6511C93.0424 62.3715 93.828 63.0271 94.6674 63.6101C95.535 64.2165 105.002 70.1174 105.222 70.1261L112.178 65.7216C113.557 64.8504 115.21 63.8783 116.475 62.899C119.816 60.314 122.08 56.4448 122.96 52.3557C123.5 49.8518 123.341 46.8965 123.337 44.3002L123.337 33.3483C123.336 31.5852 123.257 29.1251 123.362 27.4315L110.345 22.9542C109.585 22.6931 105.502 21.217 105.087 21.2212C103.984 21.5316 102.536 22.0677 101.42 22.4467L94.7791 24.7005L89.7553 26.4106C88.949 26.6843 87.6406 27.0867 86.8983 27.4186Z" fill="black"/>
            <path d="M116.995 28.6704C117.254 28.7222 120.302 31.8334 120.698 32.2351C119.434 33.5632 118.004 34.9379 116.701 36.2391L109.417 43.506L94.5791 58.3097C93.4075 57.3316 92.068 55.7929 90.8577 54.7284C91.5989 54.0261 92.4192 53.1592 93.1554 52.4263L98.2991 47.3109L116.995 28.6704Z" fill="black"/>
          </svg>
        <?php endif; ?>
      </a>
      <button
        class="site-navbar__mobile-close"
        aria-label="<?php echo esc_attr__( 'Close mobile menu', 'blacklineguardianfund-theme' ); ?>"
        data-mobile-close
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>

    <?php
    if ( $mobile_menu_id > 0 ) {
      // Add custom class to mobile menu links.
      $add_mobile_menu_link_class = function ( $atts ) {
        $atts['class'] = 'site-navbar__mobile-link';
        return $atts;
      };
      add_filter( 'nav_menu_link_attributes', $add_mobile_menu_link_class, 10, 1 );

      // Add custom class to mobile menu items.
      $add_mobile_menu_item_class = function ( $classes ) {
        $classes[] = 'site-navbar__mobile-item';
        return $classes;
      };
      add_filter( 'nav_menu_css_class', $add_mobile_menu_item_class, 10, 1 );

      wp_nav_menu(
        array(
			'menu'                 => $mobile_menu_id,
			'container'            => 'nav',
			'container_class'      => 'site-navbar__mobile-nav',
			'container_aria_label' => esc_attr__( 'Mobile navigation', 'blacklineguardianfund-theme' ),
			'menu_class'           => 'site-navbar__mobile-list',
			'items_wrap'           => '<ul class="%2$s">%3$s</ul>',
			'depth'                => 1,
			'fallback_cb'          => false,
        )
      );

      // Remove filters after use.
      remove_filter( 'nav_menu_link_attributes', $add_mobile_menu_link_class, 10 );
      remove_filter( 'nav_menu_css_class', $add_mobile_menu_item_class, 10 );
    }
    ?>

    <?php // ── Mobile donate button ── ?>
    <div class="site-navbar__mobile-footer">
      <a href="<?php echo esc_url( $donate_url ); ?>" class="site-navbar__mobile-donate">
        <?php echo esc_html( $donate_label ); ?>
        <span class="site-navbar__mobile-donate-arrow" aria-hidden="true">
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
            <path d="M2.5 8.5L7.5 3.5M7.5 3.5H3M7.5 3.5V8" stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      </a>
    </div>
  </div>
</header>
