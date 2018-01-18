<?php

add_action('wp_head','lander_head');

function lander_head() {
        $custom_logo = lander_custom_logo();
        if($custom_logo) {
        ?>
        <style type="text/css">
        .site-title a{
            background: url(<?php echo $custom_logo[0]; ?>);
	    background-repeat: no-repeat;
	    display: block;
            text-indent: -9999px;
	    position: left top;
        }
        </style>
        <?php
    }
}

function lander_custom_logo(){
    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $custom_logo = wp_get_attachment_image_src( $custom_logo_id, 'full', false );
        return $custom_logo;
    }
}
