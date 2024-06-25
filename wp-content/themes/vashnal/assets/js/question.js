jQuery( function( $ ) {

    let questionForm = {
        /** @namespace question_params */
        /** @namespace question_params.add_script */

        buttonOpenForm: $( '#open_question_form' ),
        md5Script: question_params.add_script,
        formSelector: '.question-form',
        secretSelector: 'input[name="secret"]',
        secretSourceSelector: 'input[name="secret_source"]',
        allFormElementsSelector: '.question-form :input',

        init: function() {
            this.buttonOpenForm.on( 'click', this.open );
        },

        open: function() {
            /** @namespace question_params.action */
            $.ajax( {
                url: question_params.url,
                type: 'POST',
                data: {
                    'action': question_params.action
                },
                success: function ( form ) {
                   popup.open( form );
                   questionForm.setup();
                },
            } );
        },

        setup: function() {
            $.getScript( questionForm.md5Script, function() {
                if( typeof md5 === 'function' ) {
                    $( questionForm.secretSelector ).val( md5( $( questionForm.secretSourceSelector ).val() ) );
                }
            } );
            let form = $( questionForm.formSelector );
            if ( form.length ) {
                form.on( 'submit', questionForm.send );
            }
        },

        send: function() {
            if( questionForm.validate() ) {
                /** @namespace question_params.send_action */
                let data = $( questionForm.formSelector ).serialize();
                data += '&action=' + question_params.send_action;
                questionForm.setSendingState();
                $.ajax( {
                    url: question_params.url,
                    type: 'POST',
                    data: data,
                    success: questionForm.showResult
                } );
            }
            return false;
        },

        showResult: function() {
            /** @namespace question_params.success_action */
            $.ajax( {
                url: question_params.url,
                type: 'POST',
                data: {
                    'action': question_params.success_action
                },
                success: function ( success ) {
                    popup.close();
                    popup.open( success );
                },
            } );
        },

        validate: function() {
            let fields = $( questionForm.formSelector ).find( 'input[type="text"], textarea' );
            let error = false;
            fields.each( function() {
                if ( $(this).val().length < 2 ) {
                    $( this ).addClass( 'error' );
                    error = true;
                } else {
                    $( this ).removeClass( 'error' );
                }
            } );
            return ! error;
        },

        setSendingState: function() {
            $( questionForm.formSelector ).addClass( 'sending' );
            $( questionForm.allFormElementsSelector ).prop( 'disabled', true );
        },

        unsetSendingState: function() {
            $( questionForm.formSelector ).removeClass( 'sending' );
            $( questionForm.allFormElementsSelector ).prop( 'disabled', false );
        }

    };

    questionForm.init();

} );