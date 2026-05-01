<?php
/**
 * Server-side render for the Simple Hero Section block.
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

$background_color     = $attributes['backgroundColor'] ?? '#1A1D21';
$background_image_url = $attributes['backgroundImageUrl'] ?? '';
$subtitle             = $attributes['subtitle'] ?? '';
$heading              = $attributes['heading'] ?? '';
$paragraph            = $attributes['paragraph'] ?? '';
$email                = $attributes['email'] ?? '';
$address              = $attributes['address'] ?? '';
$show_primary_button  = $attributes['showPrimaryButton'] ?? false;
$primary_button_text  = $attributes['primaryButtonText'] ?? 'Get Started';
$primary_button_url   = ! empty( $attributes['primaryButtonUrl'] ) ? esc_url( $attributes['primaryButtonUrl'] ) : '#';

// Generate unique ID for this block instance.
$block_id = 'simple-hero-section-' . wp_unique_id();

// Build inline styles.
$section_style = sprintf( 'background-color: %s;', esc_attr( $background_color ) );
if ( $background_image_url ) {
	$section_style .= sprintf(
      ' background-image: url(%s); background-size: cover; background-position: center;',
      esc_url( $background_image_url )
	);
}

$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => 'simple-hero-section relative w-full pt-20 pb-28 md:pt-28 md:pb-36 lg:pt-44 lg:pb-48 overflow-hidden',
	  'style' => $section_style,
	  'id'    => $block_id,
  )
);
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container mx-auto px-6 md:px-12 lg:px-16 max-w-6xl">
		<!-- Section Content -->
		<div class="text-center relative z-10">
			
			<?php if ( $subtitle ) : ?>
				<!-- Subtitle -->
				<p class="font-sofia text-gold text-lg md:text-2xl font-bold tracking-subheading uppercase">
					<?php echo wp_kses_post( $subtitle ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<!-- Main heading -->
				<h1 class="font-sofia text-4xl sm:text-5xl lg:text-7xl font-bold leading-[1.1] tracking-hero text-white mb-6">
					<?php echo wp_kses_post( $heading ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( $paragraph ) : ?>
				<!-- Description -->
				<p class="font-inter text-white font-light text-base md:text-lg leading-relaxed max-w-4xl mx-auto mb-6">
					<?php echo wp_kses_post( $paragraph ); ?>
				</p>
			<?php endif; ?>

			<!-- Contact Info -->
			<div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8 lg:gap-16">
				
				<?php if ( $email ) : ?>
					<!-- Email -->
					<div class="flex items-center gap-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-12" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
							<path d="M3.55286 20.1497C3.09286 20.1497 2.69403 19.9808 2.35636 19.643C2.01853 19.3053 1.84961 18.9065 1.84961 18.4465V5.55398C1.84961 5.09231 2.01853 4.69206 2.35636 4.35323C2.69403 4.01423 3.09286 3.84473 3.55286 3.84473H20.4454C20.907 3.84473 21.3073 4.01423 21.6461 4.35323C21.9851 4.69206 22.1546 5.09231 22.1546 5.55398V18.4465C22.1546 18.9065 21.9851 19.3053 21.6461 19.643C21.3073 19.9808 20.907 20.1497 20.4454 20.1497H3.55286ZM20.4454 7.06648L12.4594 12.3245C12.3812 12.366 12.3078 12.4002 12.2391 12.4272C12.1704 12.4542 12.0904 12.4677 11.9991 12.4677C11.9078 12.4677 11.8278 12.4542 11.7591 12.4272C11.6904 12.4002 11.617 12.366 11.5389 12.3245L3.55286 7.06648V18.4465H20.4454V7.06648ZM11.9991 11.004L20.3454 5.55398H3.67786L11.9991 11.004ZM3.55286 7.25948V6.18397V6.20423V5.55398V6.19473V6.16598V7.25948Z" fill="white" fill-opacity="0.6"/>
							<path d="M3.05273 5.74219V18.9463H20.9453V6.13867L20.1699 6.64844L12.2139 11.8867C12.1535 11.9184 12.1011 11.944 12.0557 11.9619C12.0525 11.9629 12.0357 11.9678 11.999 11.9678C11.9617 11.9678 11.9451 11.9628 11.9424 11.9619C11.8967 11.9439 11.8439 11.9186 11.7832 11.8867L4.05273 6.7959V6.39648L11.7256 11.4219L11.999 11.6016L12.2725 11.4229L20.6191 5.97266L21.6299 5.31152C21.6455 5.38933 21.6543 5.46973 21.6543 5.55371V18.4463C21.6543 18.7675 21.542 19.041 21.293 19.2891C21.0441 19.5371 20.7693 19.6494 20.4453 19.6494H3.55273C3.23102 19.6494 2.95784 19.5371 2.70996 19.2891L2.62305 19.1953C2.43538 18.9725 2.34961 18.7279 2.34961 18.4463V5.55371C2.34963 5.46519 2.35868 5.38047 2.37598 5.29883L3.05273 5.74219ZM3.55273 4.34473H20.4453C20.7687 4.34473 21.043 4.4579 21.292 4.70703H21.293C21.4026 4.81669 21.4842 4.93216 21.543 5.05371H2.45996C2.51864 4.93172 2.60041 4.81595 2.70996 4.70605H2.71094C2.95891 4.45718 3.23166 4.34476 3.55273 4.34473Z" stroke="white" stroke-opacity="0.6"/>
						</svg>
						<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" class="font-inter text-white/60 text-base md:text-lg underline hover:text-gold transition-colors duration-200">
							<?php echo esc_html( antispambot( $email ) ); ?>
						</a>
					</div>
				<?php endif; ?>

				<?php if ( $address ) : ?>
					<!-- Address -->
					<div class="flex items-start sm:items-center gap-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-12" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
							<path d="M11.9991 19.9057C14.1958 17.9129 15.8194 16.1146 16.8699 14.5107C17.9202 12.9071 18.4454 11.4642 18.4454 10.1822C18.4454 8.23923 17.8229 6.64873 16.5779 5.41073C15.3327 4.17289 13.8064 3.55398 11.9989 3.55398C10.1914 3.55398 8.66519 4.17289 7.42036 5.41073C6.17536 6.64873 5.55286 8.23923 5.55286 10.1822C5.55286 11.4642 6.09053 12.9061 7.16586 14.5077C8.24136 16.1096 9.85244 17.9089 11.9991 19.9057ZM11.9914 21.6257C11.8052 21.6257 11.6184 21.5946 11.4311 21.5322C11.2438 21.4699 11.0799 21.3701 10.9394 21.2327C10.2154 20.5916 9.43911 19.8565 8.61061 19.0275C7.78194 18.1985 7.01461 17.306 6.30861 16.35C5.60261 15.394 5.01628 14.3923 4.54961 13.345C4.08294 12.2978 3.84961 11.2436 3.84961 10.1822C3.84961 7.64356 4.66928 5.61931 6.30861 4.10948C7.94778 2.59964 9.84461 1.84473 11.9991 1.84473C14.1536 1.84473 16.0514 2.59964 17.6926 4.10948C19.3339 5.61931 20.1546 7.64356 20.1546 10.1822C20.1546 11.2436 19.9203 12.2978 19.4516 13.345C18.9829 14.3923 18.3956 15.394 17.6896 16.35C16.9836 17.306 16.2163 18.1985 15.3876 19.0275C14.5591 19.8565 13.7849 20.5916 13.0649 21.2327C12.9197 21.3701 12.751 21.4699 12.5589 21.5322C12.3667 21.5946 12.1775 21.6257 11.9914 21.6257ZM12.0014 11.792C12.4992 11.792 12.9229 11.6175 13.2724 11.2685C13.622 10.9193 13.7969 10.4958 13.7969 9.99798C13.7969 9.50014 13.6214 9.07648 13.2704 8.72698C12.9192 8.37731 12.4947 8.20248 11.9969 8.20248C11.499 8.20248 11.0763 8.37798 10.7286 8.72898C10.3811 9.08014 10.2074 9.50464 10.2074 10.0025C10.2074 10.5003 10.3819 10.9231 10.7309 11.2707C11.08 11.6182 11.5035 11.792 12.0014 11.792Z" stroke="white" fill="white" fill-opacity="0.6"/>
							<path d="M11.999 2.34473C14.0234 2.34473 15.802 3.04933 17.3545 4.47754C18.878 5.87913 19.6543 7.7626 19.6543 10.1826C19.6542 11.1679 19.4373 12.1526 18.9951 13.1406C18.5416 14.154 17.9725 15.1246 17.2871 16.0527C16.5957 16.9889 15.845 17.8627 15.0342 18.6738C14.2111 19.4974 13.4438 20.2259 12.7324 20.8594L12.7266 20.8643L12.7217 20.8691C12.6311 20.9548 12.5266 21.017 12.4043 21.0566C12.2578 21.1041 12.1206 21.126 11.9912 21.126C11.8619 21.126 11.7283 21.104 11.5889 21.0576C11.4732 21.0191 11.375 20.959 11.2891 20.875L11.2803 20.8662L11.2705 20.8584C10.5556 20.2252 9.78656 19.497 8.96387 18.6738C8.15312 17.8627 7.4023 16.9889 6.71094 16.0527C6.02568 15.1248 5.45726 14.1547 5.00586 13.1416C4.56549 12.1534 4.34967 11.1682 4.34961 10.1826C4.34961 7.76231 5.12573 5.8791 6.64746 4.47754C8.19779 3.04953 9.97475 2.34475 11.999 2.34473ZM11.999 3.05371C10.0638 3.05371 8.40896 3.72261 7.06738 5.05664C5.7178 6.39887 5.05273 8.12233 5.05273 10.1826C5.05284 11.6021 5.64665 13.1413 6.75098 14.7861C7.85583 16.4317 9.49651 18.2606 11.6582 20.2715L11.9951 20.585L12.335 20.2764C14.5478 18.2689 16.2053 16.4381 17.2881 14.7852C18.3669 13.138 18.9452 11.5989 18.9453 10.1826C18.9453 8.12228 18.2803 6.39888 16.9307 5.05664V5.05566C15.5888 3.72177 13.9342 3.05375 11.999 3.05371ZM11.9971 8.70215C12.3633 8.7022 12.661 8.82522 12.918 9.08105C13.1741 9.33617 13.2969 9.63248 13.2969 9.99805C13.2969 10.3642 13.1739 10.6605 12.9189 10.915C12.6641 11.1694 12.3674 11.292 12.001 11.292C11.634 11.2919 11.3382 11.169 11.084 10.916C10.8305 10.6634 10.7071 10.3691 10.707 10.0029C10.707 9.68146 10.8013 9.41279 10.9951 9.17871L11.084 9.08105C11.3369 8.82567 11.6315 8.70215 11.9971 8.70215Z" stroke="white" stroke-opacity="0.6"/>
						</svg>
						<p class="font-inter text-white/60 text-base md:text-lg">
							<?php echo esc_html( $address ); ?>
						</p>
					</div>
				<?php endif; ?>

			</div>

			<?php if ( $show_primary_button && ! empty( $primary_button_text ) ) : ?>
				<!-- CTA Button (gold gradient button) -->
				<div class="mt-8">
					<a href="<?php echo $primary_button_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_url'd ?>" class="inline-flex font-inter items-center justify-center gap-2 h-11 px-5 rounded-full font-bold text-base leading-none uppercase tracking-tight transition-all duration-300 bg-gradient-to-b from-gold-light to-gold text-gold-dark shadow-md hover:shadow-lg hover:-translate-y-0.5 active:shadow-sm active:translate-y-0 no-underline">
						<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gold shrink-0">
							<svg xmlns="http://www.w3.org/2000/svg" class="scale-x-[-1]" width="9" height="9" viewBox="0 0 9 9" fill="none" aria-hidden="true" focusable="false">
								<path d="M0.000102425 8.15588L7.13312 1.02286L2.3107 1.02357L2.31105 -1.78562e-07L8.35508 -1.36862e-08L8.85496 0.0113766L8.86634 0.511254L8.86634 6.55528L7.84383 6.55528L7.84418 1.73392L0.711165 8.86695L0.000102425 8.15588Z" fill="white"/>
							</svg>
						</span>
						<span><?php echo esc_html( $primary_button_text ); ?></span>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<!-- Decorative Blur Overlay -->
	<div class="absolute left-1/2 -translate-x-1/2 pointer-events-none -bottom-[567px] w-[1354px] h-[647px] rounded-[1354px] bg-[rgba(211,233,255,0.40)] blur-[130px]"></div>
</section>
