<?php

add_action( 'lander_loop_else','lander_404_content' );

// 404 content
function lander_404_content() {
    lander_markup( array(
        'open'    => '<article %s>',
        'slug' => 'entry',
    ) );
    ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( apply_filters('lander_404_title','Nothing found'), 'lander' ); ?></h1>
	</header><!-- .entry-header -->

	<div <?php hybrid_attr( 'entry-content' ); ?>>
		<?php echo wpautop( esc_html__( apply_filters('lander_404_content', 'Apologies, but no entries were found.' ), 'lander' ) ); ?>
	</div><!-- .entry-content -->

    <?php
    lander_markup( array(
        'close'    => '</article>',
        'slug' => 'entry',
    ) );
}
