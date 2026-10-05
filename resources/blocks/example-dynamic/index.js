import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import icon from './icon';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon,
} );
