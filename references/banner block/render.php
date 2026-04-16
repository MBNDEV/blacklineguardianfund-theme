<?php
/**
 * Server-side render for the MBN Banner 1 block.
 *
 * @package MbnLanders
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content (empty for this block).
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$heading      = $attributes['heading'] ?? '';
$subtitle     = $attributes['subtitle'] ?? '';
$bullet_items = $attributes['bulletItems'] ?? array();
$form_title   = $attributes['formTitle'] ?? '';
$cta_label    = $attributes['ctaLabel'] ?? 'Contact Us';
$cta_url      = $attributes['ctaUrl'] ?? '#banner-form';

$phone_number = $attributes['phoneNumber'] ?? '';
$logo_url     = $attributes['logoUrl'] ?: MBN_LANDERS_URL . 'assets/img/defaults/logo.svg';
$hero_url     = $attributes['heroImageUrl'] ?: MBN_LANDERS_URL . 'assets/img/defaults/hva-5star.webp';
$overlay_url  = $attributes['overlayImageUrl'] ?: MBN_LANDERS_URL . 'assets/img/defaults/overlay-img.webp';
$phone_icon   = $attributes['phoneIconUrl'] ?: MBN_LANDERS_URL . 'assets/img/defaults/phone-icon.svg';
$gf_id        = absint( $attributes['gravityFormId'] ?? 0 );

$gradient_start = sanitize_hex_color( $attributes['gradientStartColor'] ?? '' ) ?: '#1DB7F9';
$gradient_end   = sanitize_hex_color( $attributes['gradientEndColor'] ?? '' ) ?: '#01539D';
$text_color     = sanitize_hex_color( $attributes['textColor'] ?? '' ) ?: '#ffffff';
$cta_bg_color   = sanitize_hex_color( $attributes['ctaBgColor'] ?? '' );
$cta_text_color = sanitize_hex_color( $attributes['ctaTextColor'] ?? '' ) ?: '#ffffff';

$section_style = sprintf(
  'background: linear-gradient(129deg, %s 14.23%%, %s 88.76%%)',
  $gradient_start,
  $gradient_end
);

$cta_btn_style = 'color: ' . $cta_text_color . ';';
if ( $cta_bg_color ) {
  $cta_btn_style = 'background-color: ' . $cta_bg_color . '; ' . $cta_btn_style;
}

$classes       = 'mbn-banner-1 flex px-5 py-5 flex-col w-full items-center relative border-b-14 border-gray';
$wrapper_attrs = get_block_wrapper_attributes(
  array(
	  'class' => $classes,
	  'style' => $section_style,
  )
);

$phone_href = 'tel:' . preg_replace( '/[^0-9+]/', '', $phone_number );
?>
<section <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by get_block_wrapper_attributes. ?>>
  <div class="flex flex-col max-w-400 w-full relative z-20">

    <?php // Header row. ?>
    <div class="flex flex-row justify-between items-center mb-6">
      <a href="#">
        <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr__( 'logo', 'mbn-landers' ); ?>">
      </a>
      <div class="flex flex-row items-center">
        <a href="<?php echo esc_url( $phone_href ); ?>" class="mr-5 text-white text-[26px] phone_number_icon" style="color: <?php echo esc_attr( $text_color ); ?>; --phone-icon-url: url('<?php echo esc_url( $phone_icon ); ?>')">
          <span class="hidden lg:block"><?php echo esc_html( $phone_number ); ?></span>
        </a>
        <a href="<?php echo esc_url( $cta_url ); ?>" class="bg-primary text-white rounded-full uppercase px-6.75 py-4.5 font-bold lg:text-lg lg:tracking-wider" style="<?php echo esc_attr( $cta_btn_style ); ?>">
          <?php echo esc_html( $cta_label ); ?>
        </a>
      </div>
    </div>

    <?php // Hero content. ?>
    <div class="flex flex-col items-center mb-12">
      <?php if ( $heading ) : ?>
        <h1 class="block text-white text-[32px] lg:text-[64px] text-center mb-2 font-poppins leading-8 lg:leading-15 font-semibold" style="color: <?php echo esc_attr( $text_color ); ?>">
          <?php echo wp_kses_post( $heading ); ?>
        </h1>
      <?php endif; ?>
      <?php if ( $subtitle ) : ?>
        <h2 class="block text-white text-base md:text-xl lg:text-[24px] mb-4 font-opensans font-thin" style="color: <?php echo esc_attr( $text_color ); ?>">
          <?php echo wp_kses_post( $subtitle ); ?>
        </h2>
      <?php endif; ?>
      <?php if ( ! empty( $bullet_items ) ) : ?>
        <ul class="flex flex-col lg:flex-row items-center justify-evenly text-base md:text-xl lg:text-[24px] text-white lg:gap-8 font-opensans" style="color: <?php echo esc_attr( $text_color ); ?>">
          <?php foreach ( $bullet_items as $item ) : ?>
            <li><?php echo wp_kses_post( $item ); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <?php // Hero image + form. ?>
    <div class="flex flex-col lg:flex-row -mb-25 w-full lg:justify-between">
      <div class="flex flex-col lg:w-1/2 mb-5 lg:mb-0 pr-0 lg:pr-5">
        <img
          src="<?php echo esc_url( $hero_url ); ?>"
          alt="<?php echo esc_attr__( 'hero', 'mbn-landers' ); ?>"
          class="lg:max-w-141.25 lg:mt-25 lg:ml-10 block mx-auto lg:mx-0"
          loading="eager"
          fetchpriority="high"
        >
      </div>
      <div class="flex flex-col lg:w-1/2 lg:max-w-177.5">
        <div id="banner-form" class="flex w-full flex-col rounded-xl p-6 lg:p-10 bg-white shadow-[0px_4px_80px_0px_rgba(0,0,0,0.20)]">
          <?php if ( $form_title ) : ?>
            <h3 class="text-center block font-semibold text-xl md:text-2xl lg:text-[26px] mb-8">
              <?php echo wp_kses_post( $form_title ); ?>
            </h3>
          <?php endif; ?>
          <?php
          if ( $gf_id && function_exists( 'gravity_form' ) ) {
            gravity_form( $gf_id, false, false, false, null, true );
          }
          ?>
        </div>
      </div>
    </div>

  </div>
  <img
    src="<?php echo esc_url( $overlay_url ); ?>"
    class="w-full h-full absolute top-0 left-0 opacity-50 z-10 object-cover"
    alt=""
    loading="eager"
  >
</section>
