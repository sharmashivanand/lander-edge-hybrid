<?php

add_action('lander_before_loop','lander_do_archive_header');
add_action( 'lander_archive_header', 'lander_archive_header' );

/**
 * Output the archive header
 *
 * @return void
 */

function lander_archive_header() {
    lander_markup(
        array(
            'open' => '<header %s>',
            'slug' => 'archive-header'
        )
    );
    ?>
    <h1 <?php hybrid_attr( 'archive-title' ); ?>><?php 
    the_archive_title();
    echo is_paged() ? ' <span class="archive-title-paged">Page ' . absint( get_query_var( 'paged' ) ) . '</span>' : '' ; 
    ?></h1>
    <?php if (  $desc = get_the_archive_description() ) { // Check if we're on page/1. ?>

		<div <?php hybrid_attr( 'archive-description' ); ?>>
			<?php echo $desc; ?>
		</div><!-- .archive-description -->
    <?php }; // End paged check. 
    
    lander_markup(
        array(
            'close' => '</header>',
            'slug' => 'archive-header'
        )
    );
}

/**
 * Initiate the archive header conditionally
 *
 * @return void
 */

function lander_do_archive_header(){
    if ( ! is_front_page() && hybrid_is_plural() ) { // If viewing a multi-post page 
        do_action('lander_archive_header');
    }
     // End check for multi-post page.
}