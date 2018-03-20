<?php

/**
 *
 * @since 1.0.0
 * Post rendering functions.
 */

add_action( 'lander_entry', 'hybrid_get_content_template' );

add_action( 'lander_after_endwhile', 'lander_prev_next' );
add_action( 'lander_loop_else', 'lander_404_content' );

/**
 * Post pagination
 *
 * @return void
 */
function lander_prev_next() {
	if ( is_singular( 'post' ) ) { // If viewing a single post page. ?>

		<div class="loop-nav">
			<?php previous_post_link( '<div class="prev">' . esc_html__( 'Previous Post: %link', 'lander' ) . '</div>', '%title' ); ?>
			<?php next_post_link( '<div class="next">' . esc_html__( 'Next Post: %link', 'lander' ) . '</div>', '%title' ); ?>
		</div><!-- .loop-nav -->
	
	<?php
	} elseif ( is_home() || is_archive() || is_search() ) { // If viewing the blog, an archive, or search results.
	?>
	
		<?php
		the_posts_pagination(
			array(
				'prev_text' => esc_html_x( '&larr; Previous', 'posts navigation', 'lander' ),
				'next_text' => esc_html_x( 'Next &rarr;', 'posts navigation', 'lander' ),
			)
		);
		?>
	
	<?php
	} // End check for type of page being viewed.
}



/**
 * Lander 404 content
 *
 * @return void
 * @since 1.0.0
 */
function lander_404_content() {
	lander_markup(
		array(
			'open'    => '<article %s>',
			'slug' => 'entry',
		)
	);
	?>

	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( apply_filters( 'lander_404_title', 'Nothing found' ), 'lander' ); ?></h1>
	</header><!-- .entry-header -->

	<div <?php hybrid_attr( 'entry-content' ); ?>>
		<?php echo wpautop( esc_html__( apply_filters( 'lander_404_content', 'Apologies, but no entries were found.' ), 'lander' ) ); ?>
	</div><!-- .entry-content -->

	<?php
	lander_markup(
		array(
			'close'    => '</article>',
			'slug' => 'entry',
		)
	);
}

/*
function lander_entry_content(){

	$post_type = get_post_type();

	if(is_singular()){
		//call_user_func("lander_{$post_type}_singular");
		echo "lander_{$post_type}_singular";
	}
	else {
		//call_user_func("lander_{$post_type}_plural");
		echo "lander_{$post_type}_plural";
	}
}

add_action( 'lander_entry_content' ,'lander_post_content' );

*/



// add_action( 'lander_aside_content', 'lander_aside_content' );
/* === Template functions === */

add_action( 'lander_entry_header', 'lander_post_header', 10, 2 );
add_action( 'lander_entry_content', 'lander_post_content', 10, 2 );
add_action( 'lander_entry_footer', 'lander_post_footer', 10, 2 );

/* --- content.php / regular post --- */
add_filter( 'show_entry_header', 'toggle_default_entry_header', 10, 2 );

function toggle_default_entry_header( $type, $format ) {
	if ( 'aside' == $format && ! is_singular() ) {
		return false;
	}
	return true;
}
add_filter( 'show_entry_footer', 'toggle_default_entry_footer', 10, 2 );

function toggle_default_entry_footer( $type, $format ) {
	if ( ! is_singular() ) {
		return false;
	}
	return true;
}

function lander_post_header( $type, $format ) {

	if ( ! apply_filters( 'show_entry_header', true, $type, $format ) ) { // Allow certain post formats to short-circuit this function.
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
	</header><!-- .entry-header -->
	<?php
}


function lander_post_byline(){
    if ( is_page() ) {
        ?>
        <p class="entry-byline">
            <span <?php hybrid_attr( 'entry-author' ); ?>>
                <meta itemprop="name" content="<?php echo get_author_posts_url( get_the_author_meta( 'nicename' ) ); ?>" />
                <meta itemprop="url" content="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" />
            </span>
            <meta <?php hybrid_attr( 'entry-published' ); ?> content="<?php echo get_the_date(); ?>" />
            <meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
            <?php
            if ( post_type_supports( $type, 'comments' ) && ( have_comments() || comments_open() || pings_open() ) ) {
                comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); }
                ?>
            <?php
            if ( $format ) {
                hybrid_post_format_link();
            }
            edit_post_link(); ?>
        </p><!-- .entry-byline -->
        <?php
    } 
    else {
        ?>
        <p class="entry-byline">
        <span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
        <time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
        <meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
        <?php
        if ( post_type_supports( $type, 'comments' ) && ( have_comments() || comments_open() || pings_open() ) ) {
            comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); }
            ?>
        <?php
        if ( $format ) {
            hybrid_post_format_link();
        }
    edit_post_link(); ?>
    </p><!-- .entry-byline -->
        <?php
    }
}
/**
 * Recursively print all meta properties of an attachment
 *
 * @param array $arr
 * @return html ul / li element(s)
 */
function langer_get_array_meta( $arr = array() ) {
		$output = '';
	foreach ( $arr as $key => $val ) {
		if ( is_array( $val ) ) {
			$output .= '<li><strong>' . ucwords( str_replace( '_', ' ', esc_html($key) ) ) . ':</strong><ul class="children">' . langer_get_array_meta( $val ) . '</ul>' . '</li>';
		} else {
			$output .= '<li><strong>' . ucwords( str_replace( '_', ' ', esc_html($key) ) ) . ':</strong> ' . $val . '</li>';
		}
	}
		return $output;
}

function lander_post_content( $type, $format ) {
	if ( is_singular() ) {
		?>
		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php

			if ( apply_filters( 'lander_show_featured_image_on_singular', false ) && ! wp_attachment_is_image() ) {
				get_the_image(
					array(
						'image_class' => '',
						'size' => 'full',
						'split_content' => true,
						'scan_raw' => true,
						'scan' => true,
						'order' => array( 'featured', 'scan_raw', 'scan', 'attachment' ),
					)
				);
			}
			if ( is_attachment() ) {
				hybrid_attachment(); // Embed the attachment.
				// get_the_image(array('size'=>'full'));
				if ( wp_attachment_is_image() && has_excerpt() ) {
					$src = wp_get_attachment_image_src( get_the_ID(), 'full' );
					echo img_caption_shortcode(
						array(
							'width' => esc_attr( $src[1] ),
							'caption' => get_the_excerpt(),
						), wp_get_attachment_image( get_the_ID(), 'full', false )
					);
				}

				if ( wp_attachment_is_image() && ! has_excerpt() ) {
					echo wp_get_attachment_image( get_the_ID(), 'full', false );
				}

				$attachment_attribs = wp_get_attachment_metadata( get_the_ID() );
				if ( $attachment_attribs ) {
					echo '<ul class="media-meta">';
					echo langer_get_array_meta( $attachment_attribs );
					echo '</ul>';
				}

				echo '<a href="' . wp_get_attachment_url() . '"><cite>Source: </cite>' . get_the_title() . '</a>';

				// the_attachment_link( get_the_ID(), true, false, true ); // Append the attachment link sinch HC removes it by default.
			}

				the_content();
				wp_link_pages();
			?>
		</div><!-- .entry-content -->
		<?php
	} else { // we are in archive view. this is complicated. We want to pull media, featured image and selectively do excerpts for certain formats.

		if ( $do_content = ! apply_filters( 'lander_excerpts', true ) || ( 'quote' == $format || 'status' == $format || 'link' == $format || 'aside' == $format ) ) { // short posts can live with the content
			?>
			<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php
		} else {
			?>
			<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php
		}

		if ( apply_filters( 'lander_show_featured_image_on_archives', true ) ) {

			get_the_image(
				array(
					'image_class' => '',
					'size' => 'full',
					'split_content' => true,
					'scan_raw' => true,
					'scan' => true,
					'order' => array( 'featured', 'scan_raw', 'scan', 'attachment' ),
				)
			);
		}
		if ( ! $do_content ) {
			if ( 'audio' == $format || 'video' == $format ) {
				echo hybrid_get_post_media(
					array(
						'type' => $format,
						'split_media' => true,
					)
				);
			}

				the_excerpt();

			if ( 'gallery' == $format ) {
				if ( $count = hybrid_get_gallery_item_count() ) {
							?>
							 <p class="gallery-count"><?php printf( esc_html( _n( 'This gallery contains %s item.', 'This gallery contains %s items.', $count, 'lander' ) ), $count ); ?></p>
					<?php
				}
			}
		} else {
			the_content();
			// wp_link_pages(); // Why do we need post-content pagination on archives?
		}
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

function lander_post_footer( $type, $format ) {
    echo apply_filters( 'show_entry_footer', true, $type, $format );
	if ( ! apply_filters( 'show_entry_footer', true, $type, $format ) ) { // Allow certain post formats to short-circuit this function.
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		$tax = get_object_taxonomies( $type );
		// echo '<pre>';
		// var_dump($tax);
		// echo '</pre>';
		if ( in_array( 'category', $tax ) ) {
			hybrid_post_terms(
				array(
					'taxonomy' => 'category',
					'text' => esc_html__( 'Posted in %s', 'lander' ),
				)
			);
		}
?>
		<?php
		if ( in_array( 'post_tag', $tax ) ) {
			hybrid_post_terms(
				array(
					'taxonomy' => 'post_tag',
					'text' => esc_html__( 'Tagged %s', 'lander' ),
					'before' => '<br />',
					'after' => '<br />',
				)
			);
		}
?>
		
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}


/* --- aside.php / aside post format --- */

function lander_aside_header() {
	if ( ! apply_filters( 'lander_aside_header', is_singular() ) ) { // By default show header only on is_singular()
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
		
	<p class="entry-byline">
		<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
		<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
		<meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
		<?php comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); ?>
		<?php edit_post_link(); ?>
		<?php hybrid_post_format_link(); ?>
	</p><!-- .entry-byline -->
	
			</header><!-- .entry-header -->
	<?php
}

function lander_aside_content() {
	?>
	<div <?php hybrid_attr( 'entry-content' ); ?>>
		<?php
			the_content();
		if ( is_singular() ) {
			wp_link_pages();
		}
			?>
		</div><!-- .entry-content -->
		<?php
}

function lander_aside_footer() {

	if ( ! apply_filters( 'lander_aside_footer', is_singular() ) ) {    // By default show footer only on is_singular()
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'category',
				'text' => esc_html__( 'Posted in %s', 'lander' ),
			)
		);
?>
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'post_tag',
				'text' => esc_html__( 'Tagged %s', 'lander' ),
				'before' => '<br />',
			)
		);
?>
		<br />
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}

/* --- attachment-audio.php --- */

function lander_attachment_audio_header() {
	if ( ! apply_filters( 'lander_attachment_audio_header', true ) ) {
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
   
	<p class="entry-byline">
		<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
		<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
		<meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
		<?php comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); ?>
		<?php edit_post_link(); ?>
	</p><!-- .entry-byline -->

	</header><!-- .entry-header -->
	<?php
}

function lander_attachment_audio_footer() {
	if ( ! apply_filters( 'lander_attachment_audio_footer', is_singular() ) ) {
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'category',
				'text' => esc_html__( 'Posted in %s', 'lander' ),
			)
		);
?>
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'post_tag',
				'text' => esc_html__( 'Tagged %s', 'lander' ),
				'before' => '<br />',
			)
		);
?>
		<br />
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}

function lander_attachment_audio_content() {
	if ( is_attachment() ) {
		?>
		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php
				hybrid_attachment();
				the_content();
				?>
				<details class="attachment-meta">

						<summary><?php esc_html_e( 'Audio Info', 'lander' ); ?></summary>

						<ul class="media-meta">
							<?php $pre = '<li><span class="prep">%s</span>'; ?>
							<?php
							hybrid_media_meta(
								'length_formatted', array(
									'before' => sprintf( $pre, esc_html__( 'Run Time', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'artist', array(
									'before' => sprintf( $pre, esc_html__( 'Artist', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'composer', array(
									'before' => sprintf( $pre, esc_html__( 'Composer', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'album', array(
									'before' => sprintf( $pre, esc_html__( 'Album', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'track_number', array(
									'before' => sprintf( $pre, esc_html__( 'Track', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'year', array(
									'before' => sprintf( $pre, esc_html__( 'Year', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'genre', array(
									'before' => sprintf( $pre, esc_html__( 'Genre', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_type', array(
									'before' => sprintf( $pre, esc_html__( 'Type', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_name', array(
									'before' => sprintf( $pre, esc_html__( 'Name', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_size', array(
									'before' => sprintf( $pre, esc_html__( 'Size', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'mime_type', array(
									'before' => sprintf( $pre, esc_html__( 'Mime Type', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
						</ul>

				</details><!-- .attachment-meta -->
			<?php
				wp_link_pages();
			?>
		</div><!-- .entry-content -->
		<?php
	} else {
		?>
		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php
				get_the_image(
					array(
						'size' => 'full',
						'order' => array( 'featured', 'attachment' ),
					)
				);
				the_excerpt();
			?>
		</div><!-- .entry-content -->
		<?php
	}
}


/* --- attachment-image.php --- */


function lander_attachment_image_header() {
	if ( ! apply_filters( 'lander_attachment_image_header', true ) ) { // By default show header only on is_singular()
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
		
	<p class="entry-byline">
		<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
		<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
		<meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
		<?php comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); ?>
		<?php edit_post_link(); ?>
		<span class="image-sizes"><?php printf( esc_html__( 'Sizes: %s', 'hybrid-base' ), hybrid_get_image_size_links() ); ?></span>
	</p><!-- .entry-byline -->
	
			</header><!-- .entry-header -->
	<?php
}

function lander_attachment_image_content() {
	if ( is_attachment() ) {   // If is_singular
		?>
		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php

			if ( has_excerpt() ) : // If the image has an excerpt/caption.
				?>

					<?php $src = wp_get_attachment_image_src( get_the_ID(), 'full' ); ?>
		
					<?php
					echo img_caption_shortcode(
						array(
							'align' => 'aligncenter',
							'width' => esc_attr( $src[1] ),
							'caption' => get_the_excerpt(),
						), wp_get_attachment_image( get_the_ID(), 'full', false )
					);
?>
		
				<?php else : // If the image doesn't have a caption. ?>
		
					<?php echo wp_get_attachment_image( get_the_ID(), 'full', false, array( 'class' => 'aligncenter' ) ); ?>
		
				<?php
				endif; // End check for image caption.

				the_content();
				?>
				<div class="attachment-meta">

					<details class="media-info image-info">
			
						<summary><?php esc_html_e( 'Image Info', 'lander' ); ?></summary>
			
						<ul class="media-meta">
							<?php $pre = '<li><span class="prep">%s</span>'; ?>
							<?php
							hybrid_media_meta(
								'dimensions', array(
									'before' => sprintf( $pre, esc_html__( 'Dimensions', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'created_timestamp', array(
									'before' => sprintf( $pre, esc_html__( 'Date', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'camera', array(
									'before' => sprintf( $pre, esc_html__( 'Camera', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'aperture', array(
									'before' => sprintf( $pre, esc_html__( 'Aperture', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'focal_length', array(
									'before' => sprintf( $pre, esc_html__( 'Focal Length', 'lander' ) ),
									'after' => '</li>',
									'text' => esc_html__( '%s mm', 'lander' ),
								)
							);
?>
							<?php
							hybrid_media_meta(
								'iso', array(
									'before' => sprintf( $pre, esc_html__( 'ISO', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'shutter_speed', array(
									'before' => sprintf( $pre, esc_html__( 'Shutter Speed', 'lander' ) ),
									'after' => '</li>',
									'text' => esc_html__( '%s sec', 'lander' ),
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_type', array(
									'before' => sprintf( $pre, esc_html__( 'Type', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_name', array(
									'before' => sprintf( $pre, esc_html__( 'Name', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'mime_type', array(
									'before' => sprintf( $pre, esc_html__( 'Mime Type', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
						</ul>
			
					</details><!-- .media-info -->
		
				<?php
				$gallery = gallery_shortcode(
					array(
						'columns' => 4,
						'numberposts' => 8,
						'orderby' => 'rand',
						'id' => get_queried_object()->post_parent,
						'exclude' => get_the_ID(),
					)
				);
?>
		
				<?php if ( $gallery ) : // Check if the gallery is not empty. ?>
		
					<div class="image-gallery">
						<h3 class="attachment-meta-title"><?php esc_html_e( 'Gallery', 'lander' ); ?></h3>
						<?php echo $gallery; ?>
					</div>
		
				<?php endif; // End gallery check. ?>
		
				</div><!-- .attachment-meta -->
			<?php
				wp_link_pages();
			?>
		</div><!-- .entry-content -->
		<?php
	} else {  // If not is_singular
		?>
		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php

			if ( apply_filters( 'lander_excerpts', true ) ) {
				get_the_image(
					array(
						'size' => 'full',
						'order' => array( 'featured', 'attachment' ),
					)
				);
				the_excerpt();
			} else {
				the_content();
				// wp_link_pages(); // Why do we need post-content pagination on archives?
			}
			?>
		</div><!-- .entry-content -->
		<?php
	}
}


function lander_attachment_image_footer() {

	if ( ! apply_filters( 'lander_attachment_image_footer', is_singular() ) ) {    // By default show footer only on is_singular()
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'category',
				'text' => esc_html__( 'Posted in %s', 'lander' ),
			)
		);
?>
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'post_tag',
				'text' => esc_html__( 'Tagged %s', 'lander' ),
				'before' => '<br />',
			)
		);
?>
		<br />
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}


/* --- attachment-video.php --- */


function lander_attachment_video_header() {
	if ( ! apply_filters( 'lander_attachment_audio_header', true ) ) {
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
		
	<p class="entry-byline">
		<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
		<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
		<meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
		<?php comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); ?>
		<?php edit_post_link(); ?>
	</p><!-- .entry-byline -->
	
			</header><!-- .entry-header -->
	<?php
}

function lander_attachment_video_content() {
	if ( is_attachment() ) {
		?>
		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php
				hybrid_attachment();
				the_content();
				?>
				<div class="attachment-meta">

					<div class="media-info">

						<h3><?php esc_html_e( 'Audio Info', 'lander' ); ?></h3>

						<ul class="media-meta">
							<?php $pre = '<li><span class="prep">%s</span>'; ?>
							<?php
							hybrid_media_meta(
								'length_formatted', array(
									'before' => sprintf( $pre, esc_html__( 'Run Time', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'dimensions', array(
									'before' => sprintf( $pre, esc_html__( 'Dimensions', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_type', array(
									'before' => sprintf( $pre, esc_html__( 'Type', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_name', array(
									'before' => sprintf( $pre, esc_html__( 'Name', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'file_size', array(
									'before' => sprintf( $pre, esc_html__( 'Size', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
							<?php
							hybrid_media_meta(
								'mime_type', array(
									'before' => sprintf( $pre, esc_html__( 'Mime Type', 'lander' ) ),
									'after' => '</li>',
								)
							);
?>
						</ul>

					</div><!-- .media-info -->

				</div><!-- .attachment-meta -->
			<?php
				wp_link_pages();
			?>
		</div><!-- .entry-content -->
		<?php
	} else {
		?>
		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php

			if ( apply_filters( 'lander_excerpts', true ) ) {
				get_the_image();
				the_excerpt();
			} else {
				the_content();
				// wp_link_pages(); // Why do we need post-content pagination on archives?
			}
			?>
		</div><!-- .entry-content -->
		<?php
	}
}


function lander_attachment_video_footer() {
	if ( ! apply_filters( 'lander_attachment_video_footer', is_singular() ) ) {
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'category',
				'text' => esc_html__( 'Posted in %s', 'lander' ),
			)
		);
?>
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'post_tag',
				'text' => esc_html__( 'Tagged %s', 'lander' ),
				'before' => '<br />',
			)
		);
?>
		<br />
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}

/* --- attachment.php --- */

function lander_attachment_header() {
	if ( ! apply_filters( 'lander_attachment_header', true ) ) {
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
		
	<p class="entry-byline">
		<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
		<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
		<meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
		<?php comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); ?>
		<?php edit_post_link(); ?>
	</p><!-- .entry-byline -->
	
			</header><!-- .entry-header -->
	<?php
}

function lander_attachment_content() {
	if ( is_singular() ) {
		?>
		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php
			hybrid_attachment(); // Function for handling non-image attachments.
			the_content();
			wp_link_pages();
			?>
		</div><!-- .entry-content -->
		<?php
	} else {
		?>
		<div <?php hybrid_attr( 'entry-summary' ); ?>>
		
			<?php
			if ( apply_filters( 'lander_excerpts', true ) ) {
				get_the_image();    // Let's output images on excerpts.
				the_excerpt();
			} else {
				the_content();
				// wp_link_pages(); // Why do we need post-content pagination on archives?
			}
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

function lander_attachment_footer() {
	if ( ! apply_filters( 'lander_attachment_footer', is_singular() ) ) {
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'category',
				'text' => esc_html__( 'Posted in %s', 'lander' ),
			)
		);
?>
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'post_tag',
				'text' => esc_html__( 'Tagged %s', 'lander' ),
				'before' => '<br />',
			)
		);
?>
		<br />
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}

/* --- audio.php --- */

function lander_audio_header() {
	if ( ! apply_filters( 'lander_audio_header', true ) ) {
		return;
	}
	?>
	<header class="entry-header">
	<?php

	if ( is_singular() ) {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '>', '</h1>' );
	} else {
		the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' );
	}
	?>
		
	<p class="entry-byline">
		<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
		<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
		<meta itemprop="dateModified" content="<?php echo get_the_modified_date(); ?>" />
		<?php comments_popup_link( 'Comment Now', 'Comment', '% Comments', 'comments-link', '' ); ?>
		<?php edit_post_link(); ?>
		<?php hybrid_post_format_link(); ?>
	</p><!-- .entry-byline -->
	
			</header><!-- .entry-header -->
	<?php
}

function lander_audio_content() {
	if ( is_singular() ) {
		?>
		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php
			echo ( $audio = hybrid_get_post_media(
				array(
					'type' => 'audio',
					'split_media' => true,
				)
			) );
			the_content();
			wp_link_pages();
			?>
		</div><!-- .entry-content -->
		<?php
	} else {
		?>
		<div <?php hybrid_attr( 'entry-summary' ); ?>>
		
			<?php
			if ( apply_filters( 'lander_excerpts', true ) ) {
				echo ( $audio = hybrid_get_post_media(
					array(
						'type' => 'audio',
						'split_media' => true,
					)
				) );
				the_excerpt();
			} else {
				the_content();
				// wp_link_pages(); // Why do we need post-content pagination on archives?
			}
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

function lander_audio_footer() {
	if ( ! apply_filters( 'lander_audio_footer', is_singular() ) ) {
		return;
	}
	?>
	<footer class="entry-footer">
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'category',
				'text' => esc_html__( 'Posted in %s', 'lander' ),
			)
		);
?>
		<?php
		hybrid_post_terms(
			array(
				'taxonomy' => 'post_tag',
				'text' => esc_html__( 'Tagged %s', 'lander' ),
				'before' => '<br />',
			)
		);
?>
		<br />
		<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<?php if ( has_custom_logo() ) : ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo lander_get_custom_logo(); ?>" />    
			</span>
			<?php endif ?>
			<meta itemprop="name" content="<?php echo get_bloginfo( 'name' ); ?>" /> 
		</span>
		<meta itemprop="mainEntityOfPage" itemscope itemtype="<?php echo is_page() ? 'https://schema.org/WebPage' : 'https://schema.org/Blog'; ?>" />
	</footer><!-- .entry-footer -->
	<?php
}

add_action('lander_entry_footer', 'lander_afterentry_sidebar', 11, 2);

function lander_afterentry_sidebar( $type, $format ) {
    if( ! apply_filters( 'show_afterfentry_widgets', true ) ) {
        return;
    }
    if(is_singular()) {
        if ( is_active_sidebar( 'afterentry' ) ) { ?>
            <aside <?php hybrid_attr( 'sidebar', 'afterentry' ); ?>>
                <?php dynamic_sidebar( 'afterentry' ); // Displays the subsidiary sidebar. ?>
            </aside><!-- #sidebar-subsidiary -->
        <?php
        }
    }
}
