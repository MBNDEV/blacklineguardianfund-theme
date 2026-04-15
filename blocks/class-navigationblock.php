<?php
/**
 * Site Navigation — Carbon Fields Gutenberg block.
 *
 * @package CustomTheme
 */

namespace CustomTheme\Blocks;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Registers fields and renders the navigation output in blocks-render/.
 */
final class NavigationBlock extends Abstract_Block {

  /**
   * Register the block with Carbon Fields.
   *
   * @return void
   */
  public static function register(): void {
    Container::make( 'block', __( 'Site Navigation', 'blacklineguardianfund-theme' ) )
      ->set_description( __( 'Top navigation bar with logo, menu, and CTA button', 'blacklineguardianfund-theme' ) )
      ->set_category( 'mbn-blocks' )
      ->set_icon( 'menu' )
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
      'blocks-render/render-navigationblock',
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
		// Logo & Menu Section.
		Field::make( 'image', 'nav_logo', __( 'Logo', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Upload your site logo', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'nav_menu', __( 'Navigation Menu', 'blacklineguardianfund-theme' ) )
		  ->set_options(
			function () {
			  return self::get_menu_options();
			}
		  )
		  ->set_help_text( __( 'Select a menu to display in navigation', 'blacklineguardianfund-theme' ) ),

		// CTA Settings Section.
		Field::make( 'text', 'cta_text', __( 'CTA Button Text', 'blacklineguardianfund-theme' ) )
		  ->set_default_value( __( 'DONATE NOW', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'cta_link_type', __( 'CTA Link Type', 'blacklineguardianfund-theme' ) )
		  ->add_options(
			array(
				'page' => __( 'Select Page', 'blacklineguardianfund-theme' ),
				'url'  => __( 'Custom URL', 'blacklineguardianfund-theme' ),
			)
		  )
		  ->set_default_value( 'page' )
		  ->set_help_text( __( 'Choose whether to link to a page or a custom URL', 'blacklineguardianfund-theme' ) ),

		Field::make( 'select', 'cta_page', __( 'Select Page', 'blacklineguardianfund-theme' ) )
		  ->set_options(
			function () {
			  return self::get_page_options();
			}
		  )
		  ->set_help_text( __( 'Select a WordPress page to link to', 'blacklineguardianfund-theme' ) )
		  ->set_conditional_logic(
			array(
				array(
					'field'   => 'cta_link_type',
					'value'   => 'page',
					'compare' => '=',
				),
			)
		  ),

		Field::make( 'text', 'cta_custom_url', __( 'Custom URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Enter a URL (e.g., https://example.com or /donate)', 'blacklineguardianfund-theme' ) )
		  ->set_conditional_logic(
			array(
				array(
					'field'   => 'cta_link_type',
					'value'   => 'url',
					'compare' => '=',
				),
			)
		  ),

		Field::make( 'checkbox', 'cta_open_new_tab', __( 'Open to New Tab', 'blacklineguardianfund-theme' ) )
		  ->set_default_value( false ),

		Field::make( 'image', 'cta_icon', __( 'CTA Button Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Optional: Add an icon to the button', 'blacklineguardianfund-theme' ) ),
    );
  }

  /**
   * Map stored field keys to template part arguments.
   *
   * @param array<string, mixed> $fields Raw field values.
   * @return array<string, mixed>
   */
  private static function map_fields_to_template_args( array $fields ): array {
    $cta_link_type  = $fields['cta_link_type'] ?? 'page';
    $cta_page_id    = $fields['cta_page'] ?? null;
    $cta_custom_url = $fields['cta_custom_url'] ?? null;
    $cta_url        = self::get_cta_url( $cta_link_type, $cta_page_id, $cta_custom_url );

    return array_merge(
      array(
		  'logo_url'     => $fields['nav_logo'] ? wp_get_attachment_url( $fields['nav_logo'] ) : null,
		  'menu_id'      => $fields['nav_menu'] ?? null,
		  'cta_text'     => $fields['cta_text'] ?? __( 'DONATE NOW', 'blacklineguardianfund-theme' ),
		  'cta_url'      => $cta_url,
		  'cta_target'   => $fields['cta_open_new_tab'] ? '_blank' : '_self',
		  'cta_icon_url' => $fields['cta_icon'] ? wp_get_attachment_url( $fields['cta_icon'] ) : null,
      ),
      Abstract_Block::map_advanced_fields_to_template_args( $fields )
    );
  }

  /**
   * Get available menus as options.
   *
   * @return array<int|string, string>
   */
  private static function get_menu_options(): array {
    $menus   = get_terms(
      array(
		  'taxonomy'   => 'nav_menu',
		  'hide_empty' => true,
      )
    );
    $options = array();

    if ( ! is_wp_error( $menus ) ) {
      foreach ( $menus as $menu ) {
        $options[ $menu->term_id ] = $menu->name;
      }
    }

    return $options;
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
