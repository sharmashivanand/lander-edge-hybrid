<?php

add_action( 'lander_footer', 'lander_footer_open', 5 );
add_action( 'lander_footer', 'lander_footer_close', 15 );
add_action( 'lander_top_footer_markup', 'lander_layout_wrap_open', 5 );
add_action( 'lander_bottom_footer_markup', 'lander_layout_wrap_close', 15 );
add_action( 'lander_footer', 'lander_footer_content' );


/**
 * Open footer markup
 *
 * @return void
 */

function lander_footer_open() {
    lander_markup(
        array(
            'open' => '<footer %s>',
            'slug' => 'footer'
        )
    );

}


/**
 * Close footer markup
 *
 * @return void
 */

function lander_footer_close(){
    lander_markup(
        array(
            'close' => '</footer>',
            'slug' => 'footer'
        )
    );
}


/**
 * Output footer credits
 *
 * @return void
 */

function lander_footer_content() {
    
    if( has_nav_menu('footer') ) {
        hybrid_get_menu('footer');
    }
    echo '<p>Copyright &copy; ' . date('Y') . ' ' . get_bloginfo( 'name' )  . '. All rights reserved.</p>';
}
