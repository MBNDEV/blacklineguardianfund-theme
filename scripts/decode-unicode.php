<?php
/**
 * Decode JSON Unicode escape sequences in page pattern files.
 *
 * This script fixes the \u003c and \u003e escape sequences in page pattern files.
 *
 * @package CustomTheme
 */

$pattern_dir = __DIR__ . '/../template-parts/page-patterns';

if ( ! is_dir( $pattern_dir ) ) {
	die( "Pattern directory not found: {$pattern_dir}\n" );
}

$files = glob( $pattern_dir . '/*.php' );

if ( empty( $files ) ) {
	die( "No pattern files found\n" );
}

$replacements = array(
	'\u003c' => '<',
	'\u003e' => '>',
	'\u0022' => '"',
	'\u0027' => "'",
	'\u0026' => '&',
);

foreach ( $files as $file ) {
	$content = file_get_contents( $file );
	
	if ( false === $content ) {
		echo "Error reading file: {$file}\n";
		continue;
	}
	
	// Check if file contains any Unicode escapes
	$needs_fix = false;
	foreach ( array_keys( $replacements ) as $pattern ) {
		if ( false !== strpos( $content, $pattern ) ) {
			$needs_fix = true;
			break;
		}
	}
	
	if ( ! $needs_fix ) {
		echo "Skipping (no escapes found): " . basename( $file ) . "\n";
		continue;
	}
	
	// Decode Unicode escapes
	$decoded = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
	
	// Write back
	$result = file_put_contents( $file, $decoded );
	
	if ( false === $result ) {
		echo "Error writing file: {$file}\n";
	} else {
		echo "Fixed: " . basename( $file ) . "\n";
	}
}

echo "\nDone!\n";
