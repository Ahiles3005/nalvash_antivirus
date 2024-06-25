jQuery( function( $ ) {

	let menu = {

		wrapper: $( '#main_menu' ),
		overlay: $( '.overlay' ),
		hamburger: $( '#hamburger' ),
		hamburgerClose: $( '#hamburger_close' ),
		parents: $( '.menu-item-has-children' ),
		parentsLinks: $( '.menu-item-has-children a' ),
		list: $( '#primary-menu' ),
		menuSocial: $( '.header__menu__buttons' ),
		headerSocial: $( '.header__buttons .social' ),
		search: $( '#search_form' ),

		init: function() {
			this.hamburger.on( 'click', this.open );
			this.overlay.on( 'click', this.close );
			this.hamburgerClose.on( 'click', this.close );
			this.parents.each( function() {
				if( $( this ).hasClass( 'current-menu-ancestor' ) && $( window ).width() < 1100 ) {
					$( this ).addClass( 'hover' );
				}
			} );
			this.headerSocial.clone().appendTo( this.menuSocial );
			$( window ).on( 'beforeunload', this.close );
			this.search.find( '.button' ).on( 'click', function() {
				menu.search.submit();
			} );
			this.parents.on('touchstart', function (e) {
				'use strict';
				let link = $(this);
				if (link.hasClass('hover')) {
					return true;
				} else {
					link.addClass('hover');
					menu.parents.not(this).removeClass('hover');
					e.preventDefault();
					return false;
				}
			});
		},

		closeSubmenu: function() {
			menu.parents.removeClass( 'hover' );
		},

		open: function() {
			menu.wrapper.addClass( 'show' );
			menu.hamburgerClose.removeClass( 'hidden' );
			$( this ).addClass( 'hidden' );
			$( 'body' ).addClass( 'fixed' );
			menu.overlay.removeClass( 'hidden' );
		},

		close: function() {
			menu.wrapper.removeClass( 'show' );
			menu.hamburger.removeClass( 'hidden' );
			menu.hamburgerClose.addClass( 'hidden' );
			$( 'body' ).removeClass( 'fixed' );
			$( '.overlay' ).addClass( 'hidden' );
			menu.closeSubmenu();
		}

	};

	menu.init();

} );