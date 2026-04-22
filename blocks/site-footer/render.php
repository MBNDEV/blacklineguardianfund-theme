<?php
/**
 * Site Footer Block - Server-side rendering
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 *
 * @package BlacklineGuardianFund
 */

$logo_url        = $attributes['logoUrl'] ?? '';
$footer_text     = $attributes['footerText'] ?? '';
$email           = $attributes['email'] ?? '';
$email_url       = $attributes['emailUrl'] ?? '';
$show_email_icon = $attributes['showEmailIcon'] ?? true;
$email_icon_url  = $attributes['emailIconUrl'] ?? '';
$social_links    = $attributes['socialLinks'] ?? array();
$menu_id_1       = $attributes['menuId1'] ?? 0;
$menu_id_2       = $attributes['menuId2'] ?? 0;
$legal_info      = $attributes['legalInfo'] ?? '';
$legal_links     = $attributes['legalLinks'] ?? array();

$wrapper_attributes = get_block_wrapper_attributes(
  array(
	  'class' => 'w-full bg-footer-bg text-white',
  )
);

$social_icons = array(
	'facebook'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.413c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.971h-1.513c-1.491 0-1.956.93-1.956 1.886v2.265h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>',
	'instagram' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
	'x'         => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
	'linkedin'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
	'youtube'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
  
  <!-- Main Footer Content -->
  <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 py-12 md:py-16">
    
    <!-- Grid Layout: Logo/Info + Navigation -->
    <div class="grid grid-cols-1 lg:grid-cols-[60%_40%] gap-12 lg:gap-16">
      
      <!-- Left Column: Logo, Mission, Email, Social -->
      <div class="space-y-6">
        
        <!-- Logo -->
        <?php if ( ! empty( $logo_url ) ) : ?>
          <div class="mb-8">
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="max-width: 150px; height: auto;" />
          </div>
        <?php endif; ?>
        
        <!-- Mission Statement -->
        <?php if ( ! empty( $footer_text ) ) : ?>
          <p class="font-inter text-base leading-relaxed text-paragraph-gray max-w-md">
            <?php echo wp_kses_post( $footer_text ); ?>
          </p>
        <?php endif; ?>
        
        <!-- Email -->
        <?php if ( ! empty( $email ) ) : ?>
          <div class="flex items-center gap-3">
            <?php if ( $show_email_icon ) : ?>
              <?php if ( ! empty( $email_icon_url ) ) : ?>
                <img src="<?php echo esc_url( $email_icon_url ); ?>" alt="<?php echo esc_attr__( 'Email icon', 'mbn-theme' ); ?>" style="width: 20px; height: auto;" />
              <?php else : ?>
                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M2 0C0.9 0 0.00999999 0.9 0.00999999 2L0 14C0 15.1 0.9 16 2 16H18C19.1 16 20 15.1 20 14V2C20 0.9 19.1 0 18 0H2ZM18 4L10 9L2 4V2L10 7L18 2V4Z" fill="#B89352"/>
                </svg>
              <?php endif; ?>
            <?php endif; ?>
            <a href="<?php echo esc_url( $email_url ); ?>" class="font-inter text-base text-white hover:text-gold transition-colors no-underline">
              <?php echo esc_html( $email ); ?>
            </a>
          </div>
        <?php endif; ?>
        
        <!-- Social Media Icons -->
        <?php if ( ! empty( $social_links ) ) : ?>
          <div class="flex items-center gap-4 pt-4">
            <?php foreach ( $social_links as $social_link ) : ?>
              <?php
              $platform        = $social_link['platform'] ?? '';
              $url             = $social_link['url'] ?? '#';
              $open_in_new_tab = $social_link['openInNewTab'] ?? true;
              ?>
              <?php if ( ! empty( $platform ) && isset( $social_icons[ $platform ] ) ) : ?>
                <a 
                  href="<?php echo esc_url( $url ); ?>" 
                  class="text-gold hover:text-gold-light transition-colors" 
                  aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>"
                  <?php echo $open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                >
                  <?php echo $social_icons[ $platform ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        
      </div>
      
      <!-- Right Column: Navigation Links -->
      <div class="grid grid-cols-2 gap-8 md:gap-12">
        
        <!-- Column 1 -->
        <div class="space-y-4">
          <?php
          if ( $menu_id_1 > 0 ) {
            wp_nav_menu(
              array(
				  'menu'        => $menu_id_1,
				  'container'   => false,
				  'items_wrap'  => '%3$s',
				  'link_before' => '',
				  'link_after'  => '',
				  'depth'       => 1,
				  'walker'      => new class() extends Walker_Nav_Menu {
					/**
					 * Start element output.
					 *
					 * @param string   $output Output HTML.
					 * @param WP_Post  $item   Menu item.
					 * @param int      $depth  Depth (unused, required for signature).
					 * @param stdClass $args   Arguments (unused, required for signature).
					 * @param int      $id     Item ID (unused, required for signature).
					 *
					 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
					 */
					public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
					  $output .= '<a href="' . esc_url( $item->url ) . '" class="block font-inter text-base text-white hover:text-gold transition-colors no-underline">';
					  $output .= esc_html( $item->title );
					  $output .= '</a>';
					}
				  },
              )
            );
          }
          ?>
        </div>
        
        <!-- Column 2 -->
        <div class="space-y-4">
          <?php
          if ( $menu_id_2 > 0 ) {
            wp_nav_menu(
              array(
				  'menu'        => $menu_id_2,
				  'container'   => false,
				  'items_wrap'  => '%3$s',
				  'link_before' => '',
				  'link_after'  => '',
				  'depth'       => 1,
				  'walker'      => new class() extends Walker_Nav_Menu {
					/**
					 * Start element output.
					 *
					 * @param string   $output Output HTML.
					 * @param WP_Post  $item   Menu item.
					 * @param int      $depth  Depth (unused, required for signature).
					 * @param stdClass $args   Arguments (unused, required for signature).
					 * @param int      $id     Item ID (unused, required for signature).
					 *
					 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
					 */
					public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
					  $output .= '<a href="' . esc_url( $item->url ) . '" class="block font-inter text-base text-white hover:text-gold transition-colors no-underline">';
					  $output .= esc_html( $item->title );
					  $output .= '</a>';
					}
				  },
              )
            );
          }
          ?>
        </div>
        
      </div>
      
    </div>
    
  </div>
  
  <!-- Divider -->
  <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
    <div class="h-px bg-gold"></div>
  </div>
  
  <!-- Bottom Legal Section -->
  <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 py-8">
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      
      <!-- Left: Copyright & Legal Info -->
      <div class="space-y-2">
        <p class="font-inter text-sm text-white m-0">
          &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php echo esc_html__( 'All rights reserved.', 'mbn-theme' ); ?>
        </p>
        <?php if ( ! empty( $legal_info ) ) : ?>
          <p class="font-inter text-sm text-paragraph-gray m-0">
            <?php echo wp_kses_post( $legal_info ); ?>
          </p>
        <?php endif; ?>
      </div>
      
      <!-- Right: Legal Links -->
      <?php if ( ! empty( $legal_links ) ) : ?>
        <div class="flex flex-wrap items-center gap-6">
          <?php foreach ( $legal_links as $legal_link ) : ?>
            <?php
            $link_text = $legal_link['text'] ?? '';
            $link_url  = $legal_link['url'] ?? '#';
            ?>
            <?php if ( ! empty( $link_text ) ) : ?>
              <a href="<?php echo esc_url( $link_url ); ?>" class="font-inter text-sm text-white hover:text-gold transition-colors no-underline">
                <?php echo esc_html( $link_text ); ?>
              </a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      
    </div>
    
  </div>
  
</section>
