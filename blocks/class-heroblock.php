<?php
/**
 * Hero Block — Carbon Fields Gutenberg block.
 *
 * @package CustomTheme
 */

namespace CustomTheme\Blocks;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Registers fields and renders the hero block output in blocks-render/.
 */
final class HeroBlock extends Abstract_Block {

  /**
   * Register the block with Carbon Fields.
   *
   * @return void
   */
  public static function register(): void {
    Container::make( 'block', __( 'Hero', 'blacklineguardianfund-theme' ) )
      ->set_description( __( 'Large hero section with title, subtitle, CTA buttons, and background image', 'blacklineguardianfund-theme' ) )
      ->set_category( 'mbn-blocks' )
      ->set_icon( 'cover-image' )
      ->set_render_callback( array( self::class, 'render' ) )
      ->add_fields(
        array_merge(
          self::get_field_definitions(),
          Abstract_Block::get_advanced_field_definitions()
        )
      );
  }

  /**
   * Render callback for the block.
   *
   * @param array<string, mixed> $fields Carbon field values.
   * @return void
   */
  public static function render( $fields ): void {
    get_template_part(
      'blocks-render/render-heroblock',
      null,
      self::map_fields_to_template_args( is_array( $fields ) ? $fields : array() )
    );
  }

  /**
   * Field definitions for this block.
   *
   * @return array<int, mixed>
   */
  private static function get_field_definitions(): array {
    return array(
		// Background Section.
		Field::make( 'image', 'hero_background_image', __( 'Background Image', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Large background image for the hero section', 'blacklineguardianfund-theme' ) ),

		// Content Section.
		Field::make( 'text', 'hero_subtitle', __( 'Subtitle (Tagline)', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Small text above the title', 'blacklineguardianfund-theme' ) ),

		Field::make( 'textarea', 'hero_title', __( 'Title (H1)', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Main headline for the hero section', 'blacklineguardianfund-theme' ) ),

		// Primary CTA Button.
		Field::make( 'text', 'hero_primary_cta_text', __( 'Primary CTA Button Text', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Text for primary action button', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'hero_primary_cta_link_type', __( 'Primary CTA Link Type', 'blacklineguardianfund-theme' ) )
		  ->add_options(
			array(
				'page' => __( 'Select Page', 'blacklineguardianfund-theme' ),
				'url'  => __( 'Custom URL', 'blacklineguardianfund-theme' ),
			)
		  )
		  ->set_default_value( 'page' )
		  ->set_help_text( __( 'Choose whether to link to a page or a custom URL', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'hero_primary_cta_page', __( 'Select Page', 'blacklineguardianfund-theme' ) )
		  ->set_options(
			function () {
			  return self::get_page_options();
			}
		  )
		  ->set_help_text( __( 'Select a WordPress page to link to', 'blacklineguardianfund-theme' ) )
		  ->set_conditional_logic(
			array(
				array(
					'field'   => 'hero_primary_cta_link_type',
					'value'   => 'page',
					'compare' => '=',
				),
			)
		  ),

		Field::make( 'text', 'hero_primary_cta_custom_url', __( 'Custom URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Enter a URL (e.g., https://example.com or /donate)', 'blacklineguardianfund-theme' ) )
		  ->set_conditional_logic(
			array(
				array(
					'field'   => 'hero_primary_cta_link_type',
					'value'   => 'url',
					'compare' => '=',
				),
			)
		  ),

		Field::make( 'checkbox', 'hero_primary_cta_open_new_tab', __( 'Open Primary CTA in New Tab', 'blacklineguardianfund-theme' ) )
		  ->set_default_value( false ),

		Field::make( 'image', 'hero_primary_cta_icon', __( 'Primary CTA Button Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Optional: Add an icon to the primary button', 'blacklineguardianfund-theme' ) ),

		// Secondary CTA Button.
		Field::make( 'text', 'hero_secondary_cta_text', __( 'Secondary CTA Button Text', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Text for secondary action button', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'hero_secondary_cta_link_type', __( 'Secondary CTA Link Type', 'blacklineguardianfund-theme' ) )
		  ->add_options(
			array(
				'page' => __( 'Select Page', 'blacklineguardianfund-theme' ),
				'url'  => __( 'Custom URL', 'blacklineguardianfund-theme' ),
			)
		  )
		  ->set_default_value( 'page' )
		  ->set_help_text( __( 'Choose whether to link to a page or a custom URL', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'hero_secondary_cta_page', __( 'Select Page', 'blacklineguardianfund-theme' ) )
		  ->set_options(
			function () {
			  return self::get_page_options();
			}
		  )
		  ->set_help_text( __( 'Select a WordPress page to link to', 'blacklineguardianfund-theme' ) )
		  ->set_conditional_logic(
			array(
				array(
					'field'   => 'hero_secondary_cta_link_type',
					'value'   => 'page',
					'compare' => '=',
				),
			)
		  ),

		Field::make( 'text', 'hero_secondary_cta_custom_url', __( 'Custom URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Enter a URL (e.g., https://example.com or /learn-more)', 'blacklineguardianfund-theme' ) )
		  ->set_conditional_logic(
			array(
				array(
					'field'   => 'hero_secondary_cta_link_type',
					'value'   => 'url',
					'compare' => '=',
				),
			)
		  ),

		Field::make( 'checkbox', 'hero_secondary_cta_open_new_tab', __( 'Open Secondary CTA in New Tab', 'blacklineguardianfund-theme' ) )
		  ->set_default_value( false ),

		Field::make( 'image', 'hero_secondary_cta_icon', __( 'Secondary CTA Button Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Optional: Add an icon to the secondary button', 'blacklineguardianfund-theme' ) ),
    );
  }

  /**
   * Map stored field keys to template part arguments.
   *
   * @param array<string, mixed> $fields Raw field values.
   * @return array<string, mixed>
   */
  private static function map_fields_to_template_args( array $fields ): array {
    return array_merge(
      array(
		  'background_image_url' => $fields['hero_background_image'] ? wp_get_attachment_url( $fields['hero_background_image'] ) : null,
		  'subtitle'             => $fields['hero_subtitle'] ?? '',
		  'title'                => $fields['hero_title'] ?? '',
      ),
      self::get_primary_cta_args( $fields ),
      self::get_secondary_cta_args( $fields ),
      Abstract_Block::map_advanced_fields_to_template_args( $fields )
    );
  }

  /**
   * Extract primary CTA button arguments from fields.
   *
   * @param array<string, mixed> $fields Raw field values.
   * @return array<string, mixed>
   */
  private static function get_primary_cta_args( array $fields ): array {
    $link_type  = $fields['hero_primary_cta_link_type'] ?? 'page';
    $page_id    = $fields['hero_primary_cta_page'] ? (int) $fields['hero_primary_cta_page'] : null;
    $custom_url = $fields['hero_primary_cta_custom_url'] ?? null;
    $cta_url    = self::get_cta_url( $link_type, $page_id, $custom_url );

    return array(
		'primary_cta_text'     => $fields['hero_primary_cta_text'] ?? '',
		'primary_cta_url'      => $cta_url,
		'primary_cta_target'   => $fields['hero_primary_cta_open_new_tab'] ? '_blank' : '_self',
		'primary_cta_icon_url' => $fields['hero_primary_cta_icon'] ? wp_get_attachment_url( $fields['hero_primary_cta_icon'] ) : null,
    );
  }

  /**
   * Extract secondary CTA button arguments from fields.
   *
   * @param array<string, mixed> $fields Raw field values.
   * @return array<string, mixed>
   */
  private static function get_secondary_cta_args( array $fields ): array {
    $link_type  = $fields['hero_secondary_cta_link_type'] ?? 'page';
    $page_id    = $fields['hero_secondary_cta_page'] ? (int) $fields['hero_secondary_cta_page'] : null;
    $custom_url = $fields['hero_secondary_cta_custom_url'] ?? null;
    $cta_url    = self::get_cta_url( $link_type, $page_id, $custom_url );

    return array(
		'secondary_cta_text'     => $fields['hero_secondary_cta_text'] ?? '',
		'secondary_cta_url'      => $cta_url,
		'secondary_cta_target'   => $fields['hero_secondary_cta_open_new_tab'] ? '_blank' : '_self',
		'secondary_cta_icon_url' => $fields['hero_secondary_cta_icon'] ? wp_get_attachment_url( $fields['hero_secondary_cta_icon'] ) : null,
    );
  }

  /**
   * Get available pages as options.
   *
   * @return array<int|string, string>
   */
  private static function get_page_options(): array {
    $pages   = get_pages( array( 'number' => 999 ) );
    $options = array( '' => __( '-- Select a page --', 'blacklineguardianfund-theme' ) );

    foreach ( $pages as $page ) {
      $options[ $page->ID ] = $page->post_title;
    }

    return $options;
  }

  /**
   * Get the CTA URL based on link type.
   *
   * @param string      $link_type  The link type ('page' or 'url').
   * @param int|null    $page_id    The page ID.
   * @param string|null $custom_url The custom URL.
   * @return string|null
   */
  private static function get_cta_url( string $link_type, ?int $page_id, ?string $custom_url ): ?string {
    if ( 'page' === $link_type && $page_id ) {
      return get_permalink( $page_id );
    } elseif ( 'url' === $link_type && $custom_url ) {
      return $custom_url;
    }

    return null;
  }
}
