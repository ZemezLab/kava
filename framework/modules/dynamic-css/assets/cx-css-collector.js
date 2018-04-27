/**
 * Handler for CSS Collector
 */
function CxCSSCollector() {

	'use strict';

	var style,
		collectedCSS = window.CxCollectedCSS;

	if ( undefined !== collectedCSS ) {

		style = document.createElement( 'style' );

		style.setAttribute( 'title', collectedCSS.title );
		style.setAttribute( 'type', collectedCSS.type );

		style.textContent = collectedCSS.css;

		document.head.appendChild( style );
	}
}

CxCSSCollector();
