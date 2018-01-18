<?php

/** Default content template to handle post, attachment, post-format */

do_action( 'lander_entry_header', get_post_type(), get_post_format() );
do_action( 'lander_before_entry_content', get_post_type(), get_post_format() );
do_action( 'lander_entry_content', get_post_type(), get_post_format() );
do_action( 'lander_after_entry_content', get_post_type(), get_post_format() );
do_action( 'lander_entry_footer', get_post_type(), get_post_format() );
