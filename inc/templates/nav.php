<?php

add_action('lander_after_header', 'lander_primary_nav');

/**
 * lander_do_nav: Output a desired menu with a custom class for menu's top-level container.
 *
 * @param [string] $location: Register menu location
 * @param string $classes: space separated list of CSS classes
 * @return void
 */

function lander_do_nav( $location, $args=array(), $classes='' ){

        $defaults = array(
            'theme_location'  => $location,
            'container'       => '',
            'menu_id'         => 'menu-' . $location . '-items',
            'menu_class'      => 'menu-items',
            'fallback_cb'     => '',
            'items_wrap'      => '<ul id="%s" class="%s">%s</ul>'
        );

        $args = wp_parse_args($args, $defaults);
        lander_markup(
            array(
                'open' => '<nav %s>',
                'slug' => 'menu',
                'context' => $location,
                'attr' => $classes ? array('class'=> $classes): ''
            )
        );
        
        wp_nav_menu(
            $args
        );

        lander_markup(
            array(
                'close' => '</nav>',
                'slug' => 'menu',
                'context' => $location,
                'attr' => array('class'=> $classes)
            )
        );

}


function lander_primary_nav(){

    if ( has_nav_menu( 'primary' ) ) {
        hybrid_get_menu('primary');
    }
    
}
