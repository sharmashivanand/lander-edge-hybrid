<?php

add_action('customize_register', 'lander_customize_register');

function lander_customize_register($wp_customize){
    $defaults      = lander_get_defaults();
	$settings_type = 'theme_mod';
    $transport     = 'refresh';
    
    $wp_customize->add_panel('lander_panel', array('priority' => 15, 'title' => __('Lander Theme Settings', 'lander'), 'description' => __('Tune the raw power of Lander', 'lander')));
        $wp_customize->add_section('lander_general', array('title' => __('General Settings', 'lander'), 'priority' => 10, 'description' => __('General styles and other global options.', 'lander'), 'panel' => 'lander_panel'));
            $wp_customize->add_setting('link_color', array('default' => $defaults['link_color'], 'type' => $settings_type, 'transport' => $transport));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color_control', array('label' => __('Link Color', 'hyper'), 'settings' => 'link_color', 'section' => 'lander_general', 'type' => 'color')));
            $wp_customize->add_setting('link_visited_color', array('default' => $defaults['link_visited_color'], 'type' => $settings_type, 'transport' => $transport));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_visited_color_control', array('label' => __('Link Visited Color', 'hyper'), 'settings' => 'link_visited_color', 'section' => 'lander_general', 'type' => 'color')));
            $wp_customize->add_setting('link_hover_color', array('default' => $defaults['link_hover_color'], 'type' => $settings_type, 'transport' => $transport));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_hover_color_control', array('label' => __('Link Hover Color', 'hyper'), 'settings' => 'link_hover_color', 'section' => 'lander_general', 'type' => 'color')));
            $wp_customize->add_setting('link_active_color', array('default' => $defaults['link_active_color'], 'type' => $settings_type, 'transport' => $transport));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_active_color_control', array('label' => __('Link Color', 'hyper'), 'settings' => 'link_active_color', 'section' => 'lander_general', 'type' => 'color')));
    
    /*
    $post_types = lander_get_post_types();

	foreach ( $post_types as $key => $value ) {

		if( post_type_supports( $key, 'comments' ) ) {
			$wp_customize->add_setting( $key . '_comments', array( 'default' => $defaults[$key . '_comments' ] , 'type' => $settings_type, 'transport' => $transport ) );
			$wp_customize->add_control( 'comments_' . $key . '_control', array( 'label' => __( 'Enable Comments on ' . get_post_type_object( $key )->labels->name  .'?', 'lander' ), 'section'  => 'lander_general', 'settings' => $key . '_comments', 'type' => 'checkbox' ) );
		}

		if( post_type_supports( $key, 'trackbacks' ) ) {
			$wp_customize->add_setting( $key . '_trackbacks', array( 'default' => defaults[$key . '_trackbacks' ] , 'type' => $settings_type, 'transport' => $transport ) );
			$wp_customize->add_control( 'trackbacks_' . $key . '_control', array( 'label' => __( 'Enable Trackbacks on ' . get_post_type_object( $key )->labels->name . '?', 'lander' ), 'section'  => 'lander_general', 'settings' => $key . '_trackbacks', 'type' => 'checkbox' ) );
		}
    } // foreach
    */
    
}

function lander_get_post_types(){
    return get_post_types( array('public'   => true), 'objects' );
}

function lander_get_defaults(){
    $defaults = array(
        'link_color' => '#c00',
        'link_visited_color' => '#800',
        'link_hover_color' => '#d00',
        'link_active_color' => '#f00'
    );

    $post_types = lander_get_post_types();

	foreach ( $post_types as $key => $value ) {
		if( post_type_supports( $key, 'comments' )) {
			$defaults["{$key}_comments"] = '1';
		}
		if( post_type_supports( $key, 'trackbacks' )) {
			$defaults["{$key}_trackbacks"] = '0';
		}
    }
    
    return apply_filters('lander_get_defaults', $defaults );
}

add_action('wp_head','lander_customizer_css');

function lander_customizer_css(){
    echo '
    <style type="text/css">
    :link {
        color: '.lander_get_mod('link_color').';
    }
    :visited {
        color: '.lander_get_mod('link_visited_color').';
    }
    :link:hover {
        color: '.lander_get_mod('link_hover_color').';
    }
    :link:active {
        color: '.lander_get_mod('link_active_color').';
    }
    </style>
    ';
}
add_action('wp_head','lander_head');

function lander_get_mod($mod){
    return get_theme_mod($mod, lander_get_defaults()[$mod]);
}

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
