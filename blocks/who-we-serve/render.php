<?php
/**
 * Who We Serve Section - Server-side rendering
 *
 * @param array $attributes Block attributes
 * @param string $content Block content
 * @param WP_Block $block Block instance
 */

$background_image_url = $attributes['backgroundImageUrl'] ?? '';
$heading = $attributes['heading'] ?? 'Who We Serve';
$subtext = $attributes['subtext'] ?? '';
$cards = $attributes['cards'] ?? [];

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'relative w-full py-20 md:pt-32 md:pb-20 lg:pt32 lg:pb-20 overflow-hidden',
    'style' => $background_image_url 
        ? 'background-image: url(' . esc_url($background_image_url) . '); background-size: cover; background-repeat: no-repeat; background-color: #F9F5EE;' 
        : 'background-color: #f3f4f6;'
]);

/**
 * Get grid layout classes based on layout type
 */
if (!function_exists('mbn_who_we_serve_get_layout_classes')) {
    function mbn_who_we_serve_get_layout_classes($layout) {
        switch($layout) {
            case '35-65':
                return 'col-span-12 md:col-span-6 lg:col-span-5';
            case '65-35':
                return 'col-span-12 md:col-span-6 lg:col-span-7';
            case '50-50':
                return 'col-span-12 md:col-span-6';
            default:
                return 'col-span-12 md:col-span-6';
        }
    }
}
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="container mx-auto px-6 md:px-12 lg:px-16 max-w-full lg:max-w-7xl">

        <!-- Section Header -->
        <div class="text-center mb-14 md:mb-10">
            <?php if (!empty($heading)) : ?>
                <h2 class="font-sofia text-[40px] lg:text-[52px] font-bold leading-[1.1] tracking-[-0.04em] text-black mb-5">
                    <?php echo wp_kses_post($heading); ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($subtext)) : ?>
                <p class="text-lg font-light leading-relaxed text-black max-w-3xl mx-auto">
                    <?php echo wp_kses_post($subtext); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Asymmetric Image Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 lg:gap-5">
            <?php if (!empty($cards) && is_array($cards)) : ?>
                <?php foreach ($cards as $card) : 
                    $layout = $card['layout'] ?? '50-50';
                    $card_heading = $card['heading'] ?? '';
                    $card_text = $card['text'] ?? '';
                    $image_url = $card['imageUrl'] ?? '';
                    $layout_class = mbn_who_we_serve_get_layout_classes($layout);
                ?>
                    <div class="group relative overflow-hidden rounded-lg min-h-[400px] aspect-[3/3] lg:aspect-[4/3] shadow-md max-h-auto lg:max-h-[400px] w-full h-full <?php echo esc_attr($layout_class); ?>">
                        <?php if (!empty($image_url)) : ?>
                            <img
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($card_heading); ?>"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                            />
                        <?php endif; ?>
                        
                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        
                        <!-- Label -->
                        <div class="absolute bottom-0 left-0 right-0 p-5 lg:p-6">
                            <?php if (!empty($card_heading)) : ?>
                                <span class="block text-2xl sm:text-3xl font-bold leading-tight tracking-tight text-white">
                                    <?php echo wp_kses_post($card_heading); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($card_text)) : ?>
                                <p class="text-white text-lg font-light leading-normal mt-2">
                                    <?php echo wp_kses_post($card_text); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>
