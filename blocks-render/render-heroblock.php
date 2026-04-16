<?php
/**
 * Hero block render template.
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Block arguments passed from class-heroblock.php.
$background_image_url   = $args['background_image_url'] ?? null;
$subtitle               = $args['subtitle'] ?? '';
$hero_title             = $args['title'] ?? '';
$primary_cta_text       = $args['primary_cta_text'] ?? '';
$primary_cta_url        = $args['primary_cta_url'] ?? null;
$primary_cta_target     = $args['primary_cta_target'] ?? '_self';
$primary_cta_icon_url   = $args['primary_cta_icon_url'] ?? null;
$secondary_cta_text     = $args['secondary_cta_text'] ?? '';
$secondary_cta_url      = $args['secondary_cta_url'] ?? null;
$secondary_cta_target   = $args['secondary_cta_target'] ?? '_self';
$secondary_cta_icon_url = $args['secondary_cta_icon_url'] ?? null;
$fullwidth_section      = $args['fullwidth_section'] ?? false;

// Determine section wrapper classes.
$section_class = $fullwidth_section ? 'w-full' : 'container mx-auto px-4 sm:px-6 lg:px-8';
?>

<section class="relative min-h-screen flex items-end justify-center overflow-hidden bg-gray-900">
  <!-- Background Image Overlay -->
  <?php if ( $background_image_url ) : ?>
    <div class="absolute inset-0 z-0">
      <img
        src="<?php echo esc_url( $background_image_url ); ?>"
        alt="<?php esc_attr_e( 'Hero background', 'blacklineguardianfund-theme' ); ?>"
        class="w-full h-full object-cover"
      />
      <!-- <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/50 to-black/60"></div> -->
    </div>
  <?php else : ?>
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black"></div>
  <?php endif; ?>

  <!-- Content -->
  <div class="<?php echo esc_attr( $section_class ); ?> relative z-10 py-16 md:py-24">
    <div class="flex flex-col items-start max-w-3xl">
      <!-- Subtitle/Tagline -->
      <?php if ( $subtitle ) : ?>
        <div class="text-sm md:text-2xl font-bold text-brand-gold uppercase tracking-wide">
          <?php echo esc_html( $subtitle ); ?>
        </div>
      <?php endif; ?>

      <!-- Title (H1) -->
      <?php if ( $hero_title ) : ?>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-tight uppercase tracking-tight">
          <?php echo wp_kses_post( nl2br( $hero_title ) ); ?>
        </h1>
      <?php endif; ?>

      <!-- CTA Buttons -->
      <?php if ( $primary_cta_url || $secondary_cta_url ) : ?>
        <div class="flex flex-col sm:flex-row gap-4 pt-4 md:pt-8">
          <!-- Primary CTA Button -->
          <?php
          if ( $primary_cta_url ) {
            echo wp_kses_post(
              get_global_button(
                array(
					'text'          => $primary_cta_text,
					'url'           => $primary_cta_url,
					'target'        => $primary_cta_target,
					'style'         => 'primary',
					'icon_url'      => $primary_cta_icon_url,
					'icon_position' => 'after',
                )
              )
            );
          }
          ?>

          <!-- Secondary CTA Button -->
          <?php
          if ( $secondary_cta_url ) {
            echo wp_kses_post(
              get_global_button(
                array(
					'text'          => $secondary_cta_text,
					'url'           => $secondary_cta_url,
					'target'        => $secondary_cta_target,
					'style'         => 'secondary',
					'icon_url'      => $secondary_cta_icon_url,
					'icon_position' => 'after',
                )
              )
            );
          }
          ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
