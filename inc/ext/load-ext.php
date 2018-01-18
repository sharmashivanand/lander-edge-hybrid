<?php

add_action( 'after_setup_theme', 'lander_extensions',  20 );


function lander_extensions(){//echo current_theme_supports('silo-menus');
    //die();
    global $lander_dir, $lander_uri;
    hybrid_require_if_theme_supports( 'lander-silo-menus', $lander_dir . 'inc/ext/wp-silo-menus/wp-silo-menus.php' );
    
    
    define( 'LANDER_LS_URL', $lander_uri . 'inc/ext/lander-landing-sections/' );
    define( 'LANDER_LS_DIR', $lander_dir . 'inc/ext/lander-landing-sections/' );

    hybrid_require_if_theme_supports( 'lander-landing-sections', $lander_dir . 'inc/ext/lander-landing-sections/lander-landing-sections.php' );
    hybrid_require_if_theme_supports( 'hybrid-custom-classes', $lander_dir . 'inc/ext/custom-classes/custom-classes.php' );
}

add_action( 'after_setup_theme', 'hyper_output_landing_sections' , 21 );

function hyper_output_landing_sections(){

	if( ! function_exists('lander_landing_sections_get_editors') ) {
		return;
	}


	//global $post;

	$lander_landing_sections = lander_landing_sections_get_editors();

	foreach ( $lander_landing_sections as $lander_landing_section => $lander_landing_section_val ) {
		$section_name  = $lander_landing_section;
		$hook_name     = $lander_landing_section_val['hook'];
		$hook_priority = $lander_landing_section_val['priority'];
		//add_action( $hook_name, 'lander_output_'.$section_name.'_section', $hook_priority );
		$callback = function() use ( $section_name ) {
			lander_landing_sections_section_markup( $section_name );
		};
		add_action( $hook_name, $callback, $hook_priority ); // to dynamically build the markup for landing-sections to show on the front-end
	}
}