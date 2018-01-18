<?php

do_action('lander_head');

lander_markup( array(
	'open'   => '<body %s>',
	'slug' => 'body',
) );

do_action('lander_before');

lander_markup(
    array(
        'open' => '<div %s>',
        'slug' => 'site-container'
    )
);

do_action('lander_before_header');
do_action('lander_header');
do_action('lander_after_header');

lander_markup( array(
	'open'   => '<section %s>',
	'slug' => 'inner',
) );
