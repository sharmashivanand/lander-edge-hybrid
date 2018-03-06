<?php

$lander_dir = trailingslashit( get_template_directory() );
$lander_uri = trailingslashit( get_template_directory_uri() );

define( 'HYBRID_DIR', $lander_dir . 'inc/hybrid-core/' );
define( 'HYBRID_URI', $lander_uri . 'inc/hybrid-core/' );

define( '1c', '720' );
define( '2c-l', '720' );

require_once( $lander_dir . 'inc/hybrid-core/hybrid.php' );
include_once( $lander_dir . 'inc/templates/customizer.php' );
include_once( $lander_dir . 'inc/templates/shortcodes.php' );
include_once( $lander_dir . 'inc/ext/load-ext.php' );
require_once( $lander_dir . 'inc/templates/header.php' );
require_once( $lander_dir . 'inc/templates/nav.php' );
require_once( $lander_dir . 'inc/templates/archive-header.php' );
require_once( $lander_dir . 'inc/templates/loop.php' );
require_once( $lander_dir . 'inc/templates/post.php' );
require_once( $lander_dir . 'inc/templates/structure.php' );
require_once( $lander_dir . 'inc/templates/schema.php' );
require_once( $lander_dir . 'inc/templates/comments.php' );
require_once( $lander_dir . 'inc/templates/footer.php' );

function lander() {
	hybrid_get_header();    // Load the header.php template
	lander_build_body();
	hybrid_get_footer();    // Load the footer.php wp template
}



add_action( 'after_setup_theme', 'lander_setup', 5 );

/* setup functions */
function lander_setup() {

	// Theme layouts.
	add_theme_support( 'theme-layouts', array( 'default' => is_rtl() ? '2c-r' : '2c-l' ) );

	// Enable custom template hierarchy.
	add_theme_support( 'hybrid-core-template-hierarchy' );

	// The best thumbnail/image script ever.
	add_theme_support( 'get-the-image' );

	// Breadcrumbs. Yay!
	add_theme_support( 'breadcrumb-trail' );

	// Nicer [gallery] shortcode implementation.
	add_theme_support( 'cleaner-gallery' );

	// Automatically add feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// Enable Lander Extensions
	add_theme_support( 'lander-silo-menus' );
	add_theme_support( 'lander-landing-sections' );
	add_theme_support( 'hybrid-custom-classes' );

	add_theme_support( 'custom-logo' );

    // All BinaryTurf themes are woocommerce compatible. Keep it so!
    // add_theme_support( 'woocommerce' );

	// Post formats.
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) );

	// Handle content width for embeds and images.
	// hybrid_set_content_width( 720 );
}

add_action( 'template_redirect', 'lander_content_width' );

function lander_content_width() {

	hybrid_set_content_width( constant( hybrid_get_theme_layout() ) );

}

// Register custom image sizes.
add_action( 'init', 'lander_register_image_sizes', 5 );

// Register custom menus.
add_action( 'init', 'lander_register_menus', 5 );

// Register custom layouts.
add_action( 'hybrid_register_layouts', 'lander_register_layouts' );

// Register sidebars.
add_action( 'widgets_init', 'lander_register_sidebars', 5 );

// Add custom scripts and styles
add_action( 'wp_enqueue_scripts', 'lander_enqueue_scripts', 5 );
add_action( 'wp_enqueue_scripts', 'lander_enqueue_styles', 5 );


function lander_register_image_sizes() {

	// Sets the 'post-thumbnail' size.
	// set_post_thumbnail_size( 150, 150, true );
}


function lander_register_menus() {
	register_nav_menu( 'primary', esc_html_x( 'Primary', 'nav menu location', 'lander' ) );
	register_nav_menu( 'footer', esc_html_x( 'Footer', 'nav menu location', 'lander' ) );
}

function lander_register_layouts() {

	hybrid_register_layout(
		'1c', array(
			'label' => esc_html__( '1 Column', 'lander' ),
			'image' => '%s/images/layouts/1c.png',
		)
	);
	hybrid_register_layout(
		'2c-l', array(
			'label' => esc_html__( '2 Columns: Content / Sidebar', 'lander' ),
			'image' => '%s/images/layouts/2c-l.png',
		)
	);
	hybrid_register_layout(
		'2c-r', array(
			'label' => esc_html__( '2 Columns: Sidebar / Content', 'lander' ),
			'image' => '%s/images/layouts/2c-r.png',
		)
	);
	hybrid_register_layout(
		'3c-l', array(
			'label' => esc_html__( '3 Columns: Content / Sidebar / Sidebar', 'lander' ),
			'image' => '%s/images/layouts/3c-l.png',
		)
	);
	hybrid_register_layout(
		'3c-c', array(
			'label' => esc_html__( '3 Columns: Sidebar / Content / Sidebar', 'lander' ),
			'image' => '%s/images/layouts/3c-c.png',
		)
	);
	hybrid_register_layout(
		'3c-r', array(
			'label' => esc_html__( '3 Columns: Sidebar / Sidebar / Content', 'lander' ),
			'image' => '%s/images/layouts/3c-r.png',
		)
	);
	// hybrid_register_layout( '2c-r', array( 'label' => esc_html__( '2 Columns: Sidebar / Content', 'lander' ), 'image' => '%s/images/layouts/2c-r.png' ) );
}

function lander_register_sidebars() {

	hybrid_register_sidebar(
		array(
			'id'          => 'primary',
			'name'        => esc_html_x( 'Primary', 'sidebar', 'lander' ),
			'description' => esc_html__( 'This is the primary sidebar.', 'lander' ),
		)
    );
    
	hybrid_register_sidebar(
		array(
			'id'          => 'secondary',
			'name'        => esc_html_x( 'Secondary', 'sidebar', 'lander' ),
			'description' => esc_html__( 'This is the secondary sidebar.', 'lander' ),
		)
    );
    
}


function lander_enqueue_scripts() {
}


function lander_enqueue_styles() {

	// Load one-five base style.
	// wp_enqueue_style( 'hybrid-one-five' );
	// Load gallery style if 'cleaner-gallery' is active.
	if ( current_theme_supports( 'cleaner-gallery' ) ) {
		wp_enqueue_style( 'hybrid-gallery' );
	}

	// Load parent theme stylesheet if child theme is active.
	if ( is_child_theme() ) {
		wp_enqueue_style( 'hybrid-parent' );
	}

	// Load active theme stylesheet.
	wp_enqueue_style( 'hybrid-style' );

	lander_enqueue_font(
		'old_standard_tt',
		array(
			'family' => 'Old+Standard+TT:400,400i,700',
		)
	);
}

function lander_enqueue_font( $handle, $args = array() ) {
	$args['version'] = null;
	hybrid_enqueue_font( $handle, $args );
};

function lander_register_font( $handle, $args = array() ) {
	$args['version'] = null;
	hybrid_register_font( $handle, $args );
};

// add_action( 'lander_after', 'lander_to_dos' );

function lander_to_dos() {
	$todos = array(
		'need separators in the post byline',
		'include footer menu',
		'include author post box after post.',
		'<del>include support for attachments in default content template</del>',
		'<del>verify styles with lander-edge (genesis) and hybrid-base</del>',
		'<del>need to implement CSS for multiple layouts 1c, 2c, full-width etc.</del>',
		'<del>update content width hybrid_set_content_width</del>',
		'wontfix: <del>include support for disabling UI elements</del>',
		'<del>need to remove arial narrow / font-stretch as body font</del>',
		'<del>need to re-enqueue hybrid-one-five</del>',
		'<del>update hyper-land to lander landing sections</del>',
		'<del>support post formats (useful for videos, links etc.)</del>',
		'<del>menu templates</del>',
		'<del>comment &amp; ping templates</del>',
		'<del>include schema support</del>',
		'<del>admin/styles not enqueued for in-built extensions</del>',
		'<del>disable user selection of button on double click / ctrl + a</del>',
		// 'function lander_do_nav > lander_markup > attr : needs to allow for merging of existing attributes?',
		'<del>need to insert normalize.css</del>',
		'<del>need to insert sidebars</del>',
		'<del>need to insert menus</del>',
		'<del>need to insert widgets</del>',
	);

	?>
<details style="border: 1px solid #ca3;font-size:.8em; background: #fea; padding: 1em 2em; position:fixed;top:1em;right:1em;max-width:200px;">
<summary>To Do</summary>
<ol>
<?php
foreach ( $todos as $todo ) {
	echo '<li>';
	echo $todo;
	echo '</li>';
}
?>
</ol>
</details>
<?php
}

function llog( $out ) {
	echo '<pre>';
	print_r( $out );
	echo '</pre>';
}
/*
do_action('lander_init');
do_action('lander_setup');


add_action('lander_init','lander_theme_support');
add_action('lander_setup','lander_register_widget_areas');
add_action('lander_setup','lander_create_initial_layouts');

function lander_theme_support(){}
*/
