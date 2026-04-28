<?php
/**
 * Server-side render for the Contact Form Section block.
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

$background_image_url = $attributes['backgroundImageUrl'] ?? '';
$form_shortcode       = $attributes['formShortcode'] ?? '[gravityform id="1" title="false" description="false"]';
$footer_note          = $attributes['footerNote'] ?? '';

// Generate unique ID for this block instance.
$block_id = 'contact-form-section-' . wp_unique_id();

// Build inline styles.
$section_style = 'background-color: #F9F5EE;';
if ( $background_image_url ) {
	$section_style .= sprintf(
      ' background-image: url(%s); background-size: cover; background-position: center; background-repeat: no-repeat;',
      esc_url( $background_image_url )
	);
}

$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => 'contact-form-section flex items-center justify-center p-8',
	  'style' => $section_style,
	  'id'    => $block_id,
  )
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="w-full max-w-2xl">
		<!-- Contact Form Card -->
		<div class="contact-form-card rounded-2xl p-8 md:p-10 -mt-20 md:-mt-32 lg:-mt-36 relative z-10">
			
			<!-- Gravity Form -->
			<div class="gform-wrapper">
				<?php
				if ( ! empty( $form_shortcode ) && 0 === strpos( $form_shortcode, '[gravityform' ) ) {
					echo do_shortcode( $form_shortcode );
				}
				?>
			</div>

			<!-- Footer Note -->
			<?php if ( ! empty( $footer_note ) ) : ?>
        <div class="font-inter text-center text-base text-black leading-relaxed mt-6">
					<?php echo wp_kses_post( $footer_note ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
