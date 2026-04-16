<?php
/**
 * Server-side render for the Hero Section block.
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

$eyebrow          = $attributes['eyebrow'] ?? '';
$heading          = $attributes['heading'] ?? '';
$description      = $attributes['description'] ?? '';
$background_image = $attributes['backgroundImageUrl'] ?? '';
$overlay_opacity  = absint( $attributes['overlayOpacity'] ?? 60 );

$section_style = '';
if ( $background_image ) {
	$section_style = sprintf(
      'background-image: url(%s); background-size: cover; background-position: center;',
      esc_url( $background_image )
	);
}

$overlay_style = sprintf( 'opacity: %s;', $overlay_opacity / 100 );

$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => 'hero-section relative w-full flex items-center',
	  'style' => $section_style,
  )
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<!-- Dark Overlay -->
	<div class="absolute inset-0 bg-black pointer-events-none" style="<?php echo esc_attr( $overlay_style ); ?>"></div>

	<!-- Content -->
	<div class="relative z-10 w-full max-w-7xl mx-auto px-4 py-20 lg:py-32">
		<div class="max-w-3xl">
			<?php if ( $eyebrow ) : ?>
				<p class="text-amber-600 text-sm font-semibold tracking-wider uppercase mb-4">
					<?php echo wp_kses_post( $eyebrow ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h1 class="text-white text-4xl lg:text-6xl font-bold leading-tight mb-6">
					<?php echo wp_kses_post( $heading ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<p class="text-white text-lg leading-relaxed max-w-2xl">
					<?php echo wp_kses_post( $description ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
