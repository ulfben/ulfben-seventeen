<?php
/**
 * Substitutes SVG icon menu code from twentyseventeen
 * Since 1.0.0
 *
 * @package WordPress
 */
/**
 * Remove the filter from twentyseventeen_nav_menu_social_icons.
 *
 * @since  1.0.0
 * @return void
 */
function remove_filter_twentyseventeen_nav_menu_social_icons() {
	remove_filter( 'walker_nav_menu_start_el', 'twentyseventeen_nav_menu_social_icons', 10, 4 );
}
add_action( 'after_setup_theme' , 'remove_filter_twentyseventeen_nav_menu_social_icons' );
/**
 * Display SVG icons in social links menu.
 *
 * @param  string  $item_output The menu item output.
 * @param  WP_Post $item        Menu item object.
 * @param  int     $depth       Depth of the menu.
 * @param  array   $args        wp_nav_menu() arguments.
 * @return string  $item_output The menu item output with social icon.
 */
function twentyseventeen_child_nav_menu_social_icons($item_output, $item, $depth, $args ) {
	// Get supported social icons.
	$social_icons = twentyseventeen_social_links_icons();
	// See if a menu has the name Social Media Menu.
	$menu_object = wp_get_nav_menu_object( 'social-links-menu' );
	// Make sure these don't match to start with.
	$social_menu_id = -1;
	$cur_menu = 0;
	if ( $menu_object ) {
		$social_menu_id = $menu_object->term_id;
		if ( is_object( $args->menu ) ) {
			$cur_obj = $args->menu;
			$cur_menu = $cur_obj->term_id;
		} else {
			$cur_menu = $args->menu;
		}
	}
	// Change SVG icon inside social links menu if there is supported URL.
	if ( 'social' === $args->theme_location ) {
		foreach ( $social_icons as $attr => $value ) {
			if ( false !== strpos( $item_output, $attr ) ) {
				$item_output = str_replace( $args->link_after, '</span>' . twentyseventeen_get_svg( array( 'icon' => esc_attr( $value ) ) ), $item_output );
			}
		}
	} else {
		// Check if the social-menu-icon is being used elsewhere and change SVG icon inside social links menu if there is supported URL.
		if ( $social_menu_id === $cur_menu ) {
			$item_output = str_replace( '">', '"><span class="screen-reader-text">' , $item_output );
			$item_output = str_replace( '</a>', '</span></a>' , $item_output );
			foreach ( $social_icons as $attr => $value ) {
				if ( false !== strpos( $item_output, $attr ) ) {
					$item_output = str_replace( '</span>', '</span>' . twentyseventeen_get_svg( array( 'icon' => esc_attr( $value ) ) ), $item_output );
				}
			}
		}
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'twentyseventeen_child_nav_menu_social_icons', 10, 4 );
?>