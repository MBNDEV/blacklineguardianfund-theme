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
	'title'              => "Homepage",
	'slug'               => "homepage",
	'status'             => "publish",
	'excerpt'            => "",
	'parent_slug'        => "",
	'menu_order'         => 0,
	'template'           => "",
	'featured_image_url' => "",
	'featured_image_path' => "", // Theme assets path (ships via Git)
	'custom_fields'      => {"_wp_page_template":"default"},
	'content'            => <<<'EOD'
<!-- wp:mbn-theme/hero-section {"backgroundImageUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-hero-bg-homepage.jpg","backgroundImageId":28,"overlayBreakpoint":"md","primaryButtonUrl":"/donate/"} /-->

<!-- wp:mbn-theme/intro-section {"bgImageUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-bg-why-we-exist-full-v2.jpg","bgImageId":119,"photoUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/img-why-we-exist.png","photoId":29,"cards":[{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-school.svg","label":"Schools"},{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-church.svg","label":"Houses of Worship"},{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-public-figure.svg","label":"Public Figures"},{"iconUrl":"https://blacklineguardianfund.dev.local/wp-content/uploads/2026/04/icn-shield-family.svg","label":"Families"}]} /-->

<!-- wp:mbn-theme/donation-options {"cards":[{"title":"Support Training","amount":"$250","description":"One of the ways we put your $250 donation to work is in the area of protective services, which includes","bullets":["Safety training for a school","A house of worship","A community group"],"buttonText":"Donate Securely","buttonUrl":"/donate/","bgColor":"card-cream"},{"title":"Fund Readiness Resources","amount":"$500","description":"By way of illustration, your $500 donation can support the purchase and distribution of","bullets":["Medical kits","Radios","Emergency supplies to at-risk locations"],"buttonText":"Donate Securely","buttonUrl":"/donate/","bgColor":"card-gold"},{"title":"Underwrite Protection Services","amount":"$1,000","description":"Your $1,000 donation can help in a variety of important ways, including protective services for those in harm's way","bullets":["Families","Public Figures","Others facing serious threats, for instance"],"buttonText":"Donate Securely","buttonUrl":"/donate/","bgColor":"card-beige"}]} /-->
EOD
);
