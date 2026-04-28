<?php
/**
 * Page Pattern: Contact
 * 
 * This file contains the complete page data for the 'Contact' page.
 * It can be imported to create/update the page on other environments.
 * 
 * Includes: Content, Featured Image, Status, Attributes, Custom Fields
 * 
 * To use: Tools → Page Content Sync → Import All Pages from Files
 * 
 * @package CustomTheme
 */

return array(
	'title'              => "Contact",
	'slug'               => "contact",
	'status'             => "publish",
	'excerpt'            => "",
	'parent_slug'        => "",
	'menu_order'         => 0,
	'template'           => "",
	'featured_image_url' => "",
	'featured_image_path' => "", // Theme assets path (ships via Git)
	'custom_fields'      => [],
	'content'            => <<<'EOD'
<!-- wp:mbn-theme/simple-hero-section /-->

<!-- wp:mbn-theme/contact-form-section {"backgroundImageUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-bg-contact-us.jpg","backgroundImageId":227,"formShortcode":"[gravityform id=\u00221\u0022 title=\u0022false\u0022 description=\u0022false\u0022 ajax=\u0022true\u0022]"} /-->
EOD
);
