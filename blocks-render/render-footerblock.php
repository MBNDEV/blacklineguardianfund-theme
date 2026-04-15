<?php
/**
 * Footer block render template.
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Block arguments passed from class-footerblock.php.
$logo_url     = $args['logo_url'] ?? null;
$footer_text  = $args['footer_text'] ?? '';
$footer_email = $args['footer_email'] ?? '';
$social_links = $args['social_links'] ?? array();
?>

<footer class="bg-[#191919] text-gray-100">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <!-- Main Footer Content -->
    <div class="flex flex-col md:flex-row gap-16">
      <!-- Left Column (≈65%): Logo, Description, Email, Social -->
      <div class="flex flex-col space-y-6 md:flex-1">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <?php if ( $logo_url ) : ?>
            <a href="<?php echo esc_url( home_url() ); ?>" class="inline-block">
              <img 
                src="<?php echo esc_url( $logo_url ); ?>" 
                alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                class="h-auto w-auto"
              />
            </a>
          <?php else : ?>
            <a href="<?php echo esc_url( home_url() ); ?>" class="text-xl font-bold text-white hover:text-gray-300 transition-colors">
              <?php bloginfo( 'name' ); ?>
            </a>
          <?php endif; ?>
        </div>

        <!-- Description -->
        <?php if ( $footer_text ) : ?>
          <div class="text-sm leading-relaxed text-gray-400 max-w-md font-light">
            <?php echo wp_kses_post( $footer_text ); ?>
          </div>
        <?php endif; ?>

        <!-- Email with Icon -->
        <?php if ( $footer_email ) : ?>
          <div class="flex items-center gap-1.5">
            <img 
              src="/wp-content/uploads/2026/04/icn-mail.svg" 
              alt="Email"
              class="h-6 w-6 flex-shrink-0"
            />
            <a 
              href="<?php echo esc_url( 'mailto:' . sanitize_email( $footer_email ) ); ?>"
              class="text-sm text-white hover:text-amber-400 transition-colors font-light"
            >
              <?php echo esc_html( $footer_email ); ?>
            </a>
          </div>
        <?php endif; ?>

        <!-- Social Media Icons -->
        <?php if ( ! empty( $social_links ) ) : ?>
          <div class="flex space-x-3 pt-2">
            <?php foreach ( $social_links as $key => $social_link ) : ?>
              <a 
                href="<?php echo esc_url( $social_link['url'] ); ?>" 
                target="_blank"
                rel="noopener noreferrer"
                title="<?php echo esc_attr( $social_link['label'] ); ?>"
                class="flex-shrink-0 text-amber-500 hover:text-amber-400 transition-colors"
              >
                <img 
                  src="<?php echo esc_url( $social_link['icon_url'] ); ?>" 
                  alt="<?php echo esc_attr( $social_link['label'] ); ?>"
                  class="h-6 w-6"
                />
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right Column (≈35%): Navigation Links -->
      <div class="md:flex-1">
        <?php if ( has_nav_menu( 'footer-menu-1' ) || has_nav_menu( 'footer-menu-2' ) ) : ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 max-w-md md:ml-auto">
            <!-- Menu Column 1 -->
            <?php if ( has_nav_menu( 'footer-menu-1' ) ) : ?>
              <nav>
                <?php
                wp_nav_menu(
                  array(
					  'theme_location' => 'footer-menu-1',
					  'menu_class'     => 'space-y-2',
					  'container'      => false,
					  'fallback_cb'    => false,
					  'depth'          => 1,
					  'link_before'    => '<span class="text-sm text-white hover:text-amber-400 transition-colors font-normal">',
					  'link_after'     => '</span>',
                  )
                );
                ?>
              </nav>
            <?php endif; ?>

            <!-- Menu Column 2 -->
            <?php if ( has_nav_menu( 'footer-menu-2' ) ) : ?>
              <nav>
                <?php
                wp_nav_menu(
                  array(
					  'theme_location' => 'footer-menu-2',
					  'menu_class'     => 'space-y-2',
					  'container'      => false,
					  'fallback_cb'    => false,
					  'depth'          => 1,
					  'link_before'    => '<span class="text-sm text-white hover:text-amber-400 transition-colors font-normal">',
					  'link_after'     => '</span>',
                  )
                );
                ?>
              </nav>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-[#B89352] my-8"></div>

    <!-- Footer Bottom -->
    <div class="flex flex-col items-center md:flex-row md:justify-between md:items-end gap-8">
      <!-- Copyright & Legal Disclaimer -->
      <div class="flex flex-col gap-1.5">
        <p class="text-xs text-white font-light">
          <?php
          printf(
            /* translators: %d: Current year */
            esc_html__( '© %d Blackline Guardian Fund. All rights reserved.', 'blacklineguardianfund-theme' ),
            (int) gmdate( 'Y' )
          );
          ?>
        </p>
        <p class="text-xs text-gray-400 font-light max-w-lg">
          <?php esc_html_e( 'The Blackline Guardian Fund is a recognized 501(c)(3) tax-exempt nonprofit organization. Donations are tax-deductible to the fullest extent permitted by law.', 'blacklineguardianfund-theme' ); ?>
        </p>
      </div>

      <!-- Legal Links -->
      <?php if ( has_nav_menu( 'footer-legal' ) ) : ?>
        <nav class="flex flex-wrap gap-6 md:whitespace-nowrap">
          <?php
          wp_nav_menu(
            array(
				'theme_location' => 'footer-legal',
				'menu_class'     => 'flex gap-6',
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 1,
				'link_before'    => '<span class="text-xs text-white hover:text-amber-400 transition-colors font-light">',
				'link_after'     => '</span>',
            )
          );
          ?>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</footer>
