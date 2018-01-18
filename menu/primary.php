<?php


add_action('lander_top_menu_primary_markup', 'lander_layout_wrap_open');
add_action('lander_bottom_menu_primary_markup', 'lander_layout_wrap_close');

lander_do_nav('primary');