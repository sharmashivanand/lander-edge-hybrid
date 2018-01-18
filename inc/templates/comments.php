<?php


add_action('lander_after_entry', 'lander_setup_comments'); 
add_action('lander_after_comments','lander_comments_nav');
add_action('lander_comments', 'lander_do_comments');
add_action('lander_comments_notice', 'lander_comments_notice');

function lander_setup_comments() {
    
    if( ! is_singular() ) { 
        return; 
    }

    if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
        
        return; 
    }

    comments_template('', true);    // Get comments.php template
}


function lander_do_comments(){
    if ( post_password_required() ) {
        return;
    }
    if( !have_comments() && !comments_open() && !pings_open() ) {
        return;
    }
    if(!comments_open() && !pings_open() ){
        do_action('lander_comments_notice');
        // return;
    }

?>

<section id="comments-template">

	<?php if ( have_comments() ) : // Check if there are any comments. ?>

		<div id="comments">

			<h3 id="comments-number"><?php comments_number(); ?></h3>

			<ol class="comment-list">
				<?php wp_list_comments(
					array(
						'style'        => 'ol',
						'callback'     => 'hybrid_comments_callback',
						'end-callback' => 'hybrid_comments_end_callback'
					)
				); ?>
			</ol><!-- .comment-list -->

            <?php 
            do_action( 'lander_after_comments' );
            ?>

		</div><!-- #comments-->

	<?php endif; // End check for comments. ?>

    <?php 
    do_action('lander_comments_notice');
    ?>

	<?php comment_form(); // Loads the comment form. ?>

</section><!-- #comments-template -->
<?php

}

add_action('lander_comments_callback','lander_comments_callback');

function lander_comments_callback($comment){ ?>
    <li <?php hybrid_attr('comment');?>>
    <article>
    
    <div <?php hybrid_attr('comment-content');?>>
    <?php comment_text();?>
    <?php hybrid_comment_reply_link(array('before'=>'<span class="comment-reply">', 'after'=>'</span>'));?>
    </div><!-- .comment-content -->
    
    <footer class="comment-meta">
    <?php echo get_avatar($comment); ?>
    <details>
    <summary>Info</summary>
    <cite <?php hybrid_attr('comment-author');?>><?php comment_author_link();?></cite><br />
        <time <?php hybrid_attr('comment-published');?>><?php printf(esc_html__('%s ago', 'lander'), human_time_diff(get_comment_time('U'), current_time('timestamp')));?></time>
        <a <?php hybrid_attr('comment-permalink');?>><?php esc_html_e('Permalink', 'lander');?></a>
        <?php edit_comment_link();?>
    </details>
</footer><!-- .comment-meta -->

    </article>
    <?php // No closing </li> is needed.  WordPress will know where to add it.
}


function lander_comments_nav(){
    if ( get_option( 'page_comments' ) && 1 < get_comment_pages_count() ) { // Check for paged comments. ?>

        <nav class="comments-nav" role="navigation" aria-labelledby="comments-nav-title">
    
            <h3 id="comments-nav-title" class="screen-reader-text"><?php esc_html_e( 'Comments Navigation', 'lander' ); ?></h3>
    
            <?php previous_comments_link( esc_html_x( '&larr; Previous', 'comments navigation', 'lander' ) ); ?>
    
            <span class="page-numbers"><?php 
                // Translators: Comments page numbers. 1 is current page and 2 is total pages.
                printf( esc_html__( 'Page %1$s of %2$s', 'lander' ), get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1, get_comment_pages_count() ); 
            ?></span>
    
            <?php next_comments_link( esc_html_x( 'Next &rarr;', 'comments navigation', 'lander' ) ); ?>
    
        </nav><!-- .comments-nav -->
    
    <?php } // End check for paged comments.
}


function lander_comments_notice(){
    if ( pings_open() && ! comments_open() ) : ?>

    <p class="comments-closed pings-open">
        <?php
            // Translators: The two %s are placeholders for HTML. The order can't be changed.
            printf( esc_html__( 'Comments are closed, but %strackbacks%s and pingbacks are open.', 'lander' ), '<a href="' . esc_url( get_trackback_url() ) . '">', '</a>' );
        ?>
    </p><!-- .comments-closed .pings-open -->

    <?php elseif ( ! comments_open() ) : ?>

    <p class="comments-closed">
        <?php esc_html_e( 'Comments are closed.', 'lander' ); ?>
    </p><!-- .comments-closed -->

    <?php endif; 
}