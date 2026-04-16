const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const glob = require( 'glob' );
const CopyPlugin = require( 'copy-webpack-plugin' );

// Auto-discover block entry points (supports template sub-folders).
// Set BLOCK env var to filter: e.g. BLOCK=template-1/coupons-1 or BLOCK=template-1
const blockFilter = process.env.BLOCK || '';
const blockEntries = {};
const blockFiles = glob.sync( './blocks/src/*/*/index.{tsx,ts,jsx,js}' );

blockFiles.forEach( ( file ) => {
  const match = file.match( /blocks\/src\/(.+?\/.+?)\/index\./ );
  if ( match ) {
    const key = `blocks/${ match[ 1 ] }/index`;
    if ( ! blockFilter || match[ 1 ].includes( blockFilter ) ) {
      blockEntries[ key ] = path.resolve( file );
    }
  }
} );

// Copy block.json and render.php into build/blocks/{template}/{name}/.
const copyPatterns = [];
const blockDirs = glob.sync( './blocks/src/*/*/' );

blockDirs.forEach( ( dir ) => {
  const match = dir.match( /blocks\/src\/(.+?\/.+?)\/$/ );
  const name = match?.[ 1 ];
  if ( ! name ) {
    return;
  }
  if ( blockFilter && ! name.includes( blockFilter ) ) {
    return;
  }
  copyPatterns.push(
    {
      from: path.resolve( dir, 'block.json' ),
      to: path.resolve( __dirname, `build/blocks/${ name }/block.json` ),
    },
    {
      from: path.resolve( dir, 'render.php' ),
      to: path.resolve( __dirname, `build/blocks/${ name }/render.php` ),
      noErrorOnMissing: true,
    }
  );
} );

// When filtering, exclude non-block entries and disable output cleaning
// so previously built blocks are preserved.
const entryPoints = blockFilter
  ? { ...blockEntries }
  : {
      ...blockEntries,
      'editor-sidebar/index': path.resolve(
        './assets/js/src/editor/sidebar/index.tsx'
      ),
      'editor-ai-chat/index': path.resolve(
        './assets/js/src/editor/ai-chat/index.tsx'
      ),
    };

module.exports = {
  ...defaultConfig,
  entry: entryPoints,
  output: {
    ...defaultConfig.output,
    path: path.resolve( __dirname, 'build' ),
    clean: ! blockFilter,
  },
  plugins: [
    ...( defaultConfig.plugins || [] ),
    ...( copyPatterns.length ? [ new CopyPlugin( { patterns: copyPatterns } ) ] : [] ),
  ],
};
