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
$overlay_breakpoint    = $attributes['overlayBreakpoint'] ?? 'always';
$bg_position_x_mobile  = absint( $attributes['bgPositionXMobile'] ?? 50 );
$bg_position_x_tablet  = absint( $attributes['bgPositionXTablet'] ?? 50 );
$subheading            = $attributes['subheading'] ?? '';
$heading               = $attributes['heading'] ?? '';
$description           = $attributes['description'] ?? '';
$vertical_position     = $attributes['verticalPosition'] ?? 'bottom';
$primary_button_text   = sanitize_text_field( $attributes['primaryButtonText'] ?? 'Donate Now' );
$primary_button_url    = esc_url( $attributes['primaryButtonUrl'] ?? '#donate' );
$secondary_button_text = sanitize_text_field( $attributes['secondaryButtonText'] ?? 'Learn More' );
$secondary_button_url  = esc_url( $attributes['secondaryButtonUrl'] ?? '#learn-more' );
$max_width             = sanitize_html_class( $attributes['maxWidth'] ?? 'max-w-3xl' );

// Generate unique ID for this block instance
$block_id = 'hero-banner-' . wp_unique_id();

$section_style = '';
if ( $background_image ) {
	$section_style = sprintf(
      'background-image: url(%s); --bg-pos-x-mobile: %s%%; --bg-pos-x-tablet: %s%%;',
      esc_url( $background_image ),
      $bg_position_x_mobile,
      $bg_position_x_tablet
	);
}

$overlay_style = sprintf( 'opacity: %s;', $overlay_opacity / 100 );

// Map breakpoint to Tailwind responsive classes.
$overlay_class_map = array(
	'always' => 'block',
	'sm'     => 'block sm:hidden',
	'md'     => 'block md:hidden',
	'lg'     => 'block lg:hidden',
	'xl'     => 'block xl:hidden',
	'2xl'    => 'block 2xl:hidden',
);
$overlay_class     = $overlay_class_map[ $overlay_breakpoint ] ?? 'block';

// Determine vertical alignment class.
$vertical_align_class  = ( 'center' === $vertical_position ) ? 'items-center' : 'items-end';
$show_primary_button   = ! empty( $primary_button_text ) && ! empty( $primary_button_url );
$show_secondary_button = ! empty( $secondary_button_text ) && ! empty( $secondary_button_url );

$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => 'hero-banner relative w-full min-h-screen flex ' . $vertical_align_class . ' py-20 md:py-32 justify-center overflow-hidden bg-cover bg-center bg-no-repeat',
	  'style' => $section_style,
	  'id'    => $block_id,
  )
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<!-- Dark Overlay -->
	<div class="absolute inset-0 bg-black z-1 <?php echo esc_attr( $overlay_class ); ?>" style="<?php echo esc_attr( $overlay_style ); ?>"></div>

	<!-- Content -->
	<div class="relative z-2 w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
		<div class="<?php echo esc_attr( $max_width ); ?>">
			
			<?php if ( $subheading ) : ?>
				<!-- Subheading (gold) -->
				<p class="font-sofia font-bold text-2xl leading-snug tracking-tight text-gold uppercase">
					<?php echo wp_kses_post( $subheading ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<!-- Main heading (white, large) -->
				<?php $heading_margin = ! empty( $description ) ? 'mb-5' : 'mb-8 md:mb-10'; ?>
				<h1 class="font-sofia font-bold text-5xl sm:text-6xl md:text-7xl leading-none tracking-[-0.74px] text-white uppercase <?php echo esc_attr( $heading_margin ); ?>">
					<?php echo wp_kses_post( $heading ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<!-- Description paragraph (optional) -->
				<?php $description_margin = ( $show_primary_button || $show_secondary_button ) ? 'mb-8 md:mb-10' : ''; ?>
				<p class="text-white font-inter text-lg font-light leading-normal <?php echo esc_attr( $description_margin ); ?>">
					<?php echo wp_kses_post( $description ); ?>
				</p>
			<?php endif; ?>

			<!-- CTA buttons -->
			<?php
			if ( $show_primary_button || $show_secondary_button ) :
              ?>
			<div class="flex flex-col sm:flex-row gap-4 md:gap-6">
				
				<?php if ( $show_primary_button ) : ?>
				<!-- Donate Now (gold gradient button with arrow) -->
				<a href="<?php echo $primary_button_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_url'd ?>" class="inline-flex font-inter items-center justify-center gap-2 h-11 px-5 rounded-full font-bold text-base leading-none uppercase tracking-tight transition-all duration-300 bg-gradient-to-b from-gold-light to-gold text-gold-dark shadow-md hover:shadow-lg hover:-translate-y-0.5 active:shadow-sm active:translate-y-0 no-underline">
					<span><?php echo esc_html( $primary_button_text ); ?></span>
					<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gold shrink-0">
						<svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 9 9" fill="none" aria-hidden="true" focusable="false">
							<path d="M0.000102425 8.15588L7.13312 1.02286L2.3107 1.02357L2.31105 -1.78562e-07L8.35508 -1.36862e-08L8.85496 0.0113766L8.86634 0.511254L8.86634 6.55528L7.84383 6.55528L7.84418 1.73392L0.711165 8.86695L0.000102425 8.15588Z" fill="white"/>
						</svg>
					</span>
				</a>
				<?php endif; ?>
				
				<?php if ( $show_secondary_button ) : ?>
				<!-- Learn More (white outline button) -->
				<a href="<?php echo $secondary_button_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_url'd ?>" class="inline-flex font-inter items-center justify-center gap-2 h-11 px-5 rounded-full font-inter font-semibold text-base leading-normal uppercase transition-all duration-300 bg-cream-light text-gold-dark border border-white hover:bg-orange-100 hover:shadow-md hover:-translate-y-0.5 active:bg-cream-light active:translate-y-0 no-underline">
					<?php echo esc_html( $secondary_button_text ); ?>
				</a>
				<?php endif; ?>
				
			</div>
			<?php endif; ?>

		</div>
	</div>
</section>
