<?php

add_action('customize_register', 'lander_landing_sections_customize_register');

add_action( 'wp_head', 'lander_landing_sections_css' );

function lander_landing_sections_customize_register( $wp_customize ) {

	$lander_landing_sections_defaults = lander_landing_sections_get_defaults();
	$settings = 'hyperland';
	$settings_type = 'option';
	$transport     = 'refresh';
	$sanitize_callback = 'sanitize_text_field';

	$wp_customize->add_panel('lander_landing_sections_panel', array('priority' => 15, 'title' => __('Lander Landing Sections Tweaks', 'lander-landing-sections'), 'description' => __('Tweak your landing page', 'lander-landing-sections')));



	foreach($lander_landing_sections_defaults as $element => $specs) {

		$wp_customize->add_section('lander_landing_sections_general_' . $element, array('title' => $specs['label'], 'priority' => 10, 'description' => __('Global display options.', 'lander-landing-sections'), 'panel' => 'lander_landing_sections_panel'));

		$wp_customize->add_setting($settings . '[' . $element . '][selector]', array('default' => $specs['selector'], 'type' => $settings_type, 'transport' => $transport, 'sanitize_callback' => $sanitize_callback));
		$wp_customize->add_control($settings . '[' . $element . '][selector]', array('label' => $specs['label'] .' Selector', 'section' => 'lander_landing_sections_general_' . $element, 'type' => 'text'));

		$wp_customize->add_setting($settings . '[' . $element . '][show]', array('default' => $specs['show'], 'type' => $settings_type, 'transport' => $transport, 'sanitize_callback' => $sanitize_callback));
		$wp_customize->add_control($settings . '[' . $element . '][show]', array('label' => $specs['label'] .' Show on Desktop', 'section' => 'lander_landing_sections_general_' . $element, 'type' => 'select', 'choices' => array('show' => 'Show', 'hide' => 'Hide')));

		$wp_customize->add_setting($settings . '[' . $element . '][mobile_show]', array('default' => $specs['show'], 'type' => $settings_type, 'transport' => $transport, 'sanitize_callback' => $sanitize_callback));
		$wp_customize->add_control($settings . '[' . $element . '][mobile_show]', array('label' => $specs['label'] .' Show on Mobile', 'section' => 'lander_landing_sections_general_' . $element, 'type' => 'select', 'choices' => array('show' => 'Show', 'hide' => 'Hide')));

	}

}


/**
 * Returns the default settings
 * @return [array] [key => value pairs]
 */
function lander_landing_sections_get_defaults() {

	$defaults = array(

		'site_header' => array (
			'context' => array('site','post','taxonomy'),
			'label' => 'Site Header',
			'selector' => '.site-header',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
		'post_breadcrumb' => array (
			'context' => array('site','post','taxonomy'),
			'label' => 'Breadcrumb',
			'selector' => '.breadcrumbs',
			'show' => 'show',
			'mobile_show' => 'show'
			),

		'archive_header' => array (
			'context' => array('taxonomy'),
			'label' => 'Archive Header',
			'selector' => '.archive-header',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
		'archive_title' => array (
			'context' => array('taxonomy'),
			'label' => 'Archive Title',
			'selector' => '.archive-title',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
		'archive_description' => array (
			'context' => array('taxonomy'),
			'label' => 'Archive Description',
			'selector' => '.archive-description',
			'show' => 'show',
			'mobile_show' => 'show'
			),

		'post_header' => array (
			'context' => array('post'),
			'label' => 'Post Header',
			'selector' => '.singular .entry-header',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
		'post_title' => array (
			'context' => array('post'),
			'label' => 'Post Title',
			'selector' => '.singular .entry-title',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
		'post_byline' => array (
			'context' => array('post'),
			'label' => 'Post Byline',
			'selector' => '.singular.entry-byline',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
		'site_footer' => array (
			'context' => array('site','post','taxonomy'),
			'label' => 'Site Footer',
			'selector' => '.site-footer',
			'show' => 'show',
			'mobile_show' => 'show'
			),
		
	);

	return apply_filters('lander_landing_sections_get_defaults', $defaults);
}


function lander_landing_sections_get_selector( $element ) {
	$settings = lander_landing_sections_get_settings(get_option('hyperland'));
	return $settings[$element]['selector'];
}

function lander_landing_sections_is_mobile() {
	return apply_filters('lander_landing_sections_is_mobile', wp_is_mobile() );
}




/**
 * Hides the elements via CSS
 * @return [type] [description]
 */

function lander_landing_sections_css() {

    $settings = lander_landing_sections_get_settings();

	


	$hide_css = '{
		text-indent: -9999px;
		display: block;
		height: 0px;
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		border-width: 0;
		width: 0px;
	}';

	$hide_css = apply_filters( 'lander_landing_sections_css_hide', $hide_css );

	$css = '';
	
	foreach( $settings as $element => $specs ) {

		if( lander_landing_sections_is_mobile() && ( strcasecmp( $specs['mobile_show'], 'show' ) !== 0 ) ) {
			$css .= lander_landing_sections_get_selector( $element ) . $hide_css;
		}

		if( ( ! lander_landing_sections_is_mobile() ) && ( strcasecmp( $specs['show'], 'show' ) !== 0 ) ) {
			$css .= lander_landing_sections_get_selector( $element ) . $hide_css;
		}

	}
	
	//hyper_log( $css );
	//die();
	echo '<style type="text/css">'.$css.'</style>';

}

/**
 * Returns hyperland tweaks settings
 */
function lander_landing_sections_get_settings(){
    
    $global_settings = get_option('hyperland') ? wp_parse_args( get_option('hyperland'), lander_landing_sections_get_defaults() ) : lander_landing_sections_get_defaults();
    
	if( is_singular() ) {
        global $post;
        $post_settings = get_post_meta( $post->ID, '_hyperland', 1 );
        

        $post_settings = wp_parse_args( $post_settings, $global_settings );
        return $post_settings;
	}
	
	if( is_tax() || is_category() || is_tag() ) {
		$lander_landing_sections_term_settings = get_term_meta( get_queried_object_id(), '_hyperland', 1 );
        $lander_landing_sections_term_settings = wp_parse_args( $lander_landing_sections_term_settings, $global_settings );
        return $lander_landing_sections_term_settings;
	}
   
    return $global_settings;
}

//add_action('after_setup_theme', 'lander_landing_sections_trigger_hyperloop_features' , 99);

// Now we can access WordPress views / conditionals
add_action('template_redirect', 'lander_landing_sections_trigger_hyperloop_features' , 9999);

/**
 * Removes elements markup
 * @return [type] [description]
 */

function lander_landing_sections_trigger_hyperloop_features() {

	$settings = lander_landing_sections_get_settings();
    
    // llog($settings);
    // die();

	if( array_key_exists( 'site_header', $settings ) ) {
		if( lander_landing_sections_is_mobile() && ( strcasecmp( $settings['site_header']['mobile_show'], 'show' ) !== 0 ) ) {
            remove_action( 'lander_header', 'lander_header' );
            // add_filter( "lander_markup_header", __return_true );
		}
		if( ! lander_landing_sections_is_mobile() && ( strcasecmp( $settings['site_header']['show'], 'show' ) !==0 ) ) {
			remove_action( 'lander_header', 'lander_header' );
			//remove_action( 'hyper_header', 'hyper_header' );
		}
	}

	if( array_key_exists( 'site_footer', $settings ) ) {
		if( lander_landing_sections_is_mobile() && ( strcasecmp( $settings['site_footer']['show'], 'show' ) !==0 ) ) {
			remove_action( 'hyper_footer' , 'hyper_footer' );
		}
		if( ! lander_landing_sections_is_mobile() && ( strcasecmp( $settings['site_footer']['show'], 'show' ) !==0 ) ) {
            echo 'we are in the right place';
            add_filter( "lander_markup_footer", __return_true );
			//remove_action( 'hyper_footer' , 'hyper_footer' );
		}
	}
}
