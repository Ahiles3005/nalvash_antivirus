<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

$current_category = get_queried_object();
if ( $current_category->parent ) {
	$parent_category = get_term( $current_category->parent );
} else {
	$parent_category = $current_category;
}
$all_posts_anchor = get_field( 'all_posts_anchor', $parent_category );

?>
<div class="category__sections">
    <?php if ( $all_posts_anchor ) { ?>
        <a href="<?php echo get_term_link( get_term( $parent_category->term_id ) ); ?>" class="category__sections__item<?php
            echo ( Content::is_current_category( $parent_category->term_id ) ) ? " active" : ""; ?>">
            <?php echo $all_posts_anchor; ?>
        </a>
    <?php } ?>
	<?php foreach( Content::get_children_terms( $parent_category->term_id ) as $category ) { ?>
		<a href="<?php echo get_term_link( $category ); ?>" class="category__sections__item<?php
		echo ( Content::is_current_category( $category->term_id ) ) ? " active" : ""; ?>">
			<?php echo $category->name; ?>
		</a>
	<?php } ?>
</div>