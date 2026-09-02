process.env.WP_SOURCE_PATH = 'resources/blocks';
process.env.WP_BLOCKS_MANIFEST = true; // TODO: only add this when blocks are present in the project?

const { resolve } = require( 'node:path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(), // TODO: only add this when blocks are present in the project?
		example: './resources/scripts/example.ts', // TODO: Rename index to package name?
	},
	output: {
		...defaultConfig.output,
		path: resolve( __dirname, 'public' ),
	},
};
