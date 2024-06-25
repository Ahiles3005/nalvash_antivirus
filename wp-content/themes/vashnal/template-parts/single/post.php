<?php

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<div class="post__date"><?php echo get_the_date( 'd.m.Y', get_the_ID() ); ?></div>
<?php the_content() ; ?>
