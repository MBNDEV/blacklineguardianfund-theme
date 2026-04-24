<?php
/**
 * Board Members Section - Server-side rendering
 *
 * @param array $attributes Block attributes
 * @param string $content Block content
 * @param WP_Block $block Block instance
 */

$background_image_url = $attributes['backgroundImageUrl'] ?? '';
$heading = $attributes['heading'] ?? 'Board Members';
$subtext = $attributes['subtext'] ?? '';
$members = $attributes['members'] ?? [];

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'relative w-full py-20 md:py-24 lg:py-32 overflow-hidden',
    'style' => $background_image_url 
        ? 'background-image: url(' . esc_url($background_image_url) . '); background-size: cover; background-repeat: no-repeat; background-color: #F9F5EE;' 
        : 'background-color: #F9F5EE;'
]);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="container mx-auto px-6 md:px-12 lg:px-16 max-w-7xl">

        <!-- Section Header -->
        <div class="text-center mb-12 md:mb-16">
            <?php if (!empty($heading)) : ?>
                <h2 class="font-sofia text-[40px] lg:text-[52px] font-bold leading-[1.1] tracking-[-0.02em] text-black mb-4">
                    <?php echo wp_kses_post($heading); ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($subtext)) : ?>
                <p class="text-base lg:text-lg font-light leading-relaxed text-dark-text max-w-3xl mx-auto">
                    <?php echo wp_kses_post($subtext); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Board Members Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <?php if (!empty($members) && is_array($members)) : ?>
                <?php foreach ($members as $member) : 
                    $name = $member['name'] ?? '';
                    $position = $member['position'] ?? '';
                    $description = $member['description'] ?? '';
                    $image_url = $member['imageUrl'] ?? '';
                ?>
                    <div class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
                        <?php if (!empty($image_url)) : ?>
                            <div class="aspect-[4/3] overflow-hidden">
                                <img
                                    src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr($name); ?>"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                />
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <?php if (!empty($name)) : ?>
                                <h3 class="text-xl lg:text-2xl font-bold font-sofia text-black mb-1">
                                    <?php echo esc_html($name); ?>
                                </h3>
                            <?php endif; ?>
                            
                            <?php if (!empty($position)) : ?>
                                <p class="text-sm font-medium text-gold mb-4">
                                    <?php echo esc_html($position); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (!empty($description)) : ?>
                                <p class="text-sm lg:text-base font-light leading-relaxed text-dark-text">
                                    <?php echo wp_kses_post($description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>
