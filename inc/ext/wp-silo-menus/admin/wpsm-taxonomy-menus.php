<?php

/**
 *
 * Genesis SILO Menus — Taxonomy Custom Menu Locations
 * Description: This file outputs the custom menu locations metabox on supported taxonomies
 
 * @package Genesis SILO Menus
 * @author Shivanand Sharma
 * @since 1.0
 *
 */
 

/* Bail if accessing directly */
if ( !defined( 'ABSPATH' ) ) {
	wp_die( "Sorry, you are not allowed to access this page directly." );
}
 
add_action( 'admin_init', 'wpsm_taxonomy_menu_locations_box' );

function wpsm_taxonomy_menu_locations_box() {
	// By default, allow only on taxonomy type "Categories"
	$taxonomies = wpsm_enabled_taxonomies();
	// Allow users to filter the default taxonomies
	$tax_names = apply_filters( 'wpsm_taxonomies_custom_menu_locations', $taxonomies );
    
    if( empty( $tax_names ) )
        return;
	
	// Fetch all available taxonomies
	$all_tax = get_taxonomies( array(
		'public' => true
	) );
	
    // Only allow publicly queryable taxonomies
	foreach ( $tax_names as $tax_name ) {
		if ( in_array( $tax_name, $all_tax ) ) {
			add_action( $tax_name . '_edit_form', 'wpsm_taxonomy_custom_menu_locations_cb', 10, 2 );
		}
	}
}


/**
 * Builds the Custom Menu Locations metabox on the supported taxonomies screen
 *
 * @package Genesis SILO Menus
 * @param $taq, $taxonomy
 * @return none
 * @since 1.0
 */
 
function wpsm_taxonomy_custom_menu_locations_cb( $tag, $taxonomy ) {
	
	// Get all taxonomies
	$tax = get_taxonomy( $taxonomy );
	// Fetch the taxonomy custom menu location data if any
	$term_meta = (array) get_option( 'wpsm-tax-menu-locations' );
	$selected = false;
	
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
	
	if( empty( $active_menus ) ) {
		?>
		<p>
			<span class="wpsm-error"><span class="dashicons dashicons-warning"></span></span>  <?php printf( __( '%sYou should first assign menus to the available menu locations on %sMenus screen%s to be able to set custom menus for this %s.', 'wp-silo-menus' ), '<em>', '<a href="' . admin_url( 'nav-menus.php' ) . '">', '</a>', strtolower( $tax->labels->singular_name ), '</em>' ); ?><span class="clear"></span>
		</p>
		<?php
		return;
	}
	
	?>
	<div class="postbox wpsm-taxonomy-box">
	<h3>
		<?php printf( __( '%s', 'wp-silo-menus' ), WPSM_PLUGIN_NAME ); ?>
	</h3>

	<div class="inside">
	<p>
		<?php _e( 'These settings allow you to set custom menus for each of the menu locations defined by the theme.', 'wp-silo-menus' ); ?>
	</p>
	
	<p>
		<?php printf( __( 'Select the menus to be used in the following menu locations for this %1$s. %2$sChoose %3$sNone%4$s if you wish to hide the menu from this %1$s.%5$s', 'wp-silo-menus' ), strtolower( $tax->labels->singular_name ), '<em>', '<strong>', '</strong>', '</em>' ); ?>
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
				<select name="wpsm-tax-menu-locations[<?php echo $loc_slug; ?>]" id="locations-<?php echo $loc_slug; ?>">
				<?php
				foreach ( $all_menu_obj as $menu ) {
					if ( isset( $term_meta[$tag->term_id] ) ) {
						$selected = $term_meta[$tag->term_id][$loc_slug] == $menu->term_id;
					}
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
	</div>
	</div>
	<?php
	
}


/**
 * Save the custom menu locations data set by the user
 */
 
add_action( 'edited_term', 'wpsm_save_tax_custom_menu_locations', 10, 3 );

function wpsm_save_tax_custom_menu_locations( $term_id, $tt_id, $taxonomy ) {
	
	// Check if our nonce is set.
	if ( !isset( $_POST['wpsm-tax-menu-locations'] ) ) {
		return;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;

	/* It's safe for us to save the data now. */
	
	$term_meta = (array) get_option( 'wpsm-tax-menu-locations' );
	$term_meta[$term_id] = isset( $_POST['wpsm-tax-menu-locations'] ) ? $_POST['wpsm-tax-menu-locations'] : array();
	
	$custom_menus = array();

	$locations = $_POST['wpsm-tax-menu-locations'];
	foreach ( $locations as $loc => $menu_id ) {
		$custom_menus[$loc] = $menu_id;
	}

	// Save the option array
	update_option( 'wpsm-tax-menu-locations', $term_meta );
	
}