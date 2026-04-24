<?php
/**
 * Page Pattern: Homepage
 *
 * This file contains the complete page data for the 'Homepage' page.
 * It can be imported to create/update the page on other environments.
 *
 * Includes: Content, Featured Image, Status, Attributes, Custom Fields
 *
 * To use: Tools → Page Content Sync → Import All Pages from Files
 *
 * @package CustomTheme
 */

return array(
	'title'               => 'Homepage',
	'slug'                => 'homepage',
	'status'              => 'publish',
	'excerpt'             => '',
	'parent_slug'         => '',
	'menu_order'          => 0,
	'template'            => 'page-templates/template-blank.php',
	'featured_image_url'  => '',
	'featured_image_path' => '', // Theme assets path (ships via Git)
	'custom_fields'       => array( '_wp_page_template' => 'page-templates/template-blank.php' ),
	'content'             => <<<'EOD'
<!-- wp:mbn-theme/hero-section {"backgroundImageUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-hero-bg-homepage.jpg","backgroundImageId":28,"overlayBreakpoint":"md"} /-->

<!-- wp:mbn-theme/intro-section {"bgImageUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-bg-why-we-exist-full-v2.jpg","bgImageId":119,"photoUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-why-we-exist.png","photoId":29,"cards":[{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-school.svg","label":"Schools"},{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-church.svg","label":"Houses of Worship"},{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-public-figure.svg","label":"Public Figures"},{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-family.svg","label":"Families"}]} /-->

<!-- wp:mbn-theme/donation-options /-->
EOD
	,
);
