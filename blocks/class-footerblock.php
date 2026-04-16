<?php
/**
 * Site Footer — Carbon Fields Gutenberg block.
 *
 * @package CustomTheme
 */

namespace CustomTheme\Blocks;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Registers fields and renders the footer output in blocks-render/.
 */
final class FooterBlock extends Abstract_Block {

  /**
   * Register the block with Carbon Fields.
   *
   * @return void
   */
  public static function register(): void {
    Container::make( 'block', __( 'Site Footer', 'blacklineguardianfund-theme' ) )
      ->set_description( __( 'Footer with logo, description, email, and social media links', 'blacklineguardianfund-theme' ) )
      ->set_icon( 'editor-kitchensink' )
      ->set_category( 'mbn-blocks' )
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
      'blocks-render/render-footerblock',
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
		// Logo & About Section.
		Field::make( 'image', 'footer_logo', __( 'Footer Logo', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Upload your footer logo', 'blacklineguardianfund-theme' ) ),

		Field::make( 'textarea', 'footer_text', __( 'Footer Description', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Brief description about your organization', 'blacklineguardianfund-theme' ) ),

		Field::make( 'text', 'footer_email', __( 'Email Address', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Email address for contact (e.g., info@example.com)', 'blacklineguardianfund-theme' ) ),

		// Social Media Links Section.
		Field::make( 'text', 'social_facebook_url', __( 'Facebook URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Full URL to Facebook profile', 'blacklineguardianfund-theme' ) ),

		Field::make( 'image', 'social_facebook_icon', __( 'Facebook Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Facebook icon/logo from Media Library', 'blacklineguardianfund-theme' ) ),

		Field::make( 'text', 'social_instagram_url', __( 'Instagram URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Full URL to Instagram profile', 'blacklineguardianfund-theme' ) ),

		Field::make( 'image', 'social_instagram_icon', __( 'Instagram Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Instagram icon/logo from Media Library', 'blacklineguardianfund-theme' ) ),

		Field::make( 'text', 'social_x_url', __( 'X (Twitter) URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Full URL to X/Twitter profile', 'blacklineguardianfund-theme' ) ),

		Field::make( 'image', 'social_x_icon', __( 'X Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'X/Twitter icon/logo from Media Library', 'blacklineguardianfund-theme' ) ),

		Field::make( 'text', 'social_linkedin_url', __( 'LinkedIn URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Full URL to LinkedIn profile', 'blacklineguardianfund-theme' ) ),

		Field::make( 'image', 'social_linkedin_icon', __( 'LinkedIn Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'LinkedIn icon/logo from Media Library', 'blacklineguardianfund-theme' ) ),

		Field::make( 'text', 'social_youtube_url', __( 'YouTube URL', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'Full URL to YouTube channel', 'blacklineguardianfund-theme' ) ),

		Field::make( 'image', 'social_youtube_icon', __( 'YouTube Icon', 'blacklineguardianfund-theme' ) )
		  ->set_help_text( __( 'YouTube icon/logo from Media Library', 'blacklineguardianfund-theme' ) ),
    );
  }

  /**
   * Map stored field keys to template part arguments.
   *
   * @param array<string, mixed> $fields Raw field values.
   * @return array<string, mixed>
   */
  private static function map_fields_to_template_args( array $fields ): array {
    // Build social links with icon URLs.
    $social_links = self::build_social_links( $fields );

    return array_merge(
      array(
		  'logo_url'     => $fields['footer_logo'] ? wp_get_attachment_url( $fields['footer_logo'] ) : null,
		  'footer_text'  => $fields['footer_text'] ?? '',
		  'footer_email' => $fields['footer_email'] ?? '',
		  'social_links' => $social_links,
      ),
      Abstract_Block::map_advanced_fields_to_template_args( $fields )
    );
  }

  /**
   * Build social links array with icon URLs.
   *
   * @param array<string, mixed> $fields Raw field values.
   * @return array<string, array<string, mixed>>
   */
  private static function build_social_links( array $fields ): array {
    $social_keys  = array( 'facebook', 'instagram', 'x', 'linkedin', 'youtube' );
    $social_links = array();

    foreach ( $social_keys as $key ) {
      $url_key  = "social_{$key}_url";
      $icon_key = "social_{$key}_icon";
      $url      = $fields[ $url_key ] ?? '';
      $icon_id  = $fields[ $icon_key ] ?? null;

      if ( $url && $icon_id ) {
        $social_links[ $key ] = array(
			'url'      => $url,
			'icon_url' => wp_get_attachment_url( $icon_id ),
			'label'    => ( 'x' === $key ) ? __( 'X (Twitter)', 'blacklineguardianfund-theme' ) : ucfirst( $key ),
        );
      }
    }

    return $social_links;
  }
}
