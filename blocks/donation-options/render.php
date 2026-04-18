<?php
/**
 * Donation Options Block - Server-side rendering
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 *
 * @package BlacklineGuardianFund
 */

$main_heading    = $attributes['mainHeading'] ?? '';
$description1    = $attributes['description1'] ?? '';
$description2    = $attributes['description2'] ?? '';
$section_heading = $attributes['sectionHeading'] ?? '';
$cards           = $attributes['cards'] ?? array();

$wrapper_attributes = get_block_wrapper_attributes(
  array(
	  'class' => 'w-full py-16 md:py-24 bg-white',
  )
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	
	<div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
		
		<!-- Header Section: Title + Description -->
		<div class="grid grid-cols-1 lg:grid-cols-[55%_45%] gap-8 lg:gap-16 mb-12 md:mb-16">
			
			<!-- Left: Main Heading -->
			<div>
				<?php if ( ! empty( $main_heading ) ) : ?>
					<h2 class="font-sofia font-bold text-4xl md:text-5xl lg:text-6xl leading-tight text-dark-text">
						<?php echo wp_kses_post( $main_heading ); ?>
					</h2>
				<?php endif; ?>
			</div>
			
			<!-- Right: Description Text -->
			<div class="space-y-6">
				<?php if ( ! empty( $description1 ) ) : ?>
					<p class="font-inter text-base md:text-lg leading-relaxed text-dark-text">
						<?php echo esc_html( $description1 ); ?>
					</p>
				<?php endif; ?>
				<?php if ( ! empty( $description2 ) ) : ?>
					<p class="font-inter text-base md:text-lg leading-relaxed text-dark-text">
						<?php echo esc_html( $description2 ); ?>
					</p>
				<?php endif; ?>
			</div>
			
		</div>
		
		<!-- Donation Options Heading with Decorative Line -->
		<?php if ( ! empty( $section_heading ) ) : ?>
			<div class="flex items-center gap-6 mb-8">
				<h3 class="font-sofia font-bold text-2xl md:text-3xl text-dark-text whitespace-nowrap">
					<?php echo esc_html( $section_heading ); ?>
				</h3>
				<div class="flex-grow h-px bg-divider-gold"></div>
			</div>
		<?php endif; ?>
		
		<!-- Three Donation Cards -->
		<?php if ( ! empty( $cards ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
				
				<?php foreach ( $cards as $card ) : ?>
					<?php
					$card_title   = $card['title'] ?? '';
					$card_amount  = $card['amount'] ?? '';
					$card_desc    = $card['description'] ?? '';
					$card_bullets = $card['bullets'] ?? array();
					$card_btn_txt = $card['buttonText'] ?? '';
					$card_btn_url = $card['buttonUrl'] ?? '#';
					$card_bg      = $card['bgColor'] ?? 'card-cream';
					?>
					
					<div class="bg-<?php echo esc_attr( $card_bg ); ?> rounded-3xl p-8 flex flex-col border border-black/15">
						
						<!-- Card Title -->
						<?php if ( ! empty( $card_title ) ) : ?>
							<h4 class="font-sofia font-semibold text-lg md:text-2xl text-dark-text mb-4">
								<?php echo esc_html( $card_title ); ?>
							</h4>
						<?php endif; ?>
						
						<!-- Amount -->
						<?php if ( ! empty( $card_amount ) ) : ?>
							<p class="font-sofia font-bold text-6xl md:text-7xl text-gold mb-6 tracking-[-0.8px]">
								<?php echo esc_html( $card_amount ); ?>
							</p>
						<?php endif; ?>
						
						<!-- Divider -->
						<div class="w-full h-px bg-black/15 mb-6"></div>
						
						<!-- Description -->
						<?php if ( ! empty( $card_desc ) ) : ?>
							<p class="font-inter text-base leading-relaxed text-dark-text mb-6">
								<?php echo esc_html( $card_desc ); ?>
							</p>
						<?php endif; ?>
						
						<!-- Bullet List -->
						<?php if ( ! empty( $card_bullets ) ) : ?>
							<ul class="space-y-3 mb-8 flex-grow">
								<?php foreach ( $card_bullets as $bullet ) : ?>
									<li class="flex items-start gap-3">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 mt-0.5">
											<path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="#7CAA6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<span class="font-inter text-base text-dark-text"><?php echo esc_html( $bullet ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						
						<!-- CTA Button -->
						<?php if ( ! empty( $card_btn_txt ) ) : ?>
							<a href="<?php echo esc_url( $card_btn_url ); ?>" class="donate-button-shadow inline-flex items-center justify-center gap-2 h-12 px-6 rounded-full font-bold text-sm uppercase tracking-wide transition-all duration-300 bg-gradient-to-b from-gold-light to-gold text-gold-dark hover:shadow-lg hover:-translate-y-0.5 active:shadow-sm active:translate-y-0 no-underline">
								<span><?php echo esc_html( $card_btn_txt ); ?></span>
								<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gold shrink-0">
									<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1 9L9 1M9 1H1M9 1V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
							</a>
						<?php endif; ?>
						
					</div>
					
				<?php endforeach; ?>
				
			</div>
		<?php endif; ?>
		
	</div>
	
</section>
