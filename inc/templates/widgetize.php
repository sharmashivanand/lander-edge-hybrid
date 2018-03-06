<?php

//add_action('lander_after_content', 'lander_primary_sidebar');

/*
function lander_primary_sidebar(){

    if ( '1c' !== hybrid_get_theme_layout() ) {
        lander_markup( array(
            'open'    => '<aside %s>',
            'slug' => 'sidebar',
            'context' => 'primary'
        ) );

        do_action( 'lander_before_sidebar_widget_area' );
        do_action( 'lander_sidebar' );
        do_action( 'lander_after_sidebar_widget_area' );

        // End .sidebar-primary.
        lander_markup( array(
            'close'   => '</aside>',
            'slug' => 'sidebar',
        ) );
    }
}


add_action('lander_sidebar','lander_do_sidebar');

function lander_do_sidebar(){

    if ( is_active_sidebar( 'primary' ) )  {
        dynamic_sidebar( 'primary' ); 
    }
    else {

        the_widget(
            'WP_Widget_Text',
            array(
                'title'  => __( 'Example Widget', 'lander' ),
                // Translators: The %s are placeholders for HTML, so the order can't be changed.
                'text'   => sprintf( __( 'This is an example widget to show how the Primary sidebar looks by default. You can add custom widgets from the %swidgets screen%s in the admin.', 'lander' ), current_user_can( 'edit_theme_options' ) ? '<a href="' . admin_url( 'widgets.php' ) . '">' : '', current_user_can( 'edit_theme_options' ) ? '</a>' : '' ),
                'filter' => true,
            ),
            array(
                'before_widget' => '<section class="widget widget_text">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>'
            )
        ); 
    }
    
}
*/

