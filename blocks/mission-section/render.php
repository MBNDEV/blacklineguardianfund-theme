<?php
/**
 * Server-side render for the Mission Section block.
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

$background_image = $attributes['backgroundImageUrl'] ?? '';
$top_heading      = $attributes['topHeading'] ?? '';
$left_text        = $attributes['leftText'] ?? '';
$left_image_url   = $attributes['leftImageUrl'] ?? '';
$right_text       = $attributes['rightText'] ?? '';
$right_image_url  = $attributes['rightImageUrl'] ?? '';
$center_icon_url  = $attributes['centerIconUrl'] ?? '';
$bottom_text      = $attributes['bottomText'] ?? '';

// Build background style
$section_style = '';
if ( $background_image ) {
	$section_style = sprintf(
		'background: url(%s) lightgray -653.281px 0px / 238.91%% 100%% no-repeat; background-size: cover; background-position: 100%% 30%%;',
		esc_url( $background_image )
	);
}

$wrapper_class = 'mission-section relative w-full py-20 md:py-32 lg:pt-44 lg:pb-20 overflow-hidden';
if ( ! $background_image ) {
	$wrapper_class .= ' bg-cream';
}

$wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => $wrapper_class,
		'style' => $section_style,
	)
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container mx-auto px-6 md:px-12 lg:px-16 max-w-8xl">
		
		<?php if ( $top_heading ) : ?>
			<!-- Top Heading -->
			<div class="text-center mb-16 md:mb-24 lg:mb-32">
				<h2 class="text-3xl md:text-[40px] font-bold font-sofia leading-tight tracking-tight text-mission-text">
					<?php echo wp_kses_post( $top_heading ); ?>
				</h2>
			</div>
		<?php endif; ?>

		<!-- Content Grid with Images, Shield, and Text -->
		<div class="relative grid grid-cols-1 lg:grid-cols-2 gap-9 lg:gap-14 items-center">
			
			<!-- Left Column -->
			<div class="space-y-8 lg:space-y-12">
				<?php if ( $left_text ) : ?>
					<!-- Left Text Block -->
          <div class="text-center lg:text-left max-w-none lg:max-w-xl lg:mr-16">
						<p class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-[-0.4px] font-bold font-sofia">
							<?php echo wp_kses_post( $left_text ); ?>
						</p>
					</div>
				<?php endif; ?>
				
				<?php if ( $left_image_url ) : ?>
					<!-- Left Image -->
					<div class="relative w-full">
						<img 
							src="<?php echo esc_url( $left_image_url ); ?>" 
							alt="" 
							class="w-full h-auto rounded-2xl shadow-xl object-cover"
							loading="lazy"
						/>
					</div>
				<?php endif; ?>
			</div>

			<!-- Right Column -->
			<div class="space-y-8 lg:space-y-12 relative">
				<?php if ( $right_image_url ) : ?>
					<!-- Right Image -->
					<div class="relative w-full ml-auto lg:mb-9 xl:mb-0">
						<img 
							src="<?php echo esc_url( $right_image_url ); ?>" 
							alt="" 
							class="w-full h-auto rounded-2xl shadow-xl object-cover"
							loading="lazy"
						/>
					</div>
				<?php endif; ?>
				
				<?php if ( $right_text ) : ?>
					<!-- Right Text Block -->
					<div class="text-center lg:text-left max-w-none lg:max-w-xl ml-auto lg:ml-16">
						<p class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-[-0.4px] font-bold font-sofia">
							<?php echo wp_kses_post( $right_text ); ?>
						</p>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $center_icon_url ) : ?>
				<!-- Center Shield Icon (Absolutely Positioned) -->
				<div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 block pointer-events-none" aria-hidden="true">
					<div class="relative w-48 h-48 lg:w-64 lg:h-64">
						<img 
							src="<?php echo esc_url( $center_icon_url ); ?>" 
							alt="" 
							class="w-full h-full object-contain drop-shadow-2xl opacity-90"
							loading="lazy"
						/>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $bottom_text ) : ?>
			<!-- Bottom Text -->
			<div class="text-center mt-16 md:mt-32 space-y-6">
				<div class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-[-0.4px] font-bold font-sofia max-w-4xl mx-auto">
					<?php echo wp_kses_post( $bottom_text ); ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
