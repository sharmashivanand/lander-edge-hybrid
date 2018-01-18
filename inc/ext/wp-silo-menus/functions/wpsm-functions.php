<?php

/**
 *
 * Genesis SILO Menus core functions file
 * Description: This file executes all the core plugin functions
 *
 * @package Genesis SILO Menus
 * @author Shivanand Sharma
 * @since 1.0
 *
 */

/* Bail if accessing directly */
if ( !defined( 'ABSPATH' ) ) {
	wp_die( "Sorry, you are not allowed to access this page directly." );
}


/**
 * Set up the default options for the plugin
 * Allows a filter "wpsm_settings_defaults" to allow users to filter the default settings
 *
 * @return array
 * @since 1.0
 */

function wpsm_settings_defaults() {

    $all_post_types = wpsm_get_public_post_types();
    
    if( empty( $all_post_types ) )
        $defaults = array();
    else {
        $defaults = array(
            'wpsm_post_type_page' => 1,
            'wpsm_post_type_post' => 1,
            
            'wpsm_taxonomy_category' => 1,
        );
    }
    
    return apply_filters( 'wpsm_settings_defaults', $defaults );

}


/**
 * Helper function that can be used by devs to debug any random variable / function output or anything, but in a readable format ;)
 */

function wpsm_dump( $text, $echo = true ) {
	if( !current_user_can( 'edit_files' ) )
		return;
	
	if ( $echo == true ) {
		echo '<pre>';
		print_r( $text );
		echo '</pre>';
	}
	if ( $echo == false ) {
		return print_r( $text, true );
	}
}


/**
 * Helper function to fetch the available public post types
 *
 * @param none
 * @return @none
 * @since 1.0
 */

function wpsm_get_public_post_types() {

    $args = array(
        'public' => true,
		'show_ui' => true,
    );

    $available_post_types = get_post_types( $args, 'objects' );

    return $available_post_types;

}


/**
 * Helper function to fetch all the available taxonomies
 *
 * @param none
 * @return @none
 * @since 1.0
 */

function wpsm_get_public_taxonomies() {

    $args = array(
        'public' => true,
		'show_ui' => true,
    );
    
    $available_taxonomies = get_taxonomies( $args, 'objects' );
    
    return $available_taxonomies;

}


/**
 * Add the Custom Menu Locations metaboxes to user selected post type and taxonomy screens
 *
 * @param none
 * @return @none
 * @since 1.0
 */

/* Fetch the enabled post types */

function wpsm_enabled_post_types() {
	
	$wpsm_options = get_option( 'wp-silo-menus' );
	$pt_str = 'wpsm_post_type_';
	$enabled_post_types = false;
	
	if( empty( $wpsm_options ) )
		return $enabled_post_types;
	
	foreach( $wpsm_options as $option => $value  ) {
		$post_type_options = strpos( $option, $pt_str );
		if( $post_type_options  !== false ) {
			$enabled_post_types[] = str_replace( $pt_str, '', $option  );
		}
	}
	
	return $enabled_post_types;
	
}

/* Fetch the user enabled taxonomies */

function wpsm_enabled_taxonomies() {
	
	$wpsm_options = get_option( 'wp-silo-menus' );
	$pt_str = 'wpsm_taxonomy_';
	$enabled_taxonomies = false;
	
	if( empty( $wpsm_options ) )
		return $enabled_taxonomies;
	
	foreach( $wpsm_options as $option => $value  ) {
		$taxonomy_options = strpos( $option, $pt_str );
		if( $taxonomy_options  !== false ) {
			$enabled_taxonomies[] = str_replace( $pt_str, '', $option  );
		}
	}
	
	return $enabled_taxonomies;
	
}


/**
 * Callback to add Custom Menu Locations feature to the desired post types
 *
 * @param none
 * @return @none
 * @since 1.0
 */

function wpsm_init_feature_post_types() {
	
	// Add post type support for the feature on enabled post types
	$supported_post_types = wpsm_enabled_post_types();
	if( !$supported_post_types )
		return;

	foreach( $supported_post_types as $wpsm_post_type ) {
		add_post_type_support( $wpsm_post_type, 'wpsm-custom-menu-locations' );
	}
	
}


/** 
 * Callback to add Custom Menu Locations feature to the desired taxonomies
 *
 * @param none
 * @return @none
 * @since 1.0
 */

function wpsm_support_taxonomies( $taxonomies ) {
	
	$supported_taxonomies = wpsm_enabled_taxonomies();
	if( !$supported_taxonomies )
		return $taxonomies;
	
	foreach( $supported_taxonomies as $wpsm_taxonomy ) {
		$taxonomies[] = $wpsm_taxonomy;
	}
	
	return $taxonomies;
	
}


/**
 * Helper function to check for WooCommerce Shop page
 *
 * @param none
 * @return @none
 * @since 1.0
 */

function wpsm_is_woo_shop() {
	
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		if( is_shop() ) {
			return true;
		}
	}
	
}


/**
 * Filter the navigation menus on the page / post/ taxonomies depending on user's selection
 * @param type $args
 * @retun array $args
 * @since 1.0
 */

add_filter( 'wp_nav_menu_args', 'wpsm_set_custom_menu' );

function wpsm_set_custom_menu( $args ) {
	
	$enabled_post_types = wpsm_enabled_post_types();
	$enabled_taxonomies = wpsm_enabled_taxonomies();
	
	if ( is_singular() || is_home() || wpsm_is_woo_shop() ) {
		if ( is_home() ) {
			$page_id = get_option( 'page_for_posts' ); // If a page is being used to display blog posts, use its page id
		} else {
			if( wpsm_is_woo_shop() ) {
				$page_id = wc_get_page_id( 'shop' );
			}
			else {
				$page_id = get_the_ID();
			}
		}

		$custom_pt_menus = get_post_meta( $page_id, '_wpsm_custom_menu_locations', true );
		
		// Proceed only if custom menus are set
		if( $custom_pt_menus ) {
			$custom_pt_menus = maybe_unserialize( $custom_pt_menus );
			foreach( $custom_pt_menus as $menu_location => $menu_id ) {
				if ( $args['theme_location'] == $menu_location ) { // Only filter the nav menu that needs to be customized
					if ( $menu_id != '-2' ) { // Make sure that the selection is not set to "Theme Default"
						$args['menu']           = $menu_id;
						$args['fallback_cb']    = -1;
						$args['theme_location'] = -1;
					}
				}
			}
		}
	}
	
	if( !$enabled_taxonomies ) {
		if( is_category() || is_tag() || is_tax() ) {
			$custom_tax_menus = get_option( 'wpsm-tax-menu-locations' );
			// Sometimes empty array elements will throw a warning in the foreach loop, so remove all empty elements from the array
			$custom_tax_menus = is_array( $custom_tax_menus ) ? array_filter( $custom_tax_menus ) : false;
			
			// Bail if nothing
			if( !$custom_tax_menus ) {
				return $args;
			}
			
			// Custom menus are set for the taxonomy. Now let's filter the default nav menu to match the currently selected menu
			if ( $custom_tax_menus ) {
				foreach ( $custom_tax_menus as $term_id => $menu_locations ) {
					if ( is_category( $term_id ) || is_tag( $term_id ) || is_tax( $term_id ) ) {
						foreach ( $menu_locations as $menu_location => $menu_id ) {
							if ( $args['theme_location'] == $menu_location ) { // Only filter the nav menu that needs to be customized
								if ( $menu_id != '-2' ) { // Make sure that the selection is not set to "Theme Default"
									$args['menu']           = $menu_id;
									$args['fallback_cb']    = -1;
									$args['theme_location'] = -1;
								}
							}
						}
					}
				}
			}
		}
	}
	else {
		foreach( $enabled_taxonomies as $wpsm_taxonomy ) {
			if( is_category() || is_tag() || is_tax( $wpsm_taxonomy ) ) {
				$custom_tax_menus = get_option( 'wpsm-tax-menu-locations' );
				// Sometimes empty array elements will throw a warning in the foreach loop, so remove all empty elements from the array
				$custom_tax_menus = is_array( $custom_tax_menus ) ? array_filter( $custom_tax_menus ) : false;
				
				// Bail if nothing
				if( !$custom_tax_menus ) {
					return $args;
				}
				
				// Custom menus are set for the taxonomy. Now let's filter the default nav menu to match the currently selected menu
				if ( $custom_tax_menus ) {
					foreach ( $custom_tax_menus as $term_id => $menu_locations ) {
						if ( is_category( $term_id ) || is_tag( $term_id ) || is_tax( $wpsm_taxonomy, $term_id ) ) {
							foreach ( $menu_locations as $menu_location => $menu_id ) {
								if ( $args['theme_location'] == $menu_location ) { // Only filter the nav menu that needs to be customized
									if ( $menu_id != '-2' ) { // Make sure that the selection is not set to "Theme Default"
										$args['menu']           = $menu_id;
										$args['fallback_cb']    = -1;
										$args['theme_location'] = -1;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	return $args;

}