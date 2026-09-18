/* Woo Custom SASS/CSS — admin
 *
 * Wires the Extra CSS textareas to the CodeMirror instance WordPress has
 * shipped since 4.9 (wp_enqueue_code_editor). The plugin used to bundle its
 * own copy of CodeMirror, but both codemirror.min.js and codemirror.min.css
 * were zero-byte files, so `CodeMirror` was never defined and the editor
 * silently stayed a plain textarea.
 */
( function ( wp ) {
	'use strict';

	function init() {
		// querySelectorAll, not querySelector: the Extra CSS section has a
		// global editor plus one per breakpoint.
		var textareas = document.querySelectorAll( 'textarea.codemirror_text' );

		if ( ! textareas.length ) {
			return;
		}

		// Absent when the merchant disabled syntax highlighting in their
		// profile, or when this is not the Extra CSS section.
		if ( ! wp || ! wp.codeEditor || 'undefined' === typeof wooCustomCssEditor ) {
			return;
		}

		textareas.forEach( function ( textarea ) {
			wp.codeEditor.initialize( textarea, wooCustomCssEditor );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}( window.wp ) );
