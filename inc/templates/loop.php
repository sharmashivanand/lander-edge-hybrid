<?php

add_action('lander_loop','lander_do_loop');

function lander_do_loop() {
    
    if ( have_posts() ) { // Checks if any posts were found. 
        do_action( 'lander_before_while' );
        ?>
        
        <?php while ( have_posts() ) { // Begins the loop through found posts. ?>

            <?php the_post(); // Loads the post data. 
            
            do_action( 'lander_before_entry' );
            
            lander_markup( array(
                'open'    => '<article %s>',
                'slug' => 'entry',
            ) );

            do_action('lander_entry');
            
            lander_markup( array(
                'close'    => '</article>',
                'slug' => 'entry',
            ) );            
            
            do_action( 'lander_after_entry' );
            
            } 

        do_action( 'lander_after_endwhile' );// End found posts loop. 
        }    
    else {
        do_action('lander_loop_else');
    } // End check for posts.
}
