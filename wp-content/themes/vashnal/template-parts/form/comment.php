<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

$category_options = Content::get_qa_terms_options_list();

$comment_id = ( isset( $_POST['comment_id'] ) && $_POST['comment_id'] )
    ? (int) filter_input( INPUT_POST, 'comment_id' ) : 0;

$post_id = ( isset( $_POST['post_id'] ) && $_POST['post_id'] )
    ? (int) filter_input( INPUT_POST, 'post_id' ) : 0;

?>
<form class="comment-form" method="post" action="/" id="comment_form_<?php
        echo $comment_id; ?>" data-id="<?php echo $comment_id; ?>">

    <?php if ( 1==2 && $comment_id === 0 ) { ?>
        <div class="comment-form__caption">
            <?php echo __( 'Share your opinion', VASHNAL_TEXT_DOMAIN ); ?>
        </div>
    <?php } ?>

    <label class="comment-form__field comment-form__field--text">
        <input type="text" name="name" placeholder="<?php echo __( 'Enter your name', VASHNAL_TEXT_DOMAIN ); ?>">
    </label>

    <label class="comment-form__field comment-form__field--text">
		<textarea rows="5" type="text" name="comment" placeholder="<?php
        echo __( "Пожалуйста, обратите внимание.\r\n\r\n"
                 . "Не размещайте новые вопросы в разделе Комментарии. Для вопросов предназначена кнопка Задать вопрос вверху сайта. "
                 . "Новые вопросы, заданные в комментариях, будут удалены.",
            VASHNAL_TEXT_DOMAIN ); ?>"></textarea>
    </label>

    <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

    <button class="button" type="submit"><?php echo __( 'send', VASHNAL_TEXT_DOMAIN ); ?></button>

</form>

<div class="comment-form__success hidden" id="comment_form_success_<?php echo $comment_id; ?>">
    <?php echo __( 'Your message has been successfully sent. '
                   . 'It will appear on the site in a few minutes.', VASHNAL_TEXT_DOMAIN ); ?>
</div>