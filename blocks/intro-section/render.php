<?php
/**
 * Intro Section Block - Server-side rendering
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 *
 * @package BlacklineGuardianFund
 */

$bg_image_url  = $attributes['bgImageUrl'] ?? '';
$heading       = $attributes['heading'] ?? '';
$paragraph1    = $attributes['paragraph1'] ?? '';
$paragraph2    = $attributes['paragraph2'] ?? '';
$photo_url     = $attributes['photoUrl'] ?? '';
$cards_heading = $attributes['cardsHeading'] ?? '';
$cards         = $attributes['cards'] ?? array();
$shield_url    = $attributes['shieldIconUrl'] ?? '';
$tagline       = $attributes['tagline'] ?? '';

$bg_style = ! empty( $bg_image_url )
	? ' style="background-image:url(' . esc_url( $bg_image_url ) . ');background-size:cover;background-position:center;"'
	: '';

$wrapper_attributes = get_block_wrapper_attributes(
  array(
	  'class' => 'w-full bg-cream py-16 lg:py-24',
  )
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
  <div class="max-w-screen-2xl mx-auto px-6 lg:px-16">

    <!-- Top: Text (left) + Photo (right) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start mb-16 lg:mb-20">

      <!-- Left: Heading + Paragraphs -->
      <div>
        <?php if ( ! empty( $heading ) ) : ?>
          <h2 class="font-sofia font-bold text-4xl md:text-5xl lg:text-6xl text-dark-text leading-tight mb-6">
            <?php echo esc_html( $heading ); ?>
          </h2>
        <?php endif; ?>
        <?php if ( ! empty( $paragraph1 ) ) : ?>
          <p class="font-inter font-light text-base md:text-lg text-dark-text leading-relaxed mb-5">
            <?php echo esc_html( $paragraph1 ); ?>
          </p>
        <?php endif; ?>
        <?php if ( ! empty( $paragraph2 ) ) : ?>
          <p class="font-inter font-light text-base md:text-lg text-dark-text leading-relaxed">
            <?php echo esc_html( $paragraph2 ); ?>
          </p>
        <?php endif; ?>
      </div>

      <!-- Right: Photo -->
      <?php if ( ! empty( $photo_url ) ) : ?>
        <div class="rounded-2xl overflow-hidden w-full lg:max-w-[608px] lg:aspect-[608/446]">
          <img
            src="<?php echo esc_url( $photo_url ); ?>"
            alt=""
            class="w-full h-full object-cover"
            width="608"
            height="446"
          />
        </div>
      <?php endif; ?>

    </div>
    <!-- /Top Row -->

    <!-- Middle: Cards Heading + 4 Cards -->
    <?php if ( ! empty( $cards ) ) : ?>
      <div class="mb-12 lg:mb-16">

        <?php if ( ! empty( $cards_heading ) ) : ?>
          <h3 class="font-inter text-2xl font-semibold text-dark-text mb-6">
            <?php echo esc_html( $cards_heading ); ?>
          </h3>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <?php foreach ( $cards as $card ) : ?>
            <?php
            $icon_url   = $card['iconUrl'] ?? '';
            $card_label = $card['label'] ?? '';
            ?>
            <div class="category-card flex flex-col items-center justify-center gap-4 py-8 px-4">
              <?php if ( ! empty( $icon_url ) ) : ?>
                <img
                  src="<?php echo esc_url( $icon_url ); ?>"
                  alt="<?php echo esc_attr( $card_label ); ?>"
                  class="w-16 h-16 object-contain"
                  width="64"
                  height="64"
                />
              <?php endif; ?>
              <?php if ( ! empty( $card_label ) ) : ?>
                <span class="font-sofia font-bold text-2xl tracking-[-0.24px] uppercase text-card-label text-center leading-tight">
                  <?php echo esc_html( $card_label ); ?>
                </span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    <?php endif; ?>
    <!-- /Middle Row -->

    <!-- Bottom: Tagline with shield icon and divider line -->
    <?php if ( ! empty( $tagline ) ) : ?>
      <div class="flex items-center gap-4">

        <!-- Shield Icon -->
        <div class="shrink-0 w-8 h-8">
          <?php if ( ! empty( $shield_url ) ) : ?>
            <img
              src="<?php echo esc_url( $shield_url ); ?>"
              alt=""
              class="w-full h-full object-contain"
              width="32"
              height="32"
            />
          <?php else : ?>
            <svg viewBox="0 0 32 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
              <path d="M16 1L1 6.5V18C1 26.8 7.8 34.4 16 36C24.2 34.4 31 26.8 31 18V6.5L16 1Z" fill="none" stroke="#25272B" stroke-width="2" stroke-linejoin="round"/>
              <path d="M10 18l4.5 4.5L22 13" stroke="#25272B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          <?php endif; ?>
        </div>

        <!-- Tagline text -->
        <p class="font-sofia font-bold text-2xl lg:text-3xl uppercase tracking-[-0.32px] text-dark-text">
          <?php echo esc_html( $tagline ); ?>
        </p>

        <!-- Divider line -->
        <div class="flex-grow h-px bg-divider-gold opacity-40"></div>

      </div>
    <?php endif; ?>
    <!-- /Bottom Tagline -->

  </div>
</section>
