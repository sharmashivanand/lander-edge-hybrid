<?php
/**
 * May be some day we'll have customizer. But for now just use php & SCSS variables
 */

add_action('wp_head','lander_head');

function lander_head() {
    //var_dump( get_theme_mod( 'post_comments', true ) );
    //var_dump( get_theme_mod( 'page_comments', true ) );
        $custom_logo = lander_custom_logo();
        if($custom_logo) {
        ?>
        <style type="text/css">
        .site-title a{
            background: url(<?php echo $custom_logo[0]; ?>);
	        background-repeat: no-repeat;
	        display: block;
            text-indent: -9999px;
            background-position: left top;
            background-size: contain;
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
