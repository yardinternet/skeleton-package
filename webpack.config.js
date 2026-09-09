process.env.WP_SOURCE_PATH = 'resources/blocks';
process.env.WP_BLOCKS_MANIFEST = true; // TODO: only add this when blocks are present in the project?

const { resolve } = require( 'node:path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const {
	addPackagesToConfig,
} = require( '@yardinternet/gutenberg-webpack-loaders' );

module.exports = {
	...addPackagesToConfig( defaultConfig, [
		'@yardinternet/gutenberg-components',
	] ),
	entry: {
		...defaultConfig.entry(), // TODO: only add this when blocks are present in the project?
		'example-component': './resources/scripts/example-component.ts',
		admin: './resources/scripts/admin.ts',
	},
	output: {
		...defaultConfig.output,
		path: resolve( __dirname, 'public' ),
	},
};
