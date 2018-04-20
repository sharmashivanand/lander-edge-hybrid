<?php

lander_markup( array(
    'open'    => '<aside %s>',
    'slug' => 'sidebar',
    'context' => 'secondary'
) );

do_action( 'lander_before_sidebar_widget_area' );
do_action( 'lander_sidebar_alt' );
do_action( 'lander_after_sidebar_widget_area' );

// End .sidebar-secondary.
lander_markup( array(
    'close'   => '</aside>',
    'slug' => 'sidebar',
) );