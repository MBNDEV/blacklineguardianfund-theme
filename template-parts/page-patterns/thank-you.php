<?php
/**
 * Page Pattern: Thank You
 * 
 * This file contains the complete page data for the 'Thank You' page.
 * It can be imported to create/update the page on other environments.
 * 
 * Includes: Content, Featured Image, Status, Attributes, Custom Fields
 * 
 * To use: Tools → Page Content Sync → Import All Pages from Files
 * 
 * @package CustomTheme
 */

return array(
	'title'              => "Thank You",
	'slug'               => "thank-you",
	'status'             => "publish",
	'excerpt'            => "",
	'parent_slug'        => "",
	'menu_order'         => 0,
	'template'           => "",
	'featured_image_url' => "",
	'featured_image_path' => "", // Theme assets path (ships via Git)
	'custom_fields'      => [],
	'content'            => <<<'EOD'
<!-- wp:mbn-theme/simple-hero-section {"subtitle":"Contact","heading":"Thank You","paragraph":"Thank you for contacting us, we will get back to you as soon as we can.","email":"","address":"","showPrimaryButton":true,"primaryButtonText":"Go back to Homepage","primaryButtonUrl":"/"} /-->
EOD
);
