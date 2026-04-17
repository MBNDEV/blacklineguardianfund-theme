<?php
/**
 * Server-side render for the Hero Banner block.
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

$background_image      = $attributes['backgroundImageUrl'] ?? '';
$overlay_opacity       = absint( $attributes['overlayOpacity'] ?? 40 );
$subheading            = $attributes['subheading'] ?? '';
$heading               = $attributes['heading'] ?? '';
$primary_button_text   = sanitize_text_field( $attributes['primaryButtonText'] ?? 'Donate Now' );
$primary_button_url    = esc_url( $attributes['primaryButtonUrl'] ?? '#donate' );
$secondary_button_text = sanitize_text_field( $attributes['secondaryButtonText'] ?? 'Learn More' );
$secondary_button_url  = esc_url( $attributes['secondaryButtonUrl'] ?? '#learn-more' );

$section_style = '';
if ( $background_image ) {
	$section_style = sprintf(
      'background-image: url(%s);',
      esc_url( $background_image )
	);
}

$overlay_style = sprintf( 'opacity: %s;', $overlay_opacity / 100 );

$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => 'hero-banner relative w-full min-h-screen flex items-end py-20 md:py-32 justify-center overflow-hidden bg-cover bg-center bg-no-repeat',
	  'style' => $section_style,
  )
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<!-- Dark Overlay -->
	<div class="absolute inset-0 bg-black z-10" style="<?php echo esc_attr( $overlay_style ); ?>"></div>

	<!-- Content -->
	<div class="relative z-20 w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
		<div class="max-w-2xl">
			
			<?php if ( $subheading ) : ?>
				<!-- Subheading (gold) -->
				<p class="font-sofia font-bold text-2xl leading-snug tracking-tight text-gold uppercase">
					<?php echo wp_kses_post( $subheading ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<!-- Main heading (white, large) -->
				<h1 class="font-sofia font-bold text-7xl leading-none tracking-tight text-white uppercase mb-8 md:mb-10">
					<?php echo wp_kses_post( $heading ); ?>
				</h1>
			<?php endif; ?>

			<!-- CTA buttons -->
			<div class="flex flex-col sm:flex-row gap-4 md:gap-6">
				
				<!-- Donate Now (gold gradient button with arrow) -->
				<a href="<?php echo $primary_button_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_url'd ?>" class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-full font-bold text-base leading-none uppercase tracking-tight transition-all duration-300 bg-gradient-to-b from-gold-light to-gold text-gold-dark shadow-md hover:shadow-lg hover:-translate-y-0.5 active:shadow-sm active:translate-y-0 no-underline">
					<span><?php echo esc_html( $primary_button_text ); ?></span>
					<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gold shrink-0">
						<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
							<path d="M1 9L9 1M9 1H1M9 1V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</a>
				
				<!-- Learn More (white outline button) -->
				<a href="<?php echo $secondary_button_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_url'd ?>" class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-full font-inter font-semibold text-base leading-normal uppercase transition-all duration-300 bg-cream-light text-gold-dark border border-white hover:bg-orange-100 hover:shadow-md hover:-translate-y-0.5 active:bg-cream-light active:translate-y-0 no-underline">
					<?php echo esc_html( $secondary_button_text ); ?>
				</a>
				
			</div>

		</div>
	</div>
</section>
