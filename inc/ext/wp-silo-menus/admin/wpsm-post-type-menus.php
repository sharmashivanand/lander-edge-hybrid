<?php

/**
 *
 * Genesis SILO Menus — Post Types Custom Menu Locations
 * Description: This file outputs the custom menu locations metabox on supported post types
 
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
 * Adds the Custom Menu Locations metabox to supported post types
 * The plugin renders the metabox for all the available post types by default if selected on the admin page
 * This will also allow users to add / remove this metabox for any post types
 *
 * @return none
 * @since 1.0
 */
 
add_action( 'add_meta_boxes', 'wpsm_post_type_menu_locations_box' );

function wpsm_post_type_menu_locations_box() {
	
	$supported_post_types = wpsm_enabled_post_types();
	
	if( empty( $supported_post_types ) )
		return;
	
	foreach( $supported_post_types as $supported_post_type ) {
		add_meta_box( 'wpsm-custom-menus', sprintf( __( '%s', 'wp-silo-menus' ), WPSM_PLUGIN_NAME ), 'wpsm_pt_custom_menu_locations_cb', $supported_post_type, 'normal', 'default' );
	}
	
}


/**
 * Builds the Custom Menu Locations metabox on the supported post types screen
 *
 * @package Genesis SILO Menus
 * @param $post object
 * @return none
 * @since 1.0
 */
 
function wpsm_pt_custom_menu_locations_cb( $post ) {
	
	global $post, $typenow;
	
	// Try and find any existing meta data for custom menu locations
	$custom_menus = get_post_meta( $post->ID, '_wpsm_custom_menu_locations', true );
	
	// Get all registered navigation menus in the theme
	$all_menus = get_registered_nav_menus(); 
	// Get all the navigation menu objects
	$all_menu_obj = wp_get_nav_menus();
	// Get all active menus for locations
	$active_menus = get_nav_menu_locations();

	// Let's pre-populate the menu selector dropdown with the Default and None options; rest of them would be the active theme locations
	array_unshift( $all_menu_obj, (object) array(
		'name'		=> '&mdash;Theme Default&mdash;',
		'term_id'	=> '-2'
	), (object) array(
		'name'		=> '&mdash;None&mdash;',
		'term_id'	=> '-1'
	) );
	
	//print_r( $active_menus );
	
	if( empty( $active_menus ) ) {
		?>
		<p>
			<span class="wpsm-error"><span class="dashicons dashicons-warning"></span></span> <?php printf( __( '%sYou should first assign menus to the available menu locations on %sMenus screen%s to be able to set custom menus for this %s.', 'wp-silo-menus' ), '<em>', '<a href="' . admin_url( 'nav-menus.php' ) . '">', '</a></em>', $typenow ); ?><span class="clear"></span>
		</p>
		<?php
		return;
	}
	
	?>
	<p>
		<?php _e( 'These settings allow you to set custom menus for each of the menu locations defined by the theme.', 'wp-silo-menus' ); ?>
	</p>
	
	<p>
		<?php printf( __( 'Select the menus to be used in the following menu locations for this %1$s. %2$sChoose %3$sNone%4$s if you wish to hide the menu from this %1$s.%5$s', 'wp-silo-menus' ), $typenow, '<em>', '<strong>', '</strong>', '</em>' ); ?>
	</p>
	
	<table class="widefat fixed" id="menu-locations-table">
		<thead>
		<tr>
			<th scope="col" class="manage-column column-locations"><?php _e( 'Theme Location', 'wp-silo-menus' ); ?></th>
			<th scope="col" class="manage-column column-menus" colspan="2"><?php _e( 'Menu Assigned', 'wp-silo-menus' ); ?></th>
		</tr>
		</thead>		
		<tbody class="menu-locations">
		<?php
		foreach ( $all_menus as $loc_slug => $loc_name ) {
			if ( !has_nav_menu( $loc_slug ) )
				continue;
			?>
			<tr>
			<td class="menu-location-title">
				<label for="locations-<?php echo $loc_slug; ?>"><strong><?php echo $loc_name; ?></strong></label>
			</td>

			<td class="menu-location-menus" colspan="2">
				<select name="wpsm-menu-locations[<?php echo $loc_slug; ?>]" id="locations-<?php echo $loc_slug; ?>">
				<?php
				foreach ( $all_menu_obj as $menu ) {
					$selected = ( is_array( $custom_menus ) ) ? ( $custom_menus[$loc_slug] == $menu->term_id ) : '';
					?>
					<option <?php if ( $selected ) echo 'data-orig="true"'; selected( $selected ); ?> value="<?php echo $menu->term_id; ?>">
						<?php echo wp_html_excerpt( $menu->name, 40, '&hellip;' ); ?>
					</option>
					<?php
				}
				?>
				</select>
			</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
	
	wp_nonce_field( 'wpsm_save_custom_menus', 'wpsm_custom_menus_nonce' );
	
}

/**
 * Save the custom menu locations data set by the user
 */
 
add_action( 'save_post', 'wpsm_save_custom_menu_locations' );

function wpsm_save_custom_menu_locations( $post_id ) {
	
	// Check if our nonce is set.
	if ( !isset( $_POST['wpsm_custom_menus_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( !wp_verify_nonce( $_POST['wpsm_custom_menus_nonce'], 'wpsm_save_custom_menus' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* It's safe for us to save the data now. */
	// Make sure that it is set.
	if ( !isset( $_POST['wpsm-menu-locations'] ) ) {
		return;
	}
	
	$custom_menus = array();

	$locations = $_POST['wpsm-menu-locations'];
	foreach ( $locations as $loc => $menu_id ) {
		$custom_menus[$loc] = $menu_id;
	}

	update_post_meta( $post_id, '_wpsm_custom_menu_locations', $custom_menus );
	
}