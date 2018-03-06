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

@media screen and ( min-width: 48em ) {   /* single col wide space is now available. 3em + 42em + 3em = 48em = 768px i.e. 16px * 48em */
    .site-container {
        margin: 1.618em auto;
    }
    
    .wrap {
        margin: auto;
        max-width: 48em; 
        padding: calc(1.618em / 2) 3em;
        /* 48em - 6em = 42em = 672px; ideal for 16px font size at golden ratio typography */
    }

    .content {
            max-width: 42em;
            /* 16 * 42em = 672px */
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

/* When using 3 columns, we'll use smaller sidebars. Thus a smaller breakpoint (66.75em) */
@media screen and ( min-width: 66.75em ) { /* Space is now available for primary sidebar. calc ( 3em + 42em + 3em + 15.75em + 3em ) */

    .layout-3c-l .inner .wrap,
    .layout-3c-c .inner .wrap,
    .layout-3c-r .inner .wrap {
        display: flex;
        flex-flow: row wrap;
        justify-content: space-between;
    }
    
    .layout-3c-l .wrap,
    .layout-3c-c .wrap,
    .layout-3c-r .wrap {
        width: 66.75em;
        max-width: 66.75em;
    }

    .layout-3c-l .sidebar-primary,
    .layout-3c-c .sidebar-primary,
    .layout-3c-r .sidebar-primary {
        font-size: 0.875em; /* 14px */
        line-height: 1.5em; /* force wrt the new font-size */
        width: 18em; /* 14px * 18em = 252. Point of confusion: 15.75 at 16px is still 252px and that's how the breakpoint is calculated */
        max-width: 18em; /* 14px * 18em = 252. Point of confusion: 15.75 at 16px is still 252px and that's how the breakpoint is calculated */
    }

    .layout-3c-l .sidebar-secondary,
    .layout-3c-c .sidebar-secondary,
    .layout-3c-r .sidebar-secondary {
        width: 100%;
    }

    .layout-3c-c .content {
        order: 1;
    }
    
    .layout-3c-c .sidebar-primary {
        order: 0;
    }
    
    .layout-3c-c .sidebar-secondary {
        order: 2;
    }
    
    /* At 2 col break point, keep the alt sidebar after everything */
    .layout-3c-r .content {
        order: 1;
    }
    
    .layout-3c-r .sidebar-primary {
        order: 0;
    }

    .layout-3c-r .sidebar-secondary {
        order: 2;
    }
    
}

@media screen and ( min-width: 72em ) { /* Two column wide space is now available. 3em + 42em + 3em + 21em + 3em = 72em = 1152px i.e. 72em * 16px */
    
    /* Now all we got to worry about is to increase the wrap width so that sidebar can fall in place */
    .layout-2c-l .wrap,
    .layout-2c-r .wrap {
        width: 72em;
        max-width: 72em;
    }
    
    .layout-2c-r .inner .wrap,
    .layout-2c-l .inner .wrap {
        display: flex;
        flex-flow: row wrap;
        justify-content: space-between;
    }

    .layout-2c-r .sidebar-primary,
    .layout-2c-l .sidebar-primary {
        font-size: 0.875em; /* 14px */
        line-height: 1.5em; /* force wrt the new font-size */
        width: 24em; /* 14px * 24em = 336. Point of confusion: 21em at 16px is still 336px and that's how the breakpoint is calculated */
        max-width: 24em; /* 14px * 24em = 336. Point of confusion: 21em at 16px is still 336px and that's how the breakpoint is calculated */
    }

    .layout-2c-r .content {
        order: 1;
    }

    .layout-2c-r .sidebar-primary {
        order: 0;
    }

}

@media screen and ( min-width: 85.5em ) { /* Space is now available for secondary sidebar. calc ( 3em + 42em + 3em + 15.75em + 3em + 15.75em + 3em ) */

    .layout-3c-l .wrap,
    .layout-3c-c .wrap,
    .layout-3c-r .wrap {
        width: 85.5em;
        max-width: 85.5em;
    }

    .layout-3c-l .sidebar-secondary,
    .layout-3c-c .sidebar-secondary,
    .layout-3c-r .sidebar-secondary {
        font-size: 0.875em; /* 14px */
        line-height: 1.5em; /* force wrt the new font-size */
        width: 18em; /* 14px * 18em = 252. Point of confusion: 15.75 at 16px is still 252px and that's how the breakpoint is calculated */
        max-width: 18em; /* 14px * 18em = 252. Point of confusion: 15.75 at 16px is still 252px and that's how the breakpoint is calculated */
    }

    .layout-3c-r .content {
        order: 2;
    }
    
    .layout-3c-r .sidebar-primary {
        order: 0;
    }

    .layout-3c-r .sidebar-secondary {
        order: 1;
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