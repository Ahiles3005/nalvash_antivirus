jQuery(function ($) {

    let commentForm = {
        /** @namespace comment_params */
        /** @namespace comment_params.add_script */
        /** @namespace comment_params.recaptcha_url */
        /** @namespace comment_params.recaptcha_key */
        mainFormWrapper: $('#comment-form-wrapper'),
        allFormElementsSelector: ':input',
        answerButton: $('.post__comments__list__item__answer'),
        answerButtonIdPrefix: '#comment_answer_',
        answerSuccessIdPrefix: '#comment_form_success_',
        answerIdPrefix: '#comment_',
        formIdPrefix: '#comment_form_',

        init: function () {
            let postID = commentForm.mainFormWrapper.data('post-id');
            setTimeout(function () {
                commentForm.open(false, 0, postID);
            }, 1000);
            this.answerButton.on('click', function () {
                commentForm.open(true, $(this).data('comment-id'), postID);
            })
        },

        open: function (answer, commentID, postID) {
            /** @namespace comment_params.action */
            $.ajax({
                url: comment_params.url,
                type: 'POST',
                data: {
                    'action': comment_params.action,
                    'comment_id': commentID,
                    'post_id': postID
                },
                success: function (form) {
                    if (!answer && commentForm.mainFormWrapper.length) {
                        commentForm.mainFormWrapper.html(form);
                    } else {
                        $(commentForm.answerIdPrefix + commentID).append(form);
                        $(commentForm.answerButtonIdPrefix + commentID).addClass('hidden');
                    }
                    commentForm.setup(commentID);
                },
            });
        },

        setup: function (commentID) {
            let form = $(commentForm.formIdPrefix + commentID);

            if (form.length) {
                form.on('submit', function () {
                    return commentForm.send(form);
                });
            }
        },

        send: function (form) {
            if (commentForm.validate(form)) {
                /** @namespace comment_params.send_action */
                let data = form.serialize();
                data += '&action=' + comment_params.send_action;
                commentForm.setSendingState(form);
                $.ajax({
                    url: comment_params.url,
                    type: 'POST',
                    data: data,
                    success: function () {
                        commentForm.showResult(form)
                    }
                });
            }
            return false;
        },

        showResult: function (form) {
            commentForm.unsetSendingState(form);
            let formId = form.data('id');
            let successMessage = $(commentForm.answerSuccessIdPrefix + formId);
            if (formId === 0) {
                //$('#' + commentForm.recaptchaIdPrefix + formId).empty();
                form.addClass('hidden');
                successMessage.removeClass('hidden');
                setTimeout(function () {
                    successMessage.fadeOut(1000, function () {
                        successMessage.addClass('hidden');
                        successMessage.removeAttr('style');
                        //grecaptcha.reset(commentForm.initialRecaptchaId);
                        form.find('input[type="text"], textarea').val('');
                        form.removeClass('hidden');
                    });
                }, 2000);
            } else {
                let answerButton = $(commentForm.answerButtonIdPrefix + formId);
                form.remove();
                successMessage.removeClass('hidden');
                setTimeout(function () {
                    successMessage.fadeOut(1000, function () {
                        answerButton.removeClass('hidden');
                        successMessage.remove();
                    });
                }, 2000);
            }

        },

        validate: function (form) {
            let fields = form.find('input[type="text"], textarea');
            let error = false;
            fields.each(function () {
                if ($(this).val().length < 2) {
                    $(this).addClass('error');
                    error = true;
                } else {
                    $(this).removeClass('error');
                }
            });
            return !error;
        },

        setSendingState: function (form) {
            form.addClass('sending');
            form.find(commentForm.allFormElementsSelector).prop('disabled', true);
        },

        unsetSendingState: function (form) {
            form.removeClass('sending');
            form.find(commentForm.allFormElementsSelector).prop('disabled', false);
        }

    };

    commentForm.init();

});