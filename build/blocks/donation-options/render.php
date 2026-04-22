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
		<div class="grid grid-cols-1 lg:grid-cols-[5.5fr_4.5fr] gap-8 lg:gap-16 mb-12 md:mb-16">
			
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
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
				
				<?php foreach ( $cards as $card ) : ?>
					<?php
					$card_title    = $card['title'] ?? '';
					$card_amount   = $card['amount'] ?? '';
					$card_desc     = $card['description'] ?? '';
					$card_bullets  = $card['bullets'] ?? array();
					$card_btn_txt  = $card['buttonText'] ?? '';
					$card_btn_url  = $card['buttonUrl'] ?? '#';
                    $card_bg_key   = $card['bgColor'] ?? 'card-cream';
                    $card_bg_map   = array(
						'card-cream' => 'card-bg-cream',
						'card-gold'  => 'card-bg-gold',
						'card-beige' => 'card-bg-beige',
                    );
                    $card_bg_class = $card_bg_map[ $card_bg_key ] ?? 'card-bg-cream';
                    ?>
				
				<div class="<?php echo esc_attr( $card_bg_class ); ?> rounded-3xl p-5 lg:p-6 xl:p-8 flex flex-col border border-black/15">
						
						<!-- Card Title -->
						<?php if ( ! empty( $card_title ) ) : ?>
							<h4 class="font-sofia font-semibold text-lg md:text-2xl text-dark-text mb-4">
								<?php echo esc_html( $card_title ); ?>
							</h4>
						<?php endif; ?>
						
						<!-- Amount -->
						<?php if ( ! empty( $card_amount ) ) : ?>
							<p class="font-sofia font-bold text-7xl lg:text-6xl xl:text-7xl text-gold mb-6 tracking-[-0.8px]">
								<?php echo esc_html( $card_amount ); ?>
							</p>
						<?php endif; ?>
						
						<!-- Divider -->
						<div class="w-full h-px bg-black/15 mb-6"></div>
						
						<!-- Description -->
						<?php if ( ! empty( $card_desc ) ) : ?>
							<p class="font-inter font-medium text-base leading-relaxed text-dark-text mb-6">
								<?php echo esc_html( $card_desc ); ?>
							</p>
						<?php endif; ?>
						
						<!-- Bullet List -->
						<?php if ( ! empty( $card_bullets ) ) : ?>
							<ul class="space-y-3 mb-8 flex-grow">
								<?php foreach ( $card_bullets as $bullet ) : ?>
									<li class="flex items-start gap-3">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M9.44982 15.5441L18.4471 6.55285C18.6219 6.38285 18.8272 6.29785 19.0631 6.29785C19.2987 6.29785 19.5016 6.38327 19.6716 6.5541C19.8416 6.72493 19.9266 6.9286 19.9266 7.1651C19.9266 7.40177 19.8416 7.60585 19.6716 7.77735L10.0526 17.3904C9.88124 17.5604 9.67999 17.6454 9.44882 17.6454C9.21766 17.6454 9.01707 17.5604 8.84707 17.3904L4.30907 12.8524C4.13907 12.6797 4.05724 12.4749 4.06357 12.2381C4.06991 12.0013 4.15849 11.7979 4.32932 11.6279C4.50016 11.4579 4.70382 11.3729 4.94032 11.3729C5.17699 11.3729 5.38107 11.4579 5.55257 11.6279L9.44982 15.5441Z" fill="#B89352"/>
										</svg>
										<span class="font-inter text-base text-dark-text"><?php echo esc_html( $bullet ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						
						<!-- CTA Button -->
						<?php if ( ! empty( $card_btn_txt ) ) : ?>
							<a href="<?php echo esc_url( $card_btn_url ); ?>" class="donate-button-shadow inline-flex items-center justify-center gap-2 h-12 px-4 lg:px-5 xl:px-6 rounded-full font-bold text-base uppercase tracking-wide transition-all duration-300 bg-gradient-to-b from-gold-light to-gold text-gold-dark hover:shadow-lg hover:-translate-y-0.5 active:shadow-sm active:translate-y-0 no-underline">
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
