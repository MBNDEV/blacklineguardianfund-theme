<?php
/**
 * Page Content Sync - Import/Export page content as patterns or files.
 *
 * Use this for "structural" pages that should be consistent across environments
 * (e.g., About, Services, Privacy Policy).
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Page Sync submenu under Tools.
 */
function custom_theme_add_page_sync_menu() {
	add_management_page(
      __( 'Page Content Sync', 'mbn-theme' ),
      __( 'Page Content Sync', 'mbn-theme' ),
      'manage_options',
      'page-content-sync',
      'custom_theme_render_page_sync_page'
	);
}
add_action( 'admin_menu', 'custom_theme_add_page_sync_menu' );

/**
 * Get all pages eligible for sync (published pages only).
 *
 * @return array Array of WP_Post objects.
 */
function custom_theme_get_syncable_pages() {
	return get_posts(
      array(
		  'post_type'      => 'page',
		  'post_status'    => 'publish',
		  'posts_per_page' => -1,
		  'orderby'        => 'title',
		  'order'          => 'ASC',
	  )
	);
}

/**
 * Export a page's content to a pattern file.
 *
 * @param int $page_id Page ID to export.
 * @return bool|string File path on success, false on failure.
 * @throws Exception If export fails.
 */
function custom_theme_export_page_to_pattern( $page_id ) {
	$page = get_post( $page_id );

  if ( ! $page instanceof \WP_Post ) {
      throw new Exception( sprintf( 'Invalid page ID: %d. Post does not exist.', absint( $page_id ) ) );
  }

  if ( 'page' !== $page->post_type ) {
      throw new Exception( sprintf( 'Post ID %d is not a page (type: %s).', absint( $page_id ), esc_html( $page->post_type ) ) );
  }

	$slug       = $page->post_name;
	$title      = $page->post_title;
	$content    = $page->post_content;
	$excerpt    = $page->post_excerpt;
	$status     = $page->post_status;
	$parent     = $page->post_parent;
	$menu_order = $page->menu_order;
	$template   = get_page_template_slug( $page_id );

  if ( empty( $slug ) ) {
      throw new Exception( sprintf( 'Page "%s" has no slug. Please set a permalink.', esc_html( $title ) ) );
  }

	// Get featured image.
	$featured_image_id   = get_post_thumbnail_id( $page_id );
	$featured_image_url  = '';
	$featured_image_path = ''; // Theme-relative path for assets.

  if ( $featured_image_id ) {
      $image_url  = wp_get_attachment_url( $featured_image_id );
      $upload_dir = wp_upload_dir();

      // Check if image is in theme assets (preferred for structural pages).
    if ( strpos( $image_url, get_theme_file_uri( 'assets/' ) ) !== false ) {
        // Image is in theme assets - store relative path.
        $featured_image_path = str_replace( get_theme_file_uri( '' ), '', $image_url );
        $featured_image_path = ltrim( $featured_image_path, '/' );
    } else {
        // Image is in uploads folder (user content) - store full URL.
        $featured_image_url = $image_url;
    }
  }

	// Get all post meta.
	$all_meta      = get_post_meta( $page_id );
	$custom_fields = array();

	// Filter out WordPress internal meta (starts with _).
	// But keep some important ones like _wp_page_template.
	$export_meta_keys = array( '_wp_page_template' );

  foreach ( $all_meta as $key => $values ) {
      // Skip internal WordPress meta except whitelisted ones.
    if ( substr( $key, 0, 1 ) === '_' && ! in_array( $key, $export_meta_keys, true ) ) {
        continue;
    }

      // Store meta (handle serialized data).
      $custom_fields[ $key ] = is_array( $values ) && count( $values ) === 1 ? $values[0] : $values;
  }

	// Create pattern file.
	$pattern_dir = get_theme_file_path( 'template-parts/page-patterns' );

	// Create directory if it doesn't exist.
  if ( ! is_dir( $pattern_dir ) ) {
      // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
      $created = mkdir( $pattern_dir, 0755, true );
    if ( ! $created ) {
        throw new Exception( sprintf( 'Failed to create directory: %s. Check file permissions.', esc_html( $pattern_dir ) ) );
    }
  }

	// Check if directory is writable.
  if ( ! is_writable( $pattern_dir ) ) {
      throw new Exception( sprintf( 'Directory is not writable: %s. Check file permissions.', esc_html( $pattern_dir ) ) );
  }

	$file_content  = "<?php\n";
	$file_content .= "/**\n";
	$file_content .= " * Page Pattern: {$title}\n";
	$file_content .= " * \n";
	$file_content .= " * This file contains the complete page data for the '{$title}' page.\n";
	$file_content .= " * It can be imported to create/update the page on other environments.\n";
	$file_content .= " * \n";
	$file_content .= " * Includes: Content, Featured Image, Status, Attributes, Custom Fields\n";
	$file_content .= " * \n";
	$file_content .= " * To use: Tools → Page Content Sync → Import All Pages from Files\n";
	$file_content .= " * \n";
	$file_content .= " * @package CustomTheme\n";
	$file_content .= " */\n\n";
	$file_content .= "return array(\n";
	$file_content .= "\t'title'              => " . var_export( $title, true ) . ",\n";
	$file_content .= "\t'slug'               => " . var_export( $slug, true ) . ",\n";
	$file_content .= "\t'status'             => " . var_export( $status, true ) . ",\n";
	$file_content .= "\t'excerpt'            => " . var_export( $excerpt, true ) . ",\n";
	$file_content .= "\t'parent_slug'        => " . var_export( $parent > 0 ? get_post_field( 'post_name', $parent ) : '', true ) . ",\n";
	$file_content .= "\t'menu_order'         => " . var_export( $menu_order, true ) . ",\n";
	$file_content .= "\t'template'           => " . var_export( $template, true ) . ",\n";
	$file_content .= "\t'featured_image_url' => " . var_export( $featured_image_url, true ) . ",\n";
	$file_content .= "\t'featured_image_path' => " . var_export( $featured_image_path, true ) . ", // Theme assets path (ships via Git)\n";
	$file_content .= "\t'custom_fields'      => " . var_export( $custom_fields, true ) . ",\n";
	$file_content .= "\t'content'            => <<<'EOD'\n";
	$file_content .= $content . "\n";
	$file_content .= "EOD\n";
	$file_content .= ");\n";

	$file_path = $pattern_dir . '/' . $slug . '.php';

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	$written = file_put_contents( $file_path, $file_content );

  if ( false === $written ) {
      throw new Exception( sprintf( 'Failed to write file: %s. Check file permissions.', esc_html( $file_path ) ) );
  }

	return $file_path;
}

/**
 * Import an external image into the media library.
 *
 * @param string $image_url URL of the image to import.
 * @param int    $post_id   Post ID to attach the image to.
 * @return int Attachment ID on success, 0 on failure.
 */
function custom_theme_import_external_image( $image_url, $post_id = 0 ) {
  if ( empty( $image_url ) ) {
      return 0;
  }

	// Check if URL is accessible.
	$response = wp_remote_head( $image_url );
  if ( is_wp_error( $response ) ) {
      return 0;
  }

	// Download image.
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = download_url( $image_url );

  if ( is_wp_error( $tmp ) ) {
      return 0;
  }

	$file_array = array(
		'name'     => basename( $image_url ),
		'tmp_name' => $tmp,
	);

	// Upload to media library.
	$attachment_id = media_handle_sideload( $file_array, $post_id );

	// Clean up temp file.
	if ( file_exists( $tmp ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
		@unlink( $tmp );
	}

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	return $attachment_id;
}

/**
 * Get or create an attachment for a theme image file.
 *
 * @param string $file_path Absolute path to image file in theme.
 * @param int    $post_id   Post ID to attach to.
 * @return int Attachment ID, or 0 on failure.
 */
function custom_theme_get_or_create_theme_image_attachment( $file_path, $post_id = 0 ) {
  if ( ! file_exists( $file_path ) ) {
      return 0;
  }

	$filename = basename( $file_path );

	// Check if attachment already exists for this file.
	$existing = get_posts(
      array(
		  'post_type'      => 'attachment',
		  'post_status'    => 'inherit',
		  'posts_per_page' => 1,
		  'meta_query'     => array(
			  array(
				  'key'   => '_theme_asset_file',
				  'value' => $file_path,
			  ),
		  ),
	  )
	);

  if ( ! empty( $existing ) ) {
      return $existing[0]->ID;
  }

	// Create attachment.
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$filetype = wp_check_filetype( $filename );

	$attachment = array(
		'guid'           => get_theme_file_uri( str_replace( get_theme_file_path( '' ), '', $file_path ) ),
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attachment_id = wp_insert_attachment( $attachment, $file_path, $post_id );

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	// Generate attachment metadata.
	$attach_data = wp_generate_attachment_metadata( $attachment_id, $file_path );
	wp_update_attachment_metadata( $attachment_id, $attach_data );

	// Mark as theme asset for future lookups.
	update_post_meta( $attachment_id, '_theme_asset_file', $file_path );

	return $attachment_id;
}

/**
 * Import page content from pattern files.
 *
 * @return array Array with 'created', 'updated' counts, and optional 'errors'.
 * @throws Exception If import fails completely.
 */
function custom_theme_import_pages_from_patterns() {
	$pattern_dir = get_theme_file_path( 'template-parts/page-patterns' );

  if ( ! is_dir( $pattern_dir ) ) {
      throw new Exception( sprintf( 'Page patterns directory not found: %s', esc_html( $pattern_dir ) ) );
  }

  if ( ! is_readable( $pattern_dir ) ) {
      throw new Exception( sprintf( 'Page patterns directory is not readable: %s. Check file permissions.', esc_html( $pattern_dir ) ) );
  }

	$pattern_files = glob( $pattern_dir . '/*.php' );

  if ( empty( $pattern_files ) ) {
      throw new Exception( sprintf( 'No page pattern files found in: %s', esc_html( $pattern_dir ) ) );
  }

	$created = 0;
	$updated = 0;
	$errors  = array();

  foreach ( $pattern_files as $file ) {
    try {
      if ( ! is_readable( $file ) ) {
        throw new Exception( sprintf( 'File is not readable: %s', basename( $file ) ) );
      }

        $data = include $file;

      if ( ! is_array( $data ) ) {
          throw new Exception( sprintf( 'Invalid file format in %s: expected array, got %s', basename( $file ), gettype( $data ) ) );
      }

        // Required fields.
      if ( ! isset( $data['title'] ) || ! isset( $data['slug'] ) || ! isset( $data['content'] ) ) {
          throw new Exception( sprintf( 'Missing required fields (title, slug, content) in %s', basename( $file ) ) );
      }

        // Optional fields with defaults.
        $status              = isset( $data['status'] ) ? $data['status'] : 'publish';
        $excerpt             = isset( $data['excerpt'] ) ? $data['excerpt'] : '';
        $parent_slug         = isset( $data['parent_slug'] ) ? $data['parent_slug'] : '';
        $menu_order          = isset( $data['menu_order'] ) ? (int) $data['menu_order'] : 0;
        $template            = isset( $data['template'] ) ? $data['template'] : '';
        $featured_image_path = isset( $data['featured_image_path'] ) ? $data['featured_image_path'] : '';
        $featured_image_url  = isset( $data['featured_image_url'] ) ? $data['featured_image_url'] : '';
        $custom_fields       = isset( $data['custom_fields'] ) && is_array( $data['custom_fields'] ) ? $data['custom_fields'] : array();

        // Resolve parent page ID from slug.
        $parent_id = 0;
      if ( ! empty( $parent_slug ) ) {
          $parent_page = get_page_by_path( $parent_slug, OBJECT, 'page' );
        if ( $parent_page instanceof \WP_Post ) {
            $parent_id = $parent_page->ID;
        }
      }

        // Check if page exists.
        $existing = get_page_by_path( $data['slug'], OBJECT, 'page' );

        $post_data = array(
            'post_type'    => 'page',
            'post_title'   => $data['title'],
            'post_name'    => $data['slug'],
            'post_content' => $data['content'],
            'post_excerpt' => $excerpt,
            'post_status'  => $status,
            'post_parent'  => $parent_id,
            'menu_order'   => $menu_order,
        );

        if ( $existing instanceof \WP_Post ) {
            // Update existing page.
            $post_data['ID'] = $existing->ID;

            $result = wp_update_post( $post_data, true );

          if ( is_wp_error( $result ) ) {
              throw new Exception( sprintf( 'Failed to update page "%s": %s', $data['title'], $result->get_error_message() ) );
          }

            $page_id = $existing->ID;
            ++$updated;
        } else {
            // Create new page.
            $result = wp_insert_post( $post_data, true );

          if ( is_wp_error( $result ) ) {
              throw new Exception( sprintf( 'Failed to create page "%s": %s', $data['title'], $result->get_error_message() ) );
          }

            $page_id = $result;
            ++$created;
        }

        // Set page template.
        if ( ! empty( $template ) ) {
            update_post_meta( $page_id, '_wp_page_template', $template );
        }

        // Handle featured image.
        if ( ! empty( $featured_image_path ) ) {
            // Image is in theme assets - attach by path (preferred).
            $theme_image_path = get_theme_file_path( $featured_image_path );

          if ( file_exists( $theme_image_path ) ) {
              $attachment_id = custom_theme_get_or_create_theme_image_attachment( $theme_image_path, $page_id );
            if ( $attachment_id > 0 ) {
              set_post_thumbnail( $page_id, $attachment_id );
            }
          }
        } elseif ( ! empty( $featured_image_url ) ) {
            // Image is external URL - try to find or download.
            $attachment_id = attachment_url_to_postid( $featured_image_url );

          if ( $attachment_id > 0 ) {
              set_post_thumbnail( $page_id, $attachment_id );
          } else {
              // Image doesn't exist in media library - need to download it.
              // Note: This requires the image to be accessible from staging.
              $image_id = custom_theme_import_external_image( $featured_image_url, $page_id );
            if ( $image_id > 0 ) {
                set_post_thumbnail( $page_id, $image_id );
            }
          }
        }

        // Set custom fields.
        if ( ! empty( $custom_fields ) ) {
          foreach ( $custom_fields as $meta_key => $meta_value ) {
              update_post_meta( $page_id, $meta_key, $meta_value );
          }
        }
    } catch ( Exception $e ) {
        $errors[] = basename( $file ) . ': ' . $e->getMessage();
    }
  }

	// Return results.
	$result = array(
		'created' => $created,
		'updated' => $updated,
	);

	if ( ! empty( $errors ) ) {
		$result['errors'] = $errors;
	}

	// If nothing was imported and we have errors, throw exception.
	if ( 0 === $created && 0 === $updated && ! empty( $errors ) ) {
		throw new Exception( 'Import failed: ' . implode( ' | ', array_map( 'esc_html', $errors ) ) );
	}

	return $result;
}

/**
 * Handle page sync actions.
 *
 * @throws Exception If export fails.
 */
function custom_theme_handle_page_sync_actions() {
  if ( ! isset( $_POST['custom_theme_page_sync_action'] ) ) {
      return;
  }

  if ( ! current_user_can( 'manage_options' ) ) {
      return;
  }

	check_admin_referer( 'custom_theme_page_sync', 'custom_theme_page_sync_nonce' );

	$action = sanitize_text_field( $_POST['custom_theme_page_sync_action'] );

  if ( 'export_pages' === $action ) {
    try {
        $page_ids = isset( $_POST['page_ids'] ) ? array_map( 'intval', (array) $_POST['page_ids'] ) : array();

      if ( empty( $page_ids ) ) {
        throw new Exception( __( 'Please select at least one page to export.', 'mbn-theme' ) );
      }

        $exported = 0;
        $errors   = array();

      foreach ( $page_ids as $page_id ) {
        try {
          $file_path = custom_theme_export_page_to_pattern( $page_id );
          if ( $file_path ) {
              ++$exported;
          }
        } catch ( Exception $e ) {
            $errors[] = sprintf( 'Page ID %d: %s', $page_id, $e->getMessage() );
        }
      }

      if ( $exported > 0 ) {
          $message = sprintf(
              // translators: %d is the number of pages exported.
            __( '%d page(s) exported to template-parts/page-patterns/ successfully!', 'mbn-theme' ),
            $exported
          );

        if ( ! empty( $errors ) ) {
          $message .= ' ' . __( 'Errors:', 'mbn-theme' ) . ' ' . implode( '; ', $errors );
        }

          add_settings_error(
            'custom_theme_page_sync',
            'export_success',
            $message,
            empty( $errors ) ? 'success' : 'warning'
          );
      } else {
          throw new Exception(
            __( 'No pages were exported. ', 'mbn-theme' ) .
              ( ! empty( $errors ) ? implode( '; ', $errors ) : '' )
          );
      }
    } catch ( Exception $e ) {
        add_settings_error(
          'custom_theme_page_sync',
          'export_error',
          sprintf(
                // translators: %s is the error message.
            __( 'Export failed: %s', 'mbn-theme' ),
            $e->getMessage()
          ),
          'error'
        );
    }
  } elseif ( 'import_pages' === $action ) {
    try {
        $result = custom_theme_import_pages_from_patterns();

        $message = sprintf(
            // translators: %1$d is pages created, %2$d is pages updated.
          __( 'Import complete! Created: %1$d, Updated: %2$d', 'mbn-theme' ),
          $result['created'],
          $result['updated']
        );

      if ( isset( $result['errors'] ) && ! empty( $result['errors'] ) ) {
        $message .= ' | ' . __( 'Errors:', 'mbn-theme' ) . ' ' . implode( '; ', $result['errors'] );
      }

        add_settings_error(
          'custom_theme_page_sync',
          'import_success',
          $message,
          ( isset( $result['errors'] ) && ! empty( $result['errors'] ) ) ? 'warning' : 'success'
        );
    } catch ( Exception $e ) {
        add_settings_error(
          'custom_theme_page_sync',
          'import_error',
          sprintf(
                // translators: %s is the error message.
            __( 'Import failed: %s', 'mbn-theme' ),
            $e->getMessage()
          ),
          'error'
        );
    }
  }
}
add_action( 'admin_init', 'custom_theme_handle_page_sync_actions' );

/**
 * Render Page Content Sync page.
 */
function custom_theme_render_page_sync_page() {
	$pages = custom_theme_get_syncable_pages();
  ?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Page Content Sync', 'mbn-theme' ); ?></h1>
		
		<?php settings_errors( 'custom_theme_page_sync' ); ?>

		<div class="card" style="max-width: 900px; background: #fff3cd; border-left: 4px solid #ffc107;">
			<h2>⚠️ Important: Development Workflow</h2>
			
			<h3 style="margin-top: 0;">✅ Current Phase: Dev-Only Workflow</h3>
			<p><strong>ALL page changes should be done on LOCAL only:</strong></p>
			<ul style="margin-left: 20px;">
				<li>✅ Page content (blocks)</li>
				<li>✅ Featured images (use <code>assets/images/</code> for structural images)</li>
				<li>✅ Page URL (slug)</li>
				<li>✅ Page status (publish/draft)</li>
				<li>✅ Page attributes (parent, template, order)</li>
				<li>✅ Custom fields/meta</li>
			</ul>
			<p><strong style="color: #d63638;">⚠️ DO NOT edit pages directly on staging/production!</strong><br>
			Local changes will overwrite any staging/production edits during import.</p>
			
			<hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
			
			<h3>🔮 Future Phase: When Clients Edit Production</h3>
			<p>When clients start editing production pages, this system will need updates to prevent conflicts:</p>
			<ul style="margin-left: 20px;">
				<li>📝 <strong>Page ownership flags</strong> - Mark pages as "dev-managed" or "client-managed"</li>
				<li>🔒 <strong>Import protection</strong> - Skip client-managed pages during import</li>
				<li>⚠️ <strong>Conflict detection</strong> - Warn when production has newer changes</li>
			</ul>
			<p style="margin-bottom: 5px;"><strong>Recommended strategy for MVP 2:</strong></p>
			<ul style="margin-left: 20px;">
				<li>Devs continue to manage structural pages (About, Services, etc.)</li>
				<li>Clients create NEW pages or edit blog posts only</li>
				<li>Add "Lock from imports" option for client-edited pages</li>
			</ul>
			<p style="font-size: 12px; color: #666; margin-top: 10px;">
				📖 See <code>DEVELOPMENT-STRATEGY.md</code> for detailed implementation plans.
			</p>
			
			<hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
			
			<p><strong>💡 Better Alternative for reusable layouts:</strong> Use <strong>Block Patterns</strong> (in <code>inc/includes-block-patterns.php</code>) 
			for repeatable page layouts. Patterns ship via Git automatically and don't require database sync.</p>
		</div>

		<div class="card" style="max-width: 900px; margin-top: 20px;">
			<h2>📤 Export Pages to Files</h2>
			<p>Select pages below to export <strong>ALL page data</strong> to <code>template-parts/page-patterns/</code> folder.</p>
			<p><strong>Exported data includes:</strong></p>
			<ul style="margin-left: 20px;">
				<li>✅ Page content (blocks)</li>
				<li>✅ Page title & slug (URL)</li>
				<li>✅ Page status (publish, draft, private)</li>
				<li>✅ Featured image (from theme assets or uploads)</li>
				<li>✅ Page template</li>
				<li>✅ Parent page & menu order</li>
				<li>✅ Custom fields/meta</li>
			</ul>
			
			<div style="background: #e7f3ff; border-left: 4px solid #2271b1; padding: 10px 15px; margin: 15px 0;">
				<strong>💡 Pro Tip: Using Theme Assets for Images</strong>
				<p style="margin: 5px 0;">For images that should ship via Git (hero backgrounds, team photos, etc.):</p>
				<ol style="margin-left: 20px; margin-bottom: 5px;">
					<li>Place images in <code>assets/images/</code> folder</li>
					<li>Use them as featured images or in blocks</li>
					<li>Export will save as: <code>'featured_image_path' => 'assets/images/hero.jpg'</code></li>
					<li>On import, image ships via Git automatically ✅</li>
				</ol>
				<p style="margin: 5px 0; font-size: 12px; color: #666;">
					See <code>assets/images/README.md</code> for guidelines.
				</p>
			</div>
			
			<?php if ( empty( $pages ) ) : ?>
				<p><em>No published pages found.</em></p>
			<?php else : ?>
				<form method="post" style="margin-top: 20px;">
					<?php wp_nonce_field( 'custom_theme_page_sync', 'custom_theme_page_sync_nonce' ); ?>
					<input type="hidden" name="custom_theme_page_sync_action" value="export_pages">
					
					<table class="widefat" style="margin-bottom: 20px;">
						<thead>
							<tr>
								<th style="width: 40px;">
									<input type="checkbox" id="select-all-pages" onclick="
										var checkboxes = document.querySelectorAll('input[name=\'page_ids[]\']');
										checkboxes.forEach(function(cb) { cb.checked = this.checked; }.bind(this));
									">
								</th>
								<th>Page Title</th>
								<th>Slug</th>
								<th>Status</th>
								<th>Featured Image</th>
								<th>Last Modified</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $pages as $page ) : ?>
								<?php
								$has_thumbnail = has_post_thumbnail( $page->ID );
								$page_template = get_page_template_slug( $page->ID );
								?>
								<tr>
									<td>
										<input type="checkbox" name="page_ids[]" value="<?php echo esc_attr( $page->ID ); ?>">
									</td>
									<td>
										<strong><?php echo esc_html( $page->post_title ); ?></strong>
										<?php if ( $page->post_parent > 0 ) : ?>
											<br><small style="color: #666;">↳ Child of: <?php echo esc_html( get_the_title( $page->post_parent ) ); ?></small>
										<?php endif; ?>
										<?php if ( ! empty( $page_template ) && 'default' !== $page_template ) : ?>
											<br><small style="color: #2271b1;">📄 Template: <?php echo esc_html( basename( $page_template, '.php' ) ); ?></small>
										<?php endif; ?>
										<div class="row-actions">
											<a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>" target="_blank">Edit</a> |
											<a href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>" target="_blank">View</a>
										</div>
									</td>
									<td><code><?php echo esc_html( $page->post_name ); ?></code></td>
									<td>
										<span class="status-<?php echo esc_attr( $page->post_status ); ?>">
											<?php echo esc_html( ucfirst( $page->post_status ) ); ?>
										</span>
									</td>
									<td style="text-align: center;">
										<?php if ( $has_thumbnail ) : ?>
											<span style="color: #46b450;" title="Has featured image">✓</span>
										<?php else : ?>
											<span style="color: #ddd;">—</span>
										<?php endif; ?>
									</td>
									<td><?php echo esc_html( get_the_modified_date( 'Y-m-d H:i', $page->ID ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
					<button type="submit" class="button button-primary">
						📤 Export Selected Pages to Files
					</button>
				</form>
			<?php endif; ?>
		</div>

		<div class="card" style="max-width: 900px; margin-top: 20px;">
			<h2>📥 Import Pages from Files</h2>
			<p>Import all pages from <code>template-parts/page-patterns/*.php</code> files.</p>
			<p>This will <strong>create new pages or update existing ones</strong> with matching slugs.</p>
			<p><strong>Imported data includes:</strong></p>
			<ul style="margin-left: 20px;">
				<li>✅ Page content, title, slug (URL)</li>
				<li>✅ Page status (publish, draft, private)</li>
				<li>✅ Featured image (auto-downloaded if URL is accessible)</li>
				<li>✅ Page template</li>
				<li>✅ Parent page & menu order</li>
				<li>✅ Custom fields/meta</li>
			</ul>
			<p><strong>⚠️ Warning:</strong> This will <span style="color: #d63638;">OVERWRITE existing pages</span> with the same slug. Local changes are the source of truth.</p>
			
			<form method="post" style="margin-top: 20px;">
				<?php wp_nonce_field( 'custom_theme_page_sync', 'custom_theme_page_sync_nonce' ); ?>
				<input type="hidden" name="custom_theme_page_sync_action" value="import_pages">
				<button type="submit" class="button button-secondary">
					📥 Import All Pages from Files
				</button>
			</form>
		</div>

		<div class="card" style="max-width: 900px; margin-top: 20px; background: #f0f6fc; border-left: 4px solid #0073aa;">
			<h2>📚 Workflow Guide</h2>
			
			<h3>Option 1: Block Patterns (Recommended)</h3>
			<p><strong>Best for:</strong> Repeatable page layouts across multiple websites</p>
			<ol>
				<li>Edit <code>inc/includes-block-patterns.php</code></li>
				<li>Create a pattern with your page layout</li>
				<li>Commit to Git</li>
				<li>On staging/production: Create page → Insert pattern → Customize text</li>
			</ol>
			<p><strong>Pros:</strong> Ships automatically, no sync needed, truly reusable<br>
			<strong>Cons:</strong> Page content must be customized per environment</p>

			<h3>Option 2: Page Export/Import (This Tool)</h3>
			<p><strong>Best for:</strong> Structural pages that are identical everywhere</p>
			<ol>
				<li>Create/edit page in WordPress admin</li>
				<li>Export it here (saves to <code>template-parts/page-patterns/</code>)</li>
				<li>Commit to Git</li>
				<li>On staging/production: Run Import here</li>
			</ol>
			<p><strong>Pros:</strong> Complete page content in Git<br>
			<strong>Cons:</strong> Manual sync required, overwrites existing pages</p>

			<h3>Option 3: Database Migrations</h3>
			<p><strong>Best for:</strong> Large sites with lots of content</p>
			<p>Use plugins like <strong>WP Migrate DB</strong> or <strong>WP CLI</strong> to export/import entire databases.</p>
		</div>
	</div>
	<?php
}
