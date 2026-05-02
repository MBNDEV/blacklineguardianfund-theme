<?php
/**
 * Replace domain URLs in page pattern files.
 *
 * Usage: php scripts/replace-domains.php --from="oldurl.com" --to="newurl.com"
 *
 * @package CustomTheme
 */

// Parse command line arguments
$options = getopt( '', array( 'from:', 'to:', 'dry-run' ) );

if ( ! isset( $options['from'] ) || ! isset( $options['to'] ) ) {
	echo "Usage: php scripts/replace-domains.php --from=\"oldurl.com\" --to=\"newurl.com\" [--dry-run]\n";
	echo "\nExample:\n";
	echo "  php scripts/replace-domains.php --from=\"blacklineguardianfund.dev.local\" --to=\"staging2.blacklineguardianfund.com\"\n";
	echo "\nOptions:\n";
	echo "  --from      Source domain to replace (without http/https)\n";
	echo "  --to        Target domain to use (without http/https)\n";
	echo "  --dry-run   Preview changes without modifying files\n";
	exit( 1 );
}

$from_domain  = trim( $options['from'] );
$to_domain    = trim( $options['to'] );
$dry_run      = isset( $options['dry-run'] );
$pattern_dir  = __DIR__ . '/../template-parts/page-patterns';

// Build search/replace arrays for both http and https
$search = array(
	'https://' . $from_domain,
	'http://' . $from_domain,
);

$replace = array(
	'https://' . $to_domain,
	'https://' . $to_domain, // Always use https for replaced URLs
);

if ( ! is_dir( $pattern_dir ) ) {
	echo "Error: Page patterns directory not found: {$pattern_dir}\n";
	exit( 1 );
}

$files = glob( $pattern_dir . '/*.php' );

if ( empty( $files ) ) {
	echo "No page pattern files found in: {$pattern_dir}\n";
	exit( 0 );
}

echo "\n" . ( $dry_run ? '🔍 DRY RUN MODE - No files will be modified' : '🔧 LIVE MODE - Files will be updated' ) . "\n";
echo "Replacing: {$search[0]} → {$replace[0]}\n";
echo "Replacing: {$search[1]} → {$replace[1]}\n";
echo str_repeat( '=', 70 ) . "\n\n";

$total_replacements = 0;
$files_changed      = 0;

foreach ( $files as $file ) {
	$content = file_get_contents( $file );
	
	if ( false === $content ) {
		echo "❌ Error reading: " . basename( $file ) . "\n";
		continue;
	}
	
	// Count occurrences
	$count = 0;
	foreach ( $search as $needle ) {
		$count += substr_count( $content, $needle );
	}
	
	if ( 0 === $count ) {
		echo "⏭️  Skipped (no matches): " . basename( $file ) . "\n";
		continue;
	}
	
	// Perform replacement
	$new_content = str_replace( $search, $replace, $content, $replaced_count );
	
	if ( $dry_run ) {
		echo "📝 Would replace {$replaced_count} occurrence(s) in: " . basename( $file ) . "\n";
	} else {
		$result = file_put_contents( $file, $new_content );
		
		if ( false === $result ) {
			echo "❌ Error writing: " . basename( $file ) . "\n";
		} else {
			echo "✅ Replaced {$replaced_count} occurrence(s) in: " . basename( $file ) . "\n";
			++$files_changed;
		}
	}
	
	$total_replacements += $replaced_count;
}

echo "\n" . str_repeat( '=', 70 ) . "\n";
echo "Summary:\n";
echo "  Total replacements: {$total_replacements}\n";
echo "  Files " . ( $dry_run ? 'to be changed' : 'changed' ) . ": {$files_changed}\n";
echo "  Files processed: " . count( $files ) . "\n";

if ( $dry_run ) {
	echo "\n💡 Run without --dry-run to apply changes.\n";
} else {
	echo "\n✅ Done! Don't forget to commit the changes.\n";
}

echo "\n";
