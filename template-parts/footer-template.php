<?php
/**
 * Default Footer Template content.
 * 
 * This file syncs to the "Footer Template" Block Template post on theme activation.
 * Edit the Block Template post in WordPress admin, then export back to this file
 * using the export button in the admin (when implemented).
 * 
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">© <?php echo date('Y'); ?> Black Line Security Ops. All rights reserved.</p>
<!-- /wp:paragraph -->
