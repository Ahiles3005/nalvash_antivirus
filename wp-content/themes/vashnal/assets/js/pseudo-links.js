jQuery( function( $ ) {

	let pseudoLinks = {

		window: $(window),
		targetQuery: '.pseudo-link',
		targetClass: 'pseudo-link',
		classInternal: 'internal',
		mobileWidth: 1020,

		init: function() {
			$( this.targetQuery ).each( this.make );
		},

		make: function( index, element ) {

			let link = $( element );

			link.removeClass( this.targetClass );

			let classes = link.attr( 'class' );
			let href = link.data( 'href' );
			let anchor = link.data( 'anchor' );

			if ( ! anchor ) {
				anchor = link.html();
			}

			let target = ( link.hasClass( pseudoLinks.classInternal ) ) ? '_self' : '_blank';

			let newHTML = '<a href="' + href + '" class="' + classes + '" target="' + target + '"' +
				' rel="noreferrer">' + anchor + '</a>';
			link.replaceWith(newHTML);

		}

	};

	let imgLinks = {
		targetQuery: 'a.image',

		init: function() {
			$( this.targetQuery ).on( 'click', this.disableClick );
		},

		disableClick: function() {
			return $( window ).width()  <= 767;
		}
	};

	pseudoLinks.init();

	imgLinks.init();

} );