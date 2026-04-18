<?php
/**
 * Template Sync Tools - Import/Export Block Templates.
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Template Tools submenu page.
 */
function custom_theme_add_template_tools_page() {
	add_submenu_page(
		'edit.php?post_type=carbon_template',
		__( 'Template Sync Tools', CUSTOM_THEME_TEXT_DOMAIN ),
		__( 'Sync Tools', CUSTOM_THEME_TEXT_DOMAIN ),
		'manage_options',
		'template-sync-tools',
		'custom_theme_render_template_tools_page'
	);
}
add_action( 'admin_menu', 'custom_theme_add_template_tools_page' );

/**
 * Handle sync actions.
 */
function custom_theme_handle_template_sync_actions() {
	if ( ! isset( $_POST['custom_theme_sync_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	check_admin_referer( 'custom_theme_sync_templates', 'custom_theme_sync_nonce' );

	$action = sanitize_text_field( $_POST['custom_theme_sync_action'] );

	if ( 'import_from_files' === $action ) {
		try {
			// Import template-parts/*.php files into Block Template posts
			custom_theme_maybe_seed_default_block_templates( true );
			add_settings_error(
				'custom_theme_sync',
				'sync_success',
				__( 'Templates imported from files successfully!', CUSTOM_THEME_TEXT_DOMAIN ),
				'success'
			);
		} catch ( Exception $e ) {
			add_settings_error(
				'custom_theme_sync',
				'import_error',
				sprintf(
					// translators: %s is the error message.
					__( 'Import failed: %s', CUSTOM_THEME_TEXT_DOMAIN ),
					$e->getMessage()
				),
				'error'
			);
		}
	} elseif ( 'export_to_files' === $action ) {
		try {
			// Export Block Template posts to template-parts/*.php files
			custom_theme_export_templates_to_files();
		} catch ( Exception $e ) {
			add_settings_error(
				'custom_theme_sync',
				'export_error',
				sprintf(
					// translators: %s is the error message.
					__( 'Export failed: %s', CUSTOM_THEME_TEXT_DOMAIN ),
					$e->getMessage()
				),
				'error'
			);
		}
	}
}
add_action( 'admin_init', 'custom_theme_handle_template_sync_actions' );

/**
 * Export Block Template posts to template-parts PHP files.
 *
 * @throws Exception If export fails.
 */
function custom_theme_export_templates_to_files() {
	$templates = array(
		custom_theme_header_template_slug() => 'header-template',
		custom_theme_footer_template_slug() => 'footer-template',
	);

	$exported = 0;
	$errors = array();
	
	// Check if template-parts directory exists and is writable
	$template_dir = get_theme_file_path( 'template-parts' );
	if ( ! is_dir( $template_dir ) ) {
		throw new Exception( sprintf( 'Template directory does not exist: %s', $template_dir ) );
	}
	
	if ( ! is_writable( $template_dir ) ) {
		throw new Exception( sprintf( 'Template directory is not writable: %s. Check file permissions.', $template_dir ) );
	}

	foreach ( $templates as $slug => $filename ) {
		try {
			$post_id = custom_theme_get_block_template_id_by_slug( $slug );
			if ( $post_id <= 0 ) {
				$errors[] = sprintf( 'Template post not found for slug: %s', $slug );
				continue;
			}

			$post = get_post( $post_id );
			if ( ! $post instanceof \WP_Post ) {
				$errors[] = sprintf( 'Invalid post object for ID: %d', $post_id );
				continue;
			}

			$content = $post->post_content;
			
			// Create file content with PHP header
			$file_content = "<?php\n";
			$file_content .= "/**\n";
			$file_content .= " * Default " . $post->post_title . " content.\n";
			$file_content .= " * \n";
			$file_content .= " * This file syncs to the \"" . $post->post_title . "\" Block Template post on theme activation.\n";
			$file_content .= " * Edit the Block Template post in WordPress admin, then export back to this file\n";
			$file_content .= " * using the export button in the admin.\n";
			$file_content .= " * \n";
			$file_content .= " * @package CustomTheme\n";
			$file_content .= " */\n\n";
			$file_content .= "if ( ! defined( 'ABSPATH' ) ) {\n";
			$file_content .= "\texit;\n";
			$file_content .= "}\n";
			$file_content .= "?>\n";
			$file_content .= $content;

			$file_path = get_theme_file_path( 'template-parts/' . $filename . '.php' );
			
			// Write file
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			$written = file_put_contents( $file_path, $file_content );
			
			if ( false === $written ) {
				throw new Exception( sprintf( 'Failed to write file: %s. Check file permissions.', $file_path ) );
			}
			
			$exported++;
		} catch ( Exception $e ) {
			$errors[] = sprintf( '%s: %s', $filename, $e->getMessage() );
		}
	}

	// Report results
	if ( $exported > 0 ) {
		$message = sprintf(
			// translators: %d is the number of templates exported.
			__( '%d template(s) exported to template-parts/ folder successfully!', CUSTOM_THEME_TEXT_DOMAIN ),
			$exported
		);
		
		if ( ! empty( $errors ) ) {
			$message .= ' ' . __( 'Errors:', CUSTOM_THEME_TEXT_DOMAIN ) . ' ' . implode( '; ', $errors );
		}
		
		add_settings_error(
			'custom_theme_sync',
			'export_success',
			$message,
			empty( $errors ) ? 'success' : 'warning'
		);
	} else {
		$error_message = __( 'No templates were exported.', CUSTOM_THEME_TEXT_DOMAIN );
		
		if ( ! empty( $errors ) ) {
			$error_message .= ' ' . __( 'Errors:', CUSTOM_THEME_TEXT_DOMAIN ) . ' ' . implode( '; ', $errors );
		} else {
			$error_message .= ' ' . __( 'Make sure Header Template and Footer Template posts exist.', CUSTOM_THEME_TEXT_DOMAIN );
		}
		
		throw new Exception( $error_message );
	}
}

/**
 * Render Template Sync Tools page.
 */
function custom_theme_render_template_tools_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Template Sync Tools', CUSTOM_THEME_TEXT_DOMAIN ); ?></h1>
		
		<?php settings_errors( 'custom_theme_sync' ); ?>

		<div class="card" style="max-width: 800px;">
			<h2>📥 Import Templates from Files</h2>
			<p>
				<strong>Use this to deploy to staging/production:</strong><br>
				Loads header/footer templates from <code>template-parts/header-template.php</code> and 
				<code>template-parts/footer-template.php</code> into the Block Template posts.
			</p>
			<p>
				This will <strong>overwrite</strong> your current Header Template and Footer Template posts 
				with the content from the PHP files.
			</p>
			<form method="post" style="margin-top: 20px;">
				<?php wp_nonce_field( 'custom_theme_sync_templates', 'custom_theme_sync_nonce' ); ?>
				<input type="hidden" name="custom_theme_sync_action" value="import_from_files">
				<button type="submit" class="button button-primary">
					📥 Import from Files (Overwrite Database)
				</button>
			</form>
		</div>

		<div class="card" style="max-width: 800px; margin-top: 20px;">
			<h2>📤 Export Templates to Files</h2>
			<p>
				<strong>Use this after editing in WordPress admin:</strong><br>
				Exports the current Header Template and Footer Template posts to 
				<code>template-parts/header-template.php</code> and <code>template-parts/footer-template.php</code>.
			</p>
			<p>
				This allows you to version control your templates and ship them via Git to other environments.
			</p>
			<form method="post" style="margin-top: 20px;">
				<?php wp_nonce_field( 'custom_theme_sync_templates', 'custom_theme_sync_nonce' ); ?>
				<input type="hidden" name="custom_theme_sync_action" value="export_to_files">
				<button type="submit" class="button button-secondary">
					📤 Export to Files (Update Git Files)
				</button>
			</form>
		</div>

		<div class="card" style="max-width: 800px; margin-top: 20px; background: #f0f6fc; border-left: 4px solid #0073aa;">
			<h2>ℹ️ Development Workflow</h2>
			<h3>Local Development:</h3>
			<ol>
				<li>Edit Header/Footer Templates in WordPress admin (Block Templates menu)</li>
				<li>Click <strong>"📤 Export to Files"</strong> button above</li>
				<li>Commit <code>template-parts/*.php</code> files to Git</li>
				<li>Push to GitHub</li>
			</ol>

			<h3>Staging/Production Deployment:</h3>
			<ol>
				<li>Pull latest code from Git</li>
				<li>Go to <strong>Block Templates → Sync Tools</strong></li>
				<li>Click <strong>"📥 Import from Files"</strong> button</li>
				<li>Templates are now updated!</li>
			</ol>

			<h3>For Page Content (Home, About, etc):</h3>
			<p>
				Use <strong>Block Patterns</strong> instead of building pages in the editor.<br>
				Patterns are defined in <code>inc/includes-block-patterns.php</code> and ship via Git automatically.
			</p>
			<p>
				To use a pattern:
			</p>
			<ol>
				<li>Edit a page in WordPress</li>
				<li>Click the <strong>"+"</strong> button to add a block</li>
				<li>Go to the <strong>"Patterns"</strong> tab</li>
				<li>Select <strong>"Black Line Security Ops"</strong> category</li>
				<li>Insert your pattern (e.g., "Complete Home Page")</li>
			</ol>
		</div>
	</div>
	<?php
}
