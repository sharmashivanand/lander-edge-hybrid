<?php


add_action('lander_head','lander_build_head');
//add_action('lander_header','lander_header_open', 5);
add_action('lander_header','lander_header');
//add_action('lander_header','lander_header_close', 15);
add_action('lander_top_header_markup','lander_layout_wrap_open');
add_action('lander_bottom_header_markup','lander_layout_wrap_close');

/**
 * Build <head />
 *
 * @return void
 */

function lander_build_head(){
    ?><!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>
<head <?php hybrid_attr( 'head' ); ?>>
<?php wp_head(); ?>
</head><?php
}


/**
 * Open .site-header
 *
 * @return void
 */

function lander_header() {

    lander_markup(
        array(
            'open' => '<header %s>',
            'slug' => 'header'
        )
    );


    lander_markup(
        array(
            'open' => '<div %s>',
            'slug' => 'branding'
        )
    );
   
    hybrid_site_title();
	hybrid_site_description();
   
    lander_markup(
        array(
            'close' => '</div>',
            'slug' => 'branding'
        )
    );

   
    lander_markup(
        array(
		    'close'   => '</header>',
	    	'slug' => 'header'
        ) 
    );

}

add_action('wp_head' , 'lander_layout_css');
function lander_layout_css() {
    ?>
<style type="text/css">
/*
.wrap {
    background-color: #ff0;
}
.content {
    background-color: white;
}
.sidebar {
    background-color: pink;
}
*/


@media screen and (min-width: 48em) {   /* 16px * 48em = 768px */
    .site-container {
        margin: 1.618em auto;
    }
    
    .wrap {
        margin: auto;
        max-width: 48em; 
        padding: calc(1.618em / 2) 3em;
        /* 48em - 6em = 40em = 672px */
    }

    .site-header .wrap {
        padding-top: 3em;
    }
   
    .site-footer .wrap {
        padding-bottom: 3em;
    }

    .sidebar-primary {
    }
}

/*
width is inclusive of padding
required break point : padding + content-width + padding + sidebar-width + padding
Given that we want to retain golden-ratio typography,
    content
        font-size: 16px
        line-height: 1.618
        => width ~= 670.188544px  ~= 672px (42em)

Since sidebar is not the main content and it's not ideal to even give a golden-ratio width, let's keep it at rule-of-thirds

    This translates into a width of px
    = 3em + 42em + 3em + 26em + 3em
    = 77em = 
*/

@media screen and ( min-width: 72em ) { /* 3em + 42em + 3em + 21em + 3em = 1152px */
    .layout-2c-l .wrap {
        max-width: 72em; /* 16 * 72 = 1152px */
        /*
        since we have 9em padding, available width for content + padding + sb:
            = 72em - 9em = 63em = 1008px 
        */
    }
    
    .layout-2c-l .inner .wrap {
        display: flex;
        flex-flow: row wrap;
        justify-content: space-between;
    }

    .layout-2c-l .content {
        max-width: 42em;
        /* 16 * 42em = 672px */
    }

    /* Width available for sidebar 416px ( 26em ) = 416px */
    .layout-2c-l .sidebar-primary {
        font-size: 0.875em; /* 14px */
        line-height: 1.5em; /* force wrt the new font-size */
        max-width: 24em; /* 14px * 24em = 336 */
    }
}
</style>
    <?php
}

/*
.layout-1c {
    @media screen and (min-width: $bp1c) {
        .wrap {
            margin: auto; // width: $bp1;
            max-width: $bp1c;
        }
    }
}

.inner .wrap {
    // display: flex;
    // flex-flow: row wrap;
    // justify-content: space-between;
}

.content {
    // width: $content-width;
}

// 2 col layout

.layout-2c-l {
    @media screen and (min-width: $bp2c) {
        .header, .footer {
            text-align: left;
        }
        .wrap {
            margin: auto;
            width: $bp2c;
        }
        .inner .wrap {
            display: flex;
            flex-flow: row wrap;
            justify-content: space-between;
        }
        .content {
            width: $content-width-2c;
        }
        .sidebar-primary {
            width: $sb-width;
        }
    }
}
*/