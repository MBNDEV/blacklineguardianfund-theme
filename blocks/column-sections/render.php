<?php
/**
 * Column Sections Block - Render
 *
 * @package Blacklineguardianfund_MBN
 */

$background_image         = $attributes['backgroundImageUrl'] ?? '';
$row1_col1_heading        = $attributes['row1Col1Heading'] ?? '';
$row1_col1_paragraph      = $attributes['row1Col1Paragraph'] ?? '';
$row1_col1_icon_list      = $attributes['row1Col1IconList'] ?? array();
$row1_col2_shortcode      = $attributes['row1Col2GravityFormShortcode'] ?? '';
$floating_shield_icon     = $attributes['floatingShieldIconUrl'] ?? '';
$floating_shield_icon_alt = $attributes['floatingShieldIconAlt'] ?? 'Shield icon';
$form_footer_text         = $attributes['formFooterText'] ?? '';
$row2_col1_heading        = $attributes['row2Col1Heading'] ?? '';
$row2_col1_paragraph      = $attributes['row2Col1Paragraph'] ?? '';
$row2_col1_programs       = $attributes['row2Col1Programs'] ?? array();
$row2_col2_heading        = $attributes['row2Col2Heading'] ?? '';
$row2_col2_paragraph1     = $attributes['row2Col2Paragraph1'] ?? '';
$row2_col2_bullets        = $attributes['row2Col2BulletList'] ?? array();
$row2_col2_paragraph2     = $attributes['row2Col2Paragraph2'] ?? '';
$anim_attr                = mbn_get_animation_attrs( $attributes );

// Build inline styles for section background.
$section_style = '';
if ( ! empty( $background_image ) ) {
	$section_style = sprintf(
      'background-image: url(%s); background-size: cover; background-position: center; background-repeat: no-repeat;',
      esc_url( $background_image )
	);
}

$wrapper_attributes = get_block_wrapper_attributes(
  array(
	  'class' => 'column-sections-block font-inter flex items-center justify-center bg-cream',
	  'style' => $section_style,
  )
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
  <div class="py-20 md:px-12 max-w-screen-2xl mx-auto px-6">
    
    <!-- Row 1: Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start mb-20">
      
      <!-- Row 1 - Column 1: Text Content -->
      <div class="text-content">
        <?php if ( ! empty( $row1_col1_heading ) ) : ?>
          <h2 class="font-sofia text-3xl md:text-4xl lg:text-5xl font-bold text-black mb-6 leading-tight tracking-heading">
            <?php echo wp_kses_post( $row1_col1_heading ); ?>
          </h2>
        <?php endif; ?>
        
        <?php if ( ! empty( $row1_col1_paragraph ) ) : ?>
          <p class="text-base lg:text-lg text-black font-light mb-8 leading-relaxed">
            <?php echo wp_kses_post( $row1_col1_paragraph ); ?>
          </p>
        <?php endif; ?>
        
        <?php if ( ! empty( $row1_col1_icon_list ) ) : ?>
          <!-- Icon List -->
          <div class="space-y-6">
            <?php foreach ( $row1_col1_icon_list as $item ) : ?>
              <div class="flex items-center gap-4">
                <?php if ( ! empty( $item['iconUrl'] ) ) : ?>
                  <div class="w-14 h-14 flex items-center justify-center">
                    <img src="<?php echo esc_url( $item['iconUrl'] ); ?>" alt="" class="w-full h-full" />
                  </div>
                <?php endif; ?>
                <?php if ( ! empty( $item['text'] ) ) : ?>
                  <div>
                    <p class="font-sofia text-base lg:text-lg font-bold text-black tracking-body">
                      <?php echo wp_kses_post( $item['text'] ); ?>
                    </p>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Row 1 - Column 2: Gravity Form -->
      <div class="form-content-wrap">
        <?php if ( ! empty( $row1_col2_shortcode ) ) : ?>
          <div class="contact-form-card rounded-2xl px-4 py-10 sm:p-8 md:p-10 lg:p-8 xl:p-10 relative z-30 -mt-[20vh] -mb-[25vh] xl:-mt-[55vh] xl:-mb-[12vh]">

            <div class="gform-wrapper gform-no-default-theme">
              <!-- Floating Shield Icon -->
              <?php if ( ! empty( $floating_shield_icon ) ) : ?>
                <div class="absolute -top-10 md:-top-8 right-0 md:-right-5 lg:-right-4 xl:-right-5 w-24 h-auto md:w-32 md:h-40 lg:h-32 xl:w-40 xl:h-44 opacity-90 pointer-events-none" style="transform: translate(20%, -10%);">
                  <img src="<?php echo esc_url( $floating_shield_icon ); ?>" alt="<?php echo esc_attr( $floating_shield_icon_alt ); ?>" class="w-full h-full object-contain" />
                </div>
              <?php endif; ?>

              <?php echo do_shortcode( $row1_col2_shortcode ); ?>
              <?php if ( ! empty( $form_footer_text ) ) : ?>
                <div class="terms-footer mt-6">
                  <?php echo wp_kses_post( $form_footer_text ); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
      
    </div>
    
    <!-- Row 2: Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-start">
      
      <!-- Row 2 - Column 1: Programs -->
      <div class="programs-content">
        <?php if ( ! empty( $row2_col1_heading ) ) : ?>
          <h2 class="font-sofia text-3xl md:text-5xl xl:text-6xl font-bold text-black mb-6 leading-tight tracking-heading">
            <?php echo wp_kses_post( $row2_col1_heading ); ?>
          </h2>
        <?php endif; ?>
        
        <?php if ( ! empty( $row2_col1_paragraph ) ) : ?>
          <p class="text-base lg:text-lg text-black font-light mb-10 leading-relaxed">
            <?php echo wp_kses_post( $row2_col1_paragraph ); ?>
          </p>
        <?php endif; ?>
        
        <?php if ( ! empty( $row2_col1_programs ) ) : ?>
          <div class="space-y-8">
            <?php foreach ( $row2_col1_programs as $program ) : ?>
              <div class="flex items-start gap-4">
                <?php if ( ! empty( $program['iconUrl'] ) ) : ?>
                  <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center">
                    <img src="<?php echo esc_url( $program['iconUrl'] ); ?>" alt="" class="w-full h-full" />
                  </div>
                <?php endif; ?>
                <div>
                  <?php if ( ! empty( $program['heading'] ) ) : ?>
                    <h4 class="font-sofia text-2xl md:text-3xl font-bold text-black md:leading-[36.4px] tracking-subheading mb-2">
                      <?php echo wp_kses_post( $program['heading'] ); ?>
                    </h4>
                  <?php endif; ?>
                  <?php if ( ! empty( $program['text'] ) ) : ?>
                    <p class="text-gray-text font-inter text-lg font-light leading-[27px]">
                      <?php echo wp_kses_post( $program['text'] ); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Row 2 - Column 2: Bullets -->
      <div class="donations-content pt-8 lg:pt-40">
        <?php if ( ! empty( $row2_col2_heading ) ) : ?>
          <h2 class="font-sofia text-3xl md:text-5xl xl:text-6xl font-bold text-black mb-6 leading-tight tracking-heading">
            <?php echo wp_kses_post( $row2_col2_heading ); ?>
          </h2>
        <?php endif; ?>
        
        <?php if ( ! empty( $row2_col2_paragraph1 ) ) : ?>
          <p class="text-base lg:text-lg text-black font-light mb-6 leading-relaxed max-w-full lg:max-w-xl">
            <?php echo wp_kses_post( $row2_col2_paragraph1 ); ?>
          </p>
        <?php endif; ?>
        
        <?php if ( ! empty( $row2_col2_bullets ) ) : ?>
          <ul class="space-y-3 mb-6 ml-6 max-w-full lg:max-w-xl">
            <?php foreach ( $row2_col2_bullets as $bullet ) : ?>
              <?php if ( ! empty( $bullet['text'] ) ) : ?>
                <li class="text-base lg:text-lg text-black font-light leading-relaxed list-disc">
                  <?php echo wp_kses_post( $bullet['text'] ); ?>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        
        <?php if ( ! empty( $row2_col2_paragraph2 ) ) : ?>
          <p class="text-base lg:text-lg text-black font-light leading-relaxed max-w-full lg:max-w-xl">
            <?php echo wp_kses_post( $row2_col2_paragraph2 ); ?>
          </p>
        <?php endif; ?>
      </div>
      
    </div>
    
  </div>
</section>
