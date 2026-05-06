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

$background_image      = $attributes['backgroundImageUrl'] ?? '';
$top_heading           = $attributes['topHeading'] ?? '';
$top_heading_max_width = $attributes['topHeadingMaxWidth'] ?? 'max-w-4xl';
$top_heading_alignment = $attributes['topHeadingAlignment'] ?? 'center';
$middle_layout         = $attributes['middleContentLayout'] ?? 'grid';

// Grid layout attributes
$left_text       = $attributes['leftText'] ?? '';
$left_image_url  = $attributes['leftImageUrl'] ?? '';
$left_image_alt  = $attributes['leftImageAlt'] ?? '';
$right_text      = $attributes['rightText'] ?? '';
$right_image_url = $attributes['rightImageUrl'] ?? '';
$right_image_alt = $attributes['rightImageAlt'] ?? '';
$center_icon_url = $attributes['centerIconUrl'] ?? '';
$center_icon_alt = $attributes['centerIconAlt'] ?? '';

// Image Only layout attributes
$middle_image_url = $attributes['middleImageUrl'] ?? '';
$middle_image_alt = $attributes['middleImageAlt'] ?? '';

// Image and Text repeater attributes
$middle_items = $attributes['middleItems'] ?? array();

$bottom_text           = $attributes['bottomText'] ?? '';
$bottom_text_max_width = $attributes['bottomTextMaxWidth'] ?? 'max-w-4xl';
$bottom_text_alignment = $attributes['bottomTextAlignment'] ?? 'center';

// Map alignment values to explicit Tailwind classes (prevents purging)
$alignment_classes       = array(
	'left'    => 'text-left',
	'center'  => 'text-center',
	'right'   => 'text-right',
	'justify' => 'text-justify',
);
$top_heading_align_class = $alignment_classes[ $top_heading_alignment ] ?? 'text-center';
$bottom_text_align_class = $alignment_classes[ $bottom_text_alignment ] ?? 'text-center';

// Map max-width values to explicit Tailwind classes (prevents purging)
$max_width_classes           = array(
	'max-w-none' => 'max-w-none',
	'max-w-xs'   => 'max-w-xs',
	'max-w-sm'   => 'max-w-sm',
	'max-w-md'   => 'max-w-md',
	'max-w-lg'   => 'max-w-lg',
	'max-w-xl'   => 'max-w-xl',
	'max-w-2xl'  => 'max-w-2xl',
	'max-w-3xl'  => 'max-w-3xl',
	'max-w-4xl'  => 'max-w-4xl',
	'max-w-5xl'  => 'max-w-5xl',
	'max-w-6xl'  => 'max-w-6xl',
	'max-w-7xl'  => 'max-w-7xl',
);
$top_heading_max_width_class = $max_width_classes[ $top_heading_max_width ] ?? 'max-w-4xl';
$bottom_text_max_width_class = $max_width_classes[ $bottom_text_max_width ] ?? 'max-w-4xl';

// Build background style
$section_style = '';
if ( $background_image ) {
	$section_style = sprintf(
      'background: url(%s) lightgray -653.281px 0px / 238.91%% 100%% no-repeat; background-size: cover; background-position: 100%% 30%%;',
      esc_url( $background_image )
	);
}

$wrapper_class = 'mission-section relative w-full py-20 md:pt-32 md:pb-20 lg:pt-32 lg:pb-20 overflow-hidden';
if ( ! $background_image ) {
	$wrapper_class .= ' bg-cream';
}

$anim_attr = mbn_get_animation_attrs( $attributes );

$wrapper_attrs = get_block_wrapper_attributes(
  array_merge(
    array(
	    'class' => $wrapper_class,
	    'style' => $section_style,
    ),
    $anim_attr
  )
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container mx-auto px-6 md:px-12 lg:px-16 max-w-7xl">
		
		<?php if ( $top_heading ) : ?>
			<!-- Top Heading -->
			<div class="mb-16 md:mb-24 <?php echo esc_attr( $top_heading_align_class ); ?>">
				<p class="text-3xl md:text-[40px] font-bold font-sofia leading-tight tracking-tight text-mission-text <?php echo esc_attr( $top_heading_max_width_class ); ?> mx-auto">
					<?php echo wp_kses_post( $top_heading ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( 'grid' === $middle_layout ) : ?>
			<!-- Content Grid with Images, Shield, and Text -->
			<div class="relative grid grid-cols-1 lg:grid-cols-2 gap-9 lg:gap-14 items-center">
				
				<!-- Left Column -->
				<div class="space-y-8 lg:space-y-12">
					<?php if ( $left_text ) : ?>
						<!-- Left Text Block -->
						<div class="text-center lg:text-left max-w-none lg:max-w-xl lg:mr-8 xl:mr-14">
							<p class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-mission font-bold font-sofia">
								<?php echo wp_kses_post( $left_text ); ?>
							</p>
						</div>
					<?php endif; ?>
					
					<?php if ( $left_image_url ) : ?>
						<!-- Left Image -->
						<div class="relative w-full">
							<img 
								src="<?php echo esc_url( $left_image_url ); ?>" 
								alt="<?php echo esc_attr( $left_image_alt ); ?>" 
								class="w-full h-auto lg:h-[400px] rounded-2xl shadow-xl object-cover"
								loading="lazy"
							/>
						</div>
					<?php endif; ?>
				</div>

				<!-- Right Column -->
				<div class="space-y-8 lg:space-y-0 xl:space-y-0 relative">
					<?php if ( $right_image_url ) : ?>
						<!-- Right Image -->
						<div class="relative w-full ml-auto xl:mb-0">
							<img 
								src="<?php echo esc_url( $right_image_url ); ?>" 
								alt="<?php echo esc_attr( $right_image_alt ); ?>" 
								class="w-full h-auto lg:h-[400px] rounded-2xl shadow-xl object-cover"
								loading="lazy"
							/>
						</div>
					<?php endif; ?>
					
					<?php if ( $right_text ) : ?>
						<!-- Right Text Block -->
						<div class="text-center lg:text-left max-w-none lg:-mt-5 lg:max-w-xl ml-auto lg:ml-11 xl:ml-16">
							<p class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-mission font-bold font-sofia">
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
								alt="<?php echo esc_attr( $center_icon_alt ); ?>" 
								class="w-full h-full object-contain drop-shadow-2xl opacity-90"
								loading="lazy"
							/>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php elseif ( 'image-only' === $middle_layout && $middle_image_url ) : ?>
			<!-- Image Only Layout -->
			<div class="w-full">
				<img 
					src="<?php echo esc_url( $middle_image_url ); ?>" 
					alt="<?php echo esc_attr( $middle_image_alt ); ?>" 
					class="w-full max-w-6xl object-cover h-auto mx-auto rounded-2xl shadow-xl"
					loading="lazy"
				/>
			</div>
		<?php elseif ( 'image-text' === $middle_layout && ! empty( $middle_items ) ) : ?>
			<!-- Image and Text Repeater Layout -->
			<div class="space-y-8 lg:space-y-12">
				<?php foreach ( $middle_items as $item ) : ?>
					<?php
					$item_text     = $item['text'] ?? '';
					$item_image    = $item['imageUrl'] ?? '';
					$item_alt      = $item['imageAlt'] ?? '';
					$item_reversed = $item['reversed'] ?? false;
					?>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center <?php echo $item_reversed ? 'md:flex md:flex-row-reverse' : ''; ?>">
						<!-- Text -->
						<?php if ( $item_text ) : ?>
							<div class="text-center md:text-left">
								<p class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-mission font-bold font-sofia">
									<?php echo wp_kses_post( $item_text ); ?>
								</p>
							</div>
						<?php endif; ?>
						
						<!-- Image -->
						<?php if ( $item_image ) : ?>
							<div class="relative w-full">
								<img 
									src="<?php echo esc_url( $item_image ); ?>" 
									alt="<?php echo esc_attr( $item_alt ); ?>" 
									class="w-full h-auto rounded-2xl shadow-xl object-cover"
									loading="lazy"
								/>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $bottom_text ) : ?>
			<!-- Bottom Text -->
			<div class="mt-12 md:mt-14 lg:mt-24 space-y-6 <?php echo esc_attr( $bottom_text_align_class ); ?>">
				<div class="text-mission-text text-3xl md:text-[40px] leading-[1.2] tracking-mission font-bold font-sofia <?php echo esc_attr( $bottom_text_max_width_class ); ?> mx-auto">
					<?php echo wp_kses_post( $bottom_text ); ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
