<?php

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