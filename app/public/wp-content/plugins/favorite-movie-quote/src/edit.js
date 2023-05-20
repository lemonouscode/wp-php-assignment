import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { TextControl } from '@wordpress/components';
import metadata from './block.json';

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';


export default function Edit( { attributes, setAttributes } ) {

	// Destructuring fav_quote from block.json
	const { fav_quote } = attributes;

	// Function that will fire when character typed into input field for "favorite movie quote"
	const onChangeHeading = ( newMovieQuote ) => {
		setAttributes( { fav_quote: newMovieQuote } );
	};

	return (
		<>
			<InspectorControls>
				<TextControl
					label={ __( 'Enter Favorite Movie Quote', 'dynamic-block' ) }
					value={ fav_quote }
					onChange={ onChangeHeading }
				/>
			</InspectorControls>

			<div { ...useBlockProps() }>
					<ServerSideRender
						block={ metadata.name }
						skipBlockSupportAttributes
						attributes={ attributes }
					/>
			</div>
		</>
	);

}
