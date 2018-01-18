<?php

/**
 *
 * Plugin Name: Lander Landing Sections
 * Plugin URI: https://www.binaryturf.com/lander-landing-sections
 * Description: Enables additional wp_editors on edit-screens which can be displayed on front end for landing pages etc..
 * Version: 1.2
 * Author: Shivanand Sharma
 * Author URI: https://www.binaryturf.com
 * License: GPL-2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: lander-landing-sections
 * Credits: Justin Tadlock: http://themehybrid.com/plugins/butterbean, http://justintadlock.com/ WordPress: https://wordpress.org
 */

if ( ! defined( 'LANDER_LS_DIR' ) ) {
	define( 'LANDER_LS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'LANDER_LS_URL' ) ) {
	define( 'LANDER_LS_URL', plugin_dir_url( __FILE__ ) );
}

//require_once( LANDER_LS_DIR . 'customize.php' );
// require_once( LANDER_LS_DIR . 'front-end.php' );
add_action( 'admin_print_styles', 'lander_landing_sections_admin_styles' );

add_action( 'init', 'lander_landing_sections_init_post_type_support' );
add_action( 'add_meta_boxes', 'lander_landing_sections_add_editors_meta_box', 9 );

add_action( 'save_post', 'lander_landing_sections_save_editors' );



function lander_landing_sections_admin_styles() {
	wp_enqueue_style( 'lander-landing-sections-admin-style', LANDER_LS_URL . 'admin.css' );
}

/**
 *  Registers support for additional wp editors on post types
 *  Applies filter `lander_landing_sections_supported_posts` for control over supported post types
 */
function lander_landing_sections_init_post_type_support() {
	$args = array(
		'public' => true,
	);

	$output   = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'

	$post_types = get_post_types( $args, $output, $operator );

	$post_types = apply_filters( 'lander_landing_sections_supported_posts', $post_types );

	foreach ( $post_types as $post_type ) {

		add_post_type_support( $post_type, 'lander-landing-sections-editors' );
	}

}

/*
=======================================================/
					UX ELEMENT TOGGLE
/=======================================================*/

/**
 * Returns default landing sections
 * Applies filter `lander_landing_sections_get_editors`
 */
function lander_landing_sections_get_editors() {
	$lander_landing_sections_landing_sections = array(
		'after_header_first' => array(
			'context' => 'after-header-first', // used for id/classname
			'heading' => __( 'Landing Section After Header First', 'lander-landing-sections' ), // used for heading on the backend
			'hook' => 'lander_after_header', // hook where the section will be output
			'priority' => '10', // priority of the hook
		),
		'after_header_second' => array(
			'context' => 'after-header-second',
			'heading' => __( 'Landing Section After Header Second', 'lander-landing-sections' ),
			'hook' => 'lander_after_header',
			'priority' => '11',
		),
		'after_header_third' => array(
			'context' => 'after-header-third',
			'heading' => __( 'Landing Section After Header Third', 'lander-landing-sections' ),
			'hook' => 'lander_after_header',
			'priority' => '12',

		),
		'after_header_fourth' => array(
			'context' => 'after-header-fourth',
			'heading' => __( 'Landing Section After Header Fourth', 'lander-landing-sections' ),
			'hook' => 'lander_after_header',
			'priority' => '13',

		),
		'after_header_fifth' => array(
			'context' => 'after-header-fifth',
			'heading' => __( 'Landing Section After Header Fifth', 'lander-landing-sections' ),
			'hook' => 'lander_after_header',
			'priority' => '14',

		),
		'after_header_sixth' => array(
			'context' => 'after-header-sixth',
			'heading' => __( 'Landing Section After Header Sixth', 'lander-landing-sections' ),
			'hook' => 'lander_after_header',
			'priority' => '15',

		),
		'before_footer_first' => array(
			'context' => 'before-footer-first',
			'heading' => __( 'Landing Section Before Footer First', 'lander-landing-sections' ),
			'hook' => 'lander_footer',
			'priority' => '4',
		),
		'before_footer_second' => array(
			'context' => 'before-footer-second',
			'heading' => __( 'Landing Section Before Footer Second', 'lander-landing-sections' ),
			'hook' => 'lander_footer',
			'priority' => '4',
		),
		'before_footer_third' => array(
			'context' => 'before-footer-third',
			'heading' => __( 'Landing Section Before Footer Third', 'lander-landing-sections' ),
			'hook' => 'lander_footer',
			'priority' => '4',
		),

	);

	return apply_filters( 'lander_landing_sections_get_editors', $lander_landing_sections_landing_sections );
}

/**
 * AddMetaBox for posts/pages/CPT
 *
 * @return void
 */
function lander_landing_sections_add_editors_meta_box() {
	global $post;

	if ( get_option( 'show_on_front' ) == 'page' ) {
		$posts_page_id = get_option( 'page_for_posts' );
		if ( $posts_page_id == $post->ID ) {
			add_action( 'edit_form_after_title', 'lander_landing_sections_notice' );
			return;
		}
	}
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		if ( $post->ID == wc_get_page_id( 'shop' ) ) {
			add_action( 'edit_form_after_title', 'lander_landing_sections_notice' );
			return;
		}
	}

	$context  = 'normal';
	$priority = 'high';
	foreach ( (array) get_post_types(
		array(
			'public' => true,
		)
	) as $type ) {
		if ( post_type_supports( $type, 'lander-landing-sections-editors' ) ) {
			add_meta_box( 'lander-landing-sections-editors', sprintf( __( '%s Editors', 'lander-landing-sections' ), 'Lander Landing Section' ), 'lander_landing_sections_editors_box_post', $type, $context, $priority );
		}
	}
}

function lander_landing_sections_get_meta_prefix() {
	$prefix = apply_filters( 'lander_landing_sections_get_meta_prefix', 'lander_landing_sections_editor_' );
}

function lander_landing_sections_notice() {
	echo '<div class="notice notice-warning inline"><p>' . __( 'Editor sections are not available on the posts page.', 'lander-landing-sections' ) . '</p></div>';
}

function lander_landing_sections_editors_box_post( $post ) {
	global $post;
	wp_nonce_field( basename( __FILE__ ), 'lander_landing_sections_editors_post_nonce' );
	$title_placeholder         = 'Enter the editor title here';
	$class_placeholder         = 'Enter the CSS classes for this section &lpar;comma separated&rpar;';
	$lander_landing_sections_editor_options = get_post_meta( $post->ID, '_lander_landing_sections_editors', 1 );
	$lander_landing_sections_editors   = lander_landing_sections_get_editors();

	foreach ( $lander_landing_sections_editors as $lander_landing_sections_editor => $lander_landing_sections_editor_value ) {
		$section_title        = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title' ] : '';
		$section_content      = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content' ] : '';
		$section_class        = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class' ] : '';
		$hide_section_desktop = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop' ] : false;
		$hide_section_mobile  = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile' ] : false;
?>
		<div class="landing-section-stuff">
			<h4><?php echo $lander_landing_sections_editor_value['heading'];?></h4>
			<div class="section-title">
				<label class="title-prompt-text" for="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title';?>"><?php echo $title_placeholder; ?></label>
				<input type="text" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title';?>" value="<?php echo $section_title;?>" id="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title';?>" spellcheck="true" autocomplete="off" />
			</div>
			<div class="section-content"><?php
			$settings = array(
				'textarea_name' => lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content',
				'textarea_rows' => 7,
				'dfw' => true,
				'drag_drop_upload' => true,
			);
			wp_editor( $section_content, lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content', $settings );
?></div>

			<p class="section-class">
				<label class="class-prompt-text" for="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class'; ?>"><?php echo $class_placeholder; ?><input type="text" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class'; ?>" value="<?php echo $section_class; ?>" id="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class'; ?>" spellcheck="true" autocomplete="off" /></label>
			</p>


			<p>
				<input type="checkbox" id="<?php
				echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop';
		?>" name="<?php
		echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop';
		?>" value="true" <?php
		checked( $hide_section_desktop, true );
		?> />
			<label for="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop';?>"><?php _e( 'Hide on Desktop', 'lander-landing-sections' ); ?></label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile'; ?>" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile';?>" value="true" <?php checked( $hide_section_mobile, true );?> />
				<label for="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile';?>"><?php _e( 'Hide on Mobile', 'lander-landing-sections' );?></label>
			</p>
		</div>
		<?php
	}
}

/**
 * Save landing sections on posts
 *
 * @param [type] $post_id
 * @return void
 */
function lander_landing_sections_save_editors( $post_id ) {

	// Verify that the nonce is valid.
	if ( ! lander_landing_sections_verify_nonce_post( basename( __FILE__ ), 'lander_landing_sections_editors_post_nonce' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/*
	 It's safe for us to save the data now. */
	// Make sure that it is set.
	$lander_landing_sections_editor_options = array();
	$lander_landing_sections_editors   = lander_landing_sections_get_editors();

	foreach ( $lander_landing_sections_editors as $lander_landing_sections_editor => $lander_landing_sections_editor_val ) {
		$section_name                                                       = $lander_landing_sections_editor;
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ]        = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ] ) ? sanitize_text_field( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ] ) : '';
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ]      = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ] ) ? ( current_user_can( 'unfiltered_html' ) ? $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ] : wp_filter_post_kses( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ] ) ) : '';
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ]      = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ] ) ? ( current_user_can( 'unfiltered_html' ) ? $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ] : wp_filter_post_kses( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ] ) ) : '';
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_desktop' ] = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_desktop' ] ) ? true : false;
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_mobile' ]  = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_mobile' ] ) ? true : false;
	}
	// hflog($_POST);
	// die();
	update_post_meta( $post_id, '_lander_landing_sections_editors', $lander_landing_sections_editor_options );
}

function lander_landing_sections_get_section_context( $lander_landing_sections_section ) {
	$lander_landing_sections_sections = lander_landing_sections_get_editors();
	if ( array_key_exists( $lander_landing_sections_section, $lander_landing_sections_sections ) ) {
		return $lander_landing_sections_sections[ $lander_landing_sections_section ]['context'];
	} else {
		return;
	}
}

add_filter( 'lander_landing_sections_view_allowed', 'lander_landing_sections_output_sections' );

/**
 * Check if sections are allowed on the view(s)
 *
 * @param [type] $show
 * @return void
 */
function lander_landing_sections_output_sections( $show ) {
	if ( is_home() ) {
		return false;
	}

	if ( is_404() || is_search() ) {

		return false;
	}

	if ( is_singular() ) {

		return true;
	}

	if ( is_category() || is_tag() || is_tax() ) {
		if ( is_paged() ) {
			return false;
		} else {
			return true;
		}
	}

	return false;   // by default no landing sections if not for the views above
}

/*
*
* Outputs landing sections on the front end
*
*/

function lander_landing_sections_section_markup( $section_name ) {

	$allowed = apply_filters( 'lander_landing_sections_view_allowed', false );  // are landing sections allowed on this view?

	if ( ! $allowed ) {
		return;
	}

	/*
	*
	* This function can use the tax instead of global post
	*
	*/
	if ( is_singular() ) {
		global $post;
		$lander_landing_sections_section_options = get_post_meta( $post->ID, '_lander_landing_sections_editors', 1 );
	}

	if ( is_tax() || is_category() || is_tag() ) {
		$lander_landing_sections_section_options = get_term_meta( get_queried_object_id(), '_lander_landing_sections_editors', 1 );
	}

	$context                   = lander_landing_sections_get_section_context( $section_name );

	if ( ! $lander_landing_sections_section_options ) {
		return;
	}

	$section_title        = $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ];
	$section_title        = apply_filters( 'lander_landing_section_title', $section_title );

	$section_classes        = $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ];

	// $section_classes = (!empty($section_class)) ? $context . ' ' . $section_classes : $context;
	if ( ! empty( $section_classes ) ) {
		$section_classes = $context . ' ' . $section_classes . ' lander-landing-sections-landing-section';
	} else {
		$section_classes = $context . ' lander-landing-sections-landing-section';
	}

	$section_content      = $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ];

	$hide_section_desktop = isset( $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_desktop' ] ) ? $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_desktop' ] : false;
	$hide_section_mobile  = isset( $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_mobile' ] ) ? $lander_landing_sections_section_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_mobile' ] : false;

	// Hide if hidden and not visiting on mobile
	if ( $hide_section_desktop && ! wp_is_mobile() ) {
		return;
	}

	// Hide if hidden and only on mobile
	if ( $hide_section_mobile && wp_is_mobile() ) {
		return;
	}

	if ( $section_title || $section_content ) {
?>
		<section class="<?php echo $section_classes; ?>">
        <div class="wrap ls-<?php echo $context;?> landing-section">
				<?php
				if ( $section_title ) {
					echo '<h2 class="lander-landing-sections-section-title">' . $section_title . '</h2>';
				}
?>
				<?php
				if ( $section_content ) {
					echo '<div class="lander-landing-sections-section-content">' . apply_filters( 'the_content', $section_content ) . '</div>';
				}
?>
				</div>
			<?php
			// lander_structural_wrap_close( $context );
?>
		</section>
	<?php
	}
}


/*************** Taxonomy Functions */

add_action( 'load-edit-tags.php', 'lander_landing_sections_add_view_link' );

function lander_landing_sections_add_view_link() {
	$screen = get_current_screen();
	add_action( "{$screen->taxonomy}_term_edit_form_top", 'lander_landing_sections_display_term_view_link', 10, 2 );
}

function lander_landing_sections_display_term_view_link( $tag, $taxonomy ) {
	echo '<h3><a href="' . get_term_link( $tag ) . '">View</a></h3>';
}

add_action( 'load-edit-tags.php', 'lander_landing_sections_tax_init' );
add_action( 'edit_term', 'lander_landing_sections_save_tax_sections' );

function lander_landing_sections_tax_init() {
	$screen = get_current_screen();
	add_action( "{$screen->taxonomy}_edit_form_fields", 'lander_landing_sections_render_tax_form', 10, 2 );
	wp_enqueue_style( 'lander-landing-sections-admin-style', LANDER_LS_URL . 'admin.css' );
}

/**
 * Render the landing sections on the taxonomy screen
 *
 * @param [type] $tag
 * @param [type] $taxonomy
 * @return void
 */
function lander_landing_sections_render_tax_form( $tag, $taxonomy ) {

	$title_placeholder         = 'Enter the editor title here';
	$class_placeholder         = 'Enter the CSS classes for this section &lpar;comma separated&rpar;';
	$lander_landing_sections_editor_options = get_term_meta( $tag->term_id, '_lander_landing_sections_editors', 1 );
	$lander_landing_sections_editors   = lander_landing_sections_get_editors();

	echo '<tr><td>';
	wp_nonce_field( basename( __FILE__ ), 'lander_landing_sections_editors_tax_nonce' );
	echo '</td></tr>';

	foreach ( $lander_landing_sections_editors as $lander_landing_sections_editor => $lander_landing_sections_editor_value ) {
		$section_title        = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title' ] : '';
		$section_content      = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content' ] : '';
		$section_class      = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class' ] : '';
		$hide_section_desktop = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop' ] : false;
		$hide_section_mobile  = isset( $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile' ] ) ? $lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile' ] : false;
?>
		<tr><th><h4>
		<?php
		echo $lander_landing_sections_editor_value['heading'];
		?>
		</h4></th><td>
		
		<div class="landing-section-stuff">
			
			<div class="section-title">
				<label class="title-prompt-text" for="<?php
				echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title';
		?>"> <?php
		echo $title_placeholder;
		?></label>
				<input type="text" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title';
		?>" value="<?php
		echo $section_title;
		?>" id="<?php
	    echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_title';
		?>" spellcheck="true" autocomplete="off" />
			</div>
			<div class="section-content">
			<?php
			$settings = array(
				'textarea_name' => lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content',
				'textarea_rows' => 7,
				'dfw' => true,
				'drag_drop_upload' => true,
			);
			wp_editor( $section_content, lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_content', $settings );
?>
			</div>

			<div class="section-class">
				<label class="class-prompt-text" for="<?php lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class';?>"><?php echo $class_placeholder;?></label>
				<input type="text" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class';?>" value="<?php echo $section_class;?>" id="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_class';?>" spellcheck="true" autocomplete="off" />
			</div>


			<p>
				<input type="checkbox" id="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop';?>" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop';?>" value="true" <?php checked( $hide_section_desktop, true );?>/>
				<label for="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_desktop';?>">
		<?php
		_e( 'Hide on Desktop', 'lander-landing-sections' );
		?>
		</label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile';?>" name="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile';?>" value="true" <?php checked( $hide_section_mobile, true );?> />
				<label for="<?php echo lander_landing_sections_get_meta_prefix() . $lander_landing_sections_editor . '_hide_mobile';?>">
		<?php
		_e( 'Hide on Mobile', 'lander-landing-sections' );
		?>
		</label>
			</p>
		</div></td></tr>
		<?php
	}

}

/**
 * Saves the Taxonomy landing sections
 *
 * @param [type] $term_id
 * @return void
 */
function lander_landing_sections_save_tax_sections( $term_id ) {

	// Verify that the nonce is valid.
	if ( ! lander_landing_sections_verify_nonce_post( basename( __FILE__ ), 'lander_landing_sections_editors_tax_nonce' ) ) {
		return;
	}

	// Check the user's permissions.
	if ( ! current_user_can( 'edit_term', $term_id ) ) {
		return;
	}

	/*
	 It's safe for us to save the data now. */
	// Make sure that it is set.
	$lander_landing_sections_editor_options = array();
	$lander_landing_sections_editors   = lander_landing_sections_get_editors();

	foreach ( $lander_landing_sections_editors as $lander_landing_sections_editor => $lander_landing_sections_editor_val ) {
		$section_name                                                       = $lander_landing_sections_editor;
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ]        = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ] ) ? sanitize_text_field( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_title' ] ) : '';
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ]      = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ] ) ? ( current_user_can( 'unfiltered_html' ) ? $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ] : wp_filter_post_kses( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_content' ] ) ) : '';
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ]      = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ] ) ? ( current_user_can( 'unfiltered_html' ) ? $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ] : wp_filter_post_kses( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_class' ] ) ) : '';
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_desktop' ] = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_desktop' ] ) ? true : false;
		$lander_landing_sections_editor_options[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_mobile' ]  = isset( $_POST[ lander_landing_sections_get_meta_prefix() . $section_name . '_hide_mobile' ] ) ? true : false;
	}

	update_term_meta( $term_id, '_lander_landing_sections_editors', $lander_landing_sections_editor_options );
}


/*
function lander_landing_sections_get_public_post_types() {

	$args = array(
		'public' => true,
		'show_ui' => true,
	);

	$available_post_types = get_post_types( $args, 'objects' );

	return $available_post_types;

}
*/


function lander_landing_sections_verify_nonce_post( $action = '', $arg = '_wpnonce' ) {
	return isset( $_POST[ $arg ] ) ? wp_verify_nonce( sanitize_key( $_POST[ $arg ] ), $action ) : false;
}
