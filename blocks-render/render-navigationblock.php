<?php
/**
 * Navigation block render template.
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Block arguments passed from class-navigationblock.php.
$logo_url     = $args['logo_url'] ?? null;
$menu_id      = $args['menu_id'] ?? null;
$cta_text     = $args['cta_text'] ?? __( 'DONATE NOW', 'blacklineguardianfund-theme' );
$cta_url      = $args['cta_url'] ?? null;
$cta_target   = $args['cta_target'] ?? '_self';
$cta_icon_url = $args['cta_icon_url'] ?? null;
?>

<nav class="sticky top-0 z-50 bg-amber-50 border-b border-gray-200">
  <div class="container flex items-center justify-between mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <!-- Logo -->
    <div class="flex-shrink-0">
      <?php if ( $logo_url ) : ?>
        <a href="<?php echo esc_url( home_url() ); ?>" class="inline-flex items-center hover:opacity-80 transition-opacity">
          <img 
            src="<?php echo esc_url( $logo_url ); ?>" 
            alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
            class="h-auto w-auto"
          />
        </a>
      <?php else : ?>
        <a href="<?php echo esc_url( home_url() ); ?>" class="text-lg font-bold text-gray-900 hover:text-gray-700 transition-colors">
          <?php bloginfo( 'name' ); ?>
        </a>
      <?php endif; ?>
    </div>

    <!-- Navigation Menu -->
    <div class="hidden md:flex flex-1 justify-center">
      <?php
      if ( $menu_id ) {
        wp_nav_menu(
          array(
			  'menu'        => (int) $menu_id,
			  'menu_class'  => 'flex gap-8 list-none m-0 p-0',
			  'container'   => false,
			  'fallback_cb' => false,
			  'depth'       => 2,
			  'link_before' => '<span class="text-sm font-medium text-gray-900 hover:text-amber-600 transition-colors relative group">',
			  'link_after'  => '<span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber-600 group-hover:w-full transition-all duration-300"></span></span>',
          )
        );
      }
      ?>
    </div>

    <!-- CTA Button -->
    <div class="flex-shrink-0">
      <?php
      if ( $cta_url ) {
        the_global_button(
          array(
			  'text'          => $cta_text,
			  'url'           => $cta_url,
			  'target'        => $cta_target,
			  'style'         => 'primary',
			  'icon_url'      => $cta_icon_url,
			  'icon_position' => 'after',
          )
        );
      }
      ?>
    </div>

    <!-- Mobile Menu Button -->
    <button class="md:hidden ml-4 inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-amber-600" aria-expanded="false" aria-controls="mobile-menu" id="mobile-menu-button">
      <span class="sr-only"><?php esc_html_e( 'Open main menu', 'blacklineguardianfund-theme' ); ?></span>
      <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile Menu (hidden on desktop) -->
  <div class="hidden md:hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
      <?php
      if ( $menu_id ) {
        wp_nav_menu(
          array(
			  'menu'        => (int) $menu_id,
			  'menu_class'  => 'flex flex-col space-y-1 list-none m-0 p-0',
			  'container'   => false,
			  'fallback_cb' => false,
			  'depth'       => 1,
			  'link_before' => '<span class="text-gray-700 hover:text-amber-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors">',
			  'link_after'  => '</span>',
          )
        );
      }
      ?>
    </div>
  </div>
</nav>
