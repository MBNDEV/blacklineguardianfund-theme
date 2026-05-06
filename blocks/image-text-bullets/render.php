<?php
/**
 * Server-side render for the Image Text with Bullets Below block.
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

$heading          = $attributes['heading'] ?? '';
$description      = $attributes['description'] ?? '';
$image_url        = $attributes['imageUrl'] ?? '';
$image_alt        = $attributes['imageAlt'] ?? '';
$shield_image_url = $attributes['shieldImageUrl'] ?? '';
$bg_image_url     = $attributes['backgroundImageUrl'] ?? '';
$cards            = $attributes['cards'] ?? array();

$wrapper_class = 'image-text-bullets-section w-full py-16 md:py-20 lg:py-24 overflow-hidden';

// Build background style
$section_style = 'background-color: #F9F5EE;';
if ( $bg_image_url ) {
	$section_style .= sprintf(
      ' background-image: url(%s); background-size: cover; background-position: center;',
      esc_url( $bg_image_url )
	);
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

		<!-- Top Section: Heading + Image -->
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center mb-16 lg:mb-24">
			
			<!-- Left Column: Text Content -->
			<div class="lg:col-span-6 space-y-6">
				<?php if ( $heading ) : ?>
					<h2 class="font-sofia text-4xl md:text-5xl lg:text-[52px] font-bold leading-[1.1] tracking-[-0.02em] text-black">
						<?php echo wp_kses_post( $heading ); ?>
					</h2>
				<?php endif; ?>
				
				<?php if ( $description ) : ?>
					<p class="text-base lg:text-lg font-light font-inter leading-relaxed text-dark-text max-w-lg">
						<?php echo wp_kses_post( $description ); ?>
					</p>
				<?php endif; ?>
			</div>

			<!-- Right Column: Image with Shield Overlay -->
			<?php if ( $image_url ) : ?>
				<div class="lg:col-span-6 relative">
					<div class="relative rounded-2xl overflow-hidden shadow-2xl">
						<img 
							src="<?php echo esc_url( $image_url ); ?>" 
							alt="<?php echo esc_attr( $image_alt ); ?>" 
							class="w-full h-auto object-cover"
							loading="lazy"
						/>
					</div>
					<!-- Shield Overlay -->
					<?php if ( $shield_image_url ) : ?>
						<div class="absolute -top-6 -right-6 lg:-top-11 lg:-right-9 w-24 md:w-32 lg:w-36 h-auto">
							<img 
								src="<?php echo esc_url( $shield_image_url ); ?>" 
								alt="" 
								class="w-full h-full object-contain drop-shadow-2xl"
								loading="lazy"
							/>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

		<!-- Cards Section -->
		<?php if ( ! empty( $cards ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
				<?php foreach ( $cards as $card ) : ?>
					<?php
					$card_icon       = $card['iconUrl'] ?? '';
					$card_heading    = $card['heading'] ?? '';
					$card_list_items = $card['listItems'] ?? array();
					?>
					<div class="space-y-4">
						<!-- Icon -->
						<?php if ( $card_icon ) : ?>
							<div class="w-12 h-12 lg:w-14 lg:h-14 flex items-center justify-center">
								<img 
									src="<?php echo esc_url( $card_icon ); ?>" 
									alt="" 
									class="w-full h-full object-contain"
									loading="lazy"
								/>
							</div>
						<?php endif; ?>
						
						<!-- Heading -->
						<?php if ( $card_heading ) : ?>
							<h3 class="font-sofia text-xl lg:text-2xl font-bold text-black">
								<?php echo wp_kses_post( $card_heading ); ?>
							</h3>
						<?php endif; ?>
						
						<!-- List Items -->
						<?php if ( ! empty( $card_list_items ) ) : ?>
							<ul class="list-disc pl-5 space-y-2 text-sm lg:text-base font-light font-inter text-dark-text">
								<?php foreach ( $card_list_items as $list_item ) : ?>
									<?php $item_text = $list_item['text'] ?? ''; ?>
									<?php if ( $item_text ) : ?>
										<li><?php echo esc_html( $item_text ); ?></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
