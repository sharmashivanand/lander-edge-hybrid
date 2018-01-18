<?php

lander_markup(
    array(
        'close'   => '</section>',
        'slug' => 'inner',
    )
);

do_action('lander_before_footer');
do_action('lander_footer');
do_action('lander_after_footer');

lander_markup(
    array(
        'close' => '</div>',
        'slug' => 'site-container'
    )
);

do_action('lander_after');

wp_footer();

lander_markup( array(
	'close'   => '</body>',
	'slug' => 'body',
) );
?></html>