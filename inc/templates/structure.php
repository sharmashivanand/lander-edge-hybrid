<?php


/**
 * Return or output markup (optionally) with HTML5 attributes
 */

add_action('lander_top_inner_markup','lander_layout_wrap_open');
add_action('lander_bottom_inner_markup','lander_layout_wrap_close');

function lander_markup( $args = array() ) {

	$defaults = array(
		
		'slug' => '',
		'context' => '',
		'open'    => '',
		'close'   => '',
		'content' => '',
		'echo'    => true,
        'attr'    => array()
	);

	$args = wp_parse_args( $args, $defaults );

    // Allows to kill the markup / element altogether
    $pre = apply_filters( "lander_markup_{$args['slug']}", false, $args );
	if ( false !== $pre ) {

		if ( ! $args['echo'] ) {
			return $pre;
		}

		echo $pre;
		return null;

    }
    
    $open = $args['open'] ? sprintf( $args['open'], hybrid_get_attr( $args['slug'], $args['context'], $args['attr'] ) ) : $args['open'];

    $close = $args['close'];

    if ( $args['echo'] ) {
        if($args['open']){
            //do_action( "lander_before_{$args['slug']}_markup" );
            
            do_action( 'lander_before' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );

            echo $open;
            do_action( 'lander_top' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
            echo $args['content'];
        }
       
        if($args['close']){
            do_action( 'lander_bottom' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
            echo $close;
            do_action( 'lander_after' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
        }
    }
    else {  // If not echo
        if($args['open']) {
            ob_start();
            do_action( 'lander_before' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
            echo $open;
            do_action( 'lander_top' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
            echo $args['content'];
            $open = ob_get_clean();
        }
    
        if($args['close']) {
            ob_start();
            do_action( 'lander_bottom' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
            echo $close;
            do_action( 'lander_after' . ($args['slug'] ? '_' . $args['slug'] : '') . ($args['context'] ? '_' . $args['context'] : '') . '_markup' );
            $close = ob_get_clean();
        }
        return $open . $close;
    }

}


function lander_build_body(){

    do_action( 'lander_before_content_sidebar_wrap' );

    /*
    lander_markup( array(
        'open'   => '<section %s>',
        'slug' => 'content-sidebar-wrap',
    ) );
    */

    do_action( 'lander_before_content' );

    lander_markup(
        array(
            'open' => '<main %s>',
            'slug' => 'content'
        )
    );

    do_action( 'lander_before_loop' );
    
    do_action( 'lander_loop' );
    
    do_action( 'lander_after_loop' );
    
    lander_markup(
        array(
            'close' => '</main>',
            'slug' => 'content'
        )
    );

    do_action( 'lander_after_content' );

    /*
    lander_markup( array(
		'close'   => '</section>',
		'slug' => 'content-sidebar-wrap',
    ) );
    */

	do_action( 'lander_after_content_sidebar_wrap' );
}


add_action( 'lander_before' , 'lander_skip_link');

function lander_skip_link() {
    lander_markup(
        array(
            'open' => '<div %s>',
            'slug' => 'skip-link',
        )
    );

	?>
    <a href="#content" class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'lander' ); ?></a>
    <?php

	 lander_markup(
        array(
            'slug' => 'skip-link',
            'close' => '</div>'
        )
    );
}


/**
 * Opens div.wrap used for layout structuring
 *
 * @return void
 */

function lander_layout_wrap_open(){
    lander_markup(
        array(
            'open' => '<div %s>',
            'slug' => 'wrap'
        )
    );
}

/**
 * Closes div.wrap used for layout structuring
 *
 * @return void
 */

function lander_layout_wrap_close(){
    lander_markup(
        array(
            'close' => '</div>',
            'slug' => 'wrap'
        )
    );
}

add_action('lander_after_content', 'lander_do_primary_sidebar');

function lander_do_primary_sidebar() {
    if ( '1c' !== hybrid_get_theme_layout() ) {
        hybrid_get_sidebar( 'primary' );
    }
}

add_action('lander_after_content', 'lander_do_secondary_sidebar');

function lander_do_secondary_sidebar() {
    if ( '3c-c' == hybrid_get_theme_layout() || '3c-l' == hybrid_get_theme_layout() || '3c-r' == hybrid_get_theme_layout() ) {
        hybrid_get_sidebar( 'secondary' );
    }
}
add_action('lander_sidebar','lander_do_primary_widgets');

function lander_do_primary_widgets(){

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

add_action('lander_sidebar_alt','lander_do_secondary_widgets');

function lander_do_secondary_widgets(){

    if ( is_active_sidebar( 'secondary' ) )  {
        dynamic_sidebar( 'secondary' ); 
    }
    else {

        the_widget(
            'WP_Widget_Text',
            array(
                'title'  => __( 'Example Widget', 'lander' ),
                // Translators: The %s are placeholders for HTML, so the order can't be changed.
                'text'   => sprintf( __( 'This is an example widget to show how the Secondary sidebar looks by default. You can add custom widgets from the %swidgets screen%s in the admin.', 'lander' ), current_user_can( 'edit_theme_options' ) ? '<a href="' . admin_url( 'widgets.php' ) . '">' : '', current_user_can( 'edit_theme_options' ) ? '</a>' : '' ),
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

/* === Breadcrumb support === */

add_action( 'lander_before_content_sidebar_wrap', 'lander_breadcrumbs' );

function lander_breadcrumbs(){

    if( false == apply_filters( 'lander_show_breadcrumbs', true ) ) {
        return;
    }
    if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('<p id="lander-breadcrumbs">','</p>');
    }
    if( function_exists('bcn_display')) {     
        echo '<p id="lander-breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
        bcn_display();
        echo '</p>';
    }

}


function lander_get_custom_logo() {
    
    if( current_theme_supports( 'custom-logo' ) && function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        return wp_get_attachment_image_url( $custom_logo_id, 'full', false );
    }

    return false;
}