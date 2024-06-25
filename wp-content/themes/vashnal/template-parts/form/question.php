<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

$category_options = Content::get_qa_terms_options_list();

?>
<form class="question-form" method="post" action="/">

	<div class="question-form__caption">
		<?php echo __( 'Ask your question', 'vashnal' ); ?>
	</div>

	<label class="question-form__field question-form__field--select question-form__field--subject">
		<select name="category">
            <?php echo $category_options; ?>
		</select>
        <div class="question-form__field--select__shadow"></div>
		<?php $vashnal_svg->print_symbol( 'down' ); ?>
	</label>

	<label class="question-form__field question-form__field--text">
		<input type="text" name="name" placeholder="<?php echo __( 'Enter your name', 'vashnal' ); ?>">
	</label>

	<label class="question-form__field question-form__field--text">
		<input type="text" name="subject" placeholder="<?php echo __( 'Short question', 'vashnal' ); ?>">
	</label>

	<label class="question-form__field question-form__field--text">
		<textarea type="text" name="question" placeholder="<?php
		echo __( 'Please describe your question in detail, then you will be able to get the most detailed answer.',
			'vashnal' ); ?>"></textarea>
	</label>

    <input type="hidden" name="secret_source" value="<?php echo md5( date( 'Y-m-d' ) ); ?>">
    <input type="hidden" name="secret" value="">

	<button class="button" type="submit"><?php echo __( 'ask a question on the site', 'vashnal' ); ?></button>

</form>