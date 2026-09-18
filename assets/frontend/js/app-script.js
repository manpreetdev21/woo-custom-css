/* Woo Custom SASS/CSS — frontend
 *
 * Two jobs:
 *
 *  1. Product image hover swap, for themes that render their own catalogue
 *     card and so never fire woocommerce_before_shop_loop_item_title. On
 *     classic markup the plugin does this server side and this script finds
 *     nothing to do — it skips any card that already has a second image.
 *
 *  2. The sale countdown, which genuinely needs a timer.
 */
( function () {
	'use strict';

	/* ----------------------------------------------------------------------
	 * Product image hover swap
	 * ------------------------------------------------------------------- */

	/**
	 * Cards are found by whichever id convention the markup uses: a
	 * data-product-id attribute (custom theme cards), WooCommerce's classic
	 * li.product.post-<id>, or the Product Collection block's
	 * li.wc-block-product.post-<id>.
	 *
	 * Each selector is anchored to a list item or an explicit product
	 * attribute so a bare .post-<id> on a single product page cannot match.
	 */
	function findCard( id ) {
		return document.querySelector(
			'[data-product-id="' + id + '"],' +
			'li.product.post-' + id + ',' +
			'li.wc-block-product.post-' + id + ',' +
			'li.post-' + id
		);
	}

	function overlayGalleryImage( card, url ) {
		// Server-side path already handled this one.
		if ( card.querySelector( '.product_img_2' ) ) {
			return;
		}

		var base = card.querySelector( 'img' );
		if ( ! base || ! base.parentElement ) {
			return;
		}

		var holder = base.parentElement;

		var second = document.createElement( 'img' );
		second.src = url;
		second.alt = '';
		second.loading = 'lazy';
		second.className = 'product_img_2';
		second.setAttribute( 'aria-hidden', 'true' );

		// Match the base image's own sizing so the swap does not jump.
		second.width = base.width || 0;
		second.height = base.height || 0;

		base.classList.add( 'product-img' );
		holder.classList.add( 'woo_pro_img' );
		holder.appendChild( second );
	}

	function initHoverSwap() {
		if ( typeof wooCustomCssGallery === 'undefined' || ! wooCustomCssGallery ) {
			return;
		}

		Object.keys( wooCustomCssGallery ).forEach( function ( id ) {
			var card = findCard( id );
			if ( card ) {
				overlayGalleryImage( card, wooCustomCssGallery[ id ] );
			}
		} );
	}

	/* ----------------------------------------------------------------------
	 * Sale countdown
	 * ------------------------------------------------------------------- */

	function pad( value ) {
		return String( value ).padStart( 2, '0' );
	}

	function startCountdown( element ) {
		var target = Date.parse( element.getAttribute( 'data-sale' ) );

		if ( isNaN( target ) ) {
			return;
		}

		function tick() {
			var distance = target - Date.now();

			if ( distance <= 0 ) {
				element.textContent = '';
				clearInterval( timer );
				return;
			}

			var days = Math.floor( distance / 86400000 );
			var hours = Math.floor( ( distance % 86400000 ) / 3600000 );
			var minutes = Math.floor( ( distance % 3600000 ) / 60000 );
			var seconds = Math.floor( ( distance % 60000 ) / 1000 );

			element.textContent =
				days + 'd ' + pad( hours ) + 'h ' + pad( minutes ) + 'm ' + pad( seconds ) + 's';
		}

		var timer = setInterval( tick, 1000 );
		tick();
	}

	function initCountdown() {
		document.querySelectorAll( '.countdown[data-sale]' ).forEach( startCountdown );
	}

	function init() {
		initHoverSwap();
		initCountdown();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
