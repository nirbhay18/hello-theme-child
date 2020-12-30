<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects( $items, $args ) {
	
	// loop
	foreach( $items as &$item ) {
		
		// Get ACF fields
		$country_icon = get_field('country_flag_icon', $item);
		$functionality_icon = get_field('functionality_icons', $item);
		$badge = get_field('menu_item_badge', $item);

		// Add simple badge for menu items

		if( ! empty( $badge ) ) {
			if( empty( $functionality_icon ) ) {
				$badge = ! empty( $badge ) ? 'data-badge="' . $badge . '"' : '';
				$item->title .= '<span class="item-badge"' . $badge . '"></span>';
			}
		}
		
		
		// Add country flag icon

		if( $country_icon ) {

			$item_title = $item->title;
			
			$item->title = ' <img class="country-menu-icons" src="' . $country_icon['url'] . '"> ';

			$item->title .= $item_title;
			
		} elseif ( $functionality_icon ) {

			// Add other functioanlity related icons
			
			$item_title = '<span class="menu-title">' . $item->title;
			
			$item->title = ' <img class="functionality-menu-icons" src="' . $functionality_icon['url'] . '"></span>';

			$item->title .= $item_title;
			
		}

		// Add item description

		if( ! empty( $item->description ) ) {

			$badge = ! empty( $badge ) ? 'data-badge="' . $badge . '"' : '';

			$item->title .= '<small class="description" '. $badge .'">' . $item->description . '</small>';

		} 
		
		// Add badge to functionality icon fields

		if ( $badge ) {

			$badge = ! empty( $badge ) ? 'data-badge="' . $badge . '"' : '';

			$item_title = '<span class="menu-title" data-badge="' . $badge . '">' . $item->title . '</span>';

		}
		
	}

	// return
	return $items;
	
}

add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {

	// Check if menu item has grid layout enabled or not.

	$is_grid = get_field( 'enable_grid', $item );

	if(  $is_grid  ){

		// Get column numbers.
		$columns = get_field('number_of_columns', $item );
		
		// Set CSS class if column number is greater than or equal to 2.
		$col_class = $columns >= 2 ? 'submenu-grid-col-'.$columns : '';

		// Add classes to the array
		$atts['class'] = $col_class;
	}

	return $atts;
	
}, 10, 4 );

if ( ! function_exists('custom_post_type') ) {

	// Register Custom Post Type
	function custom_post_type() {
	
		$labels = array(
			'name'                  => _x( 'Use Cases', 'Post Type General Name', 'exotel' ),
			'singular_name'         => _x( 'Use Case', 'Post Type Singular Name', 'exotel' ),
			'menu_name'             => __( 'Use Cases', 'exotel' ),
			'name_admin_bar'        => __( 'Use Case', 'exotel' ),
			'archives'              => __( 'Use Case Archives', 'exotel' ),
			'attributes'            => __( 'Use Case Attributes', 'exotel' ),
			'parent_item_colon'     => __( 'Parent Item:', 'exotel' ),
			'all_items'             => __( 'All Use Cases', 'exotel' ),
			'add_new_item'          => __( 'Add New Use Case', 'exotel' ),
			'add_new'               => __( 'Add New Use Case', 'exotel' ),
			'new_item'              => __( 'New Use Case', 'exotel' ),
			'edit_item'             => __( 'Edit Use Case', 'exotel' ),
			'update_item'           => __( 'Update Use Case', 'exotel' ),
			'view_item'             => __( 'View Use Case', 'exotel' ),
			'view_items'            => __( 'View Use Cases', 'exotel' ),
			'search_items'          => __( 'Search Use Case', 'exotel' ),
			'not_found'             => __( 'Not found', 'exotel' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'exotel' ),
			'featured_image'        => __( 'Featured Image', 'exotel' ),
			'set_featured_image'    => __( 'Set featured image', 'exotel' ),
			'remove_featured_image' => __( 'Remove featured image', 'exotel' ),
			'use_featured_image'    => __( 'Use as featured image', 'exotel' ),
			'insert_into_item'      => __( 'Insert into use case', 'exotel' ),
			'uploaded_to_this_item' => __( 'Uploaded to this use case', 'exotel' ),
			'items_list'            => __( 'Use Cases list', 'exotel' ),
			'items_list_navigation' => __( 'Use Cases list navigation', 'exotel' ),
			'filter_items_list'     => __( 'Filter use cases list', 'exotel' ),
		);
		$args = array(
			'label'                 => __( 'Use Case', 'exotel' ),
			'description'           => __( 'Use Cases', 'exotel' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields', 'page-attributes' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_rest'			=> true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'use-cases', $args );
	
	}
	add_action( 'init', 'custom_post_type', 0 );
	
}