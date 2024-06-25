jQuery( function( $ ) {

    let postMenu = {

        post: $( '.post' ),
        menu: $( '.post__menu' ),
        menuList: $( '.post__menu__list' ),
        menuLinks: $( '.post__menu__list li' ),
        initPosition: 0,
        footerOffset: $( 'footer' ).offset().top,
        headers: $( '.post h2, .post h3, .post h4, .post h5, .post h6' ),
        menuItemPrefix: '.post__menu__list li[data-link="%id%"]',
        menuItem: $( '.post__menu__list li' ),

        init: function () {
            if ( this.menu.length ) {
                this.setInitPosition();
                this.menuLinks.on( 'click', this.scrollToCaption );
                $( window ).on( 'scroll', this.fixPosition );
                $( window ).on( 'scroll', this.markCurrentItem );
            }
        },

        markCurrentItem: function() {
            let currentScroll = $( window ).scrollTop();
            postMenu.headers.each( function() {
                let headerId = $( this ).data( 'anchor' );
                if ( currentScroll + 100 >= $( this ).offset().top ) {
                    postMenu.menuItem.removeClass( 'active' );
                    $( postMenu.menuItemPrefix.replace( '%id%', headerId ) ).addClass( 'active' );
                }
                //return false;
            } );
        },

        setInitPosition: function() {
            this.initPosition = this.menu.offset().top - 30 - 50;
        },

        scrollToCaption: function( event ) {
            let link = $( event.target );
            let anchor = $( '[data-anchor=' + link.data( 'link' ) + ']' );
            $('html, body').animate({
                scrollTop: anchor.offset().top - 80
            }, 1000);
        },

        fixPosition: function() {
            let currentScroll = $( window ).scrollTop();
            if ( currentScroll > postMenu.post.offset().top - 20 ) {
                let postEnd = parseInt( postMenu.post.offset().top + postMenu.post.height() );
                let menuEnd = parseInt( postMenu.menu.offset().top + postMenu.menu.height() + 90 );
                let menuBegin = parseInt( postMenu.menu.offset().top - 75 );
                if ( menuBegin >= currentScroll ) {
                    postMenu.menu.addClass( 'fixed' );
                    postMenu.menu.removeClass( 'bottom' );
                }
                else if ( menuEnd >= postEnd ) {
                    postMenu.menu.removeClass( 'fixed' );
                    postMenu.menu.addClass( 'bottom' );
                } else {
                    postMenu.menu.addClass( 'fixed' );
                    postMenu.menu.removeClass( 'bottom' );
                }
            } else {
                postMenu.menu.removeClass( 'fixed' );
                postMenu.menu.removeClass( 'bottom' );
            }
        }

    };

    postMenu.init();

} );