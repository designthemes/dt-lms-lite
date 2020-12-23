<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php 
	if ( have_comments() ) : 
		?>
		<h3><?php comments_number(esc_html__('No Comments', 'dtlms-lite'), esc_html__('Comment ( 1 )', 'dtlms-lite'), esc_html__('Comments ( % )', 'dtlms-lite') );?></h3>
		<?php the_comments_navigation(); ?>
		<ul class="commentlist">
			<?php wp_list_comments(array ( 'avatar_size' => 50 )); ?>
		</ul>
		<?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    	<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'dtlms-lite'); ?></p>
    <?php endif;?>

    <?php comment_form(); ?>
</div>