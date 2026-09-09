/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';

const Edit = ( { attributes } ) => (
	<div { ...useBlockProps() }>
		<ServerSideRender
			block={ metadata.name }
			attributes={ attributes }
		/>
	</div>
);

export default Edit;
