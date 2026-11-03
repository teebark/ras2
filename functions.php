<?php 
/* Adds page name to classes for page */
add_filter('body_class','page_class');
function page_class($classes) {
   global $wp_query;
   $page = '';
   $page = $wp_query->query_vars['pagename'];
   // add 'pagename' to the $classes array
   $classes[] = $page;
   // return the $classes array
   return $classes;
}
// add_filter( 'wp_nav_menu_objects', 'my_wp_nav_menu_objects_sub_menu', 10, 2 );

// JS for responsive tables
/**
 * Enqueue a script
 */
function myprefix_enqueue_scripts() {
	wp_register_script('table-resp', get_stylesheet_directory_uri() . '/js/table.js', array() );
    wp_enqueue_script( 'table-resp', get_stylesheet_directory_uri() . '/js/table.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'myprefix_enqueue_scripts' );
// Fix excerpt
add_filter( 'excerpt_allowed_wrapper_blocks', function( $allowed ) {
    $allowed[] = 'generateblocks/container';
    $allowed[] = 'generateblocks/grid';
    return $allowed;
} );

// Sort and only show current and future dates
add_filter( 'generateblocks_query_wp_query_args', function( $args, $attributes ) {
    $meta_key = 'ras-date';
    $today = date( 'Ymd' );
    if ( empty( $attributes['className'] ) ) {
        return $args;
    }
    $class_name = $attributes['className'];
    
    // TOEKOMSTIG: >= vandaag (alleen gepubliceerd)
    if ( strpos( $class_name, 'order-by-date' ) !== false ) {
        $args['post_type'] = 'ras-venue';
        $args['post_status'] = 'publish';  // ← DIT!
        $args['meta_key'] = $meta_key;
        $args['orderby'] = 'meta_value';
        $args['order'] = 'ASC';
        $args['meta_query'] = array(
            array(
                'key'     => $meta_key,
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        );
    }
    // GESPEELD: < vandaag (alleen gepubliceerd)
    elseif ( strpos( $class_name, 'order-by-date' ) !== false ) {
        $args['post_type'] = 'ras-venue';
        $args['post_status'] = 'publish';  // ← DIT!
        $args['meta_key'] = $meta_key;
        $args['orderby'] = 'meta_value';
        $args['order'] = 'DESC';  // Nieuwste gespeeld bovenaan
        $args['meta_query'] = array(
            array(
                'key'     => $meta_key,
                'value'   => $today,
                'compare' => '<',
                'type'    => 'DATE',
            ),
        );
    }

    return $args;
}, 10, 2 );
// Format date
add_filter( 'generateblocks_dynamic_tag_output', function( $output, $options ) {
	if ( empty( $options['customdateformat'] ) || ! is_string( $output ) || '' === $output ) {
		return $output;
	}

	$date = DateTime::createFromFormat( 'Ymd', $output );

	if ( ! $date ) {
		$timestamp = strtotime( $output );

		if ( ! $timestamp ) {
			return $output;
		}

		$date = ( new DateTime() )->setTimestamp( $timestamp );
	}

//	return $date->format( 'n/j/Y' );
	return $date->format( 'j M' );
}, 10, 2 );
// Fix column names
add_filter('manage_ras-venue_posts_columns', 'my_custom_columns');
function my_custom_columns($columns) {
//    $columns['ras-date'] = __('Venue Date'),
	$columns = array (
	  'cb' => $columns['cb'],
	  'title' => __('Title'),
	  'ras-date' => __('Venue Date'),
    );
    return $columns;
}
add_action('manage_ras-venue_posts_custom_column', 'my_custom_column_content', 10, 2);
function my_custom_column_content($column, $post_id) {
    if ($column === 'ras-date') {
        echo get_post_meta($post_id,'ras-date', true);
    }
}
add_filter('manage_ras-venue_sortable_columns', 'my_sortable_columns');
function my_sortable_columns($columns) {
    $columns['ras-date'] = 'Venue Date';
    return $columns;
}
// Sort and only show current and future dates
add_filter( 'generateblocks_query_loop_args', function( $query_args, $attributes ) {
    $meta_key = 'ras-date';
    $today = date( 'Ymd' );

    if ( empty( $attributes['className'] ) ) {
        return $query_args;
    }

    $class_name = $attributes['className'];

    // TOEKOMSTIG: >= vandaag (alleen gepubliceerd)
    if ( strpos( $class_name, 'order-by-date' ) !== false ) {
        $query_args['post_type'] = 'ras-venue';
        $query_args['post_status'] = 'publish';  // ← DIT!
        $query_args['meta_key'] = $meta_key;
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'ASC';
        $query_args['meta_query'] = array(
            array(
                'key'     => $meta_key,
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        );
    }
    // GESPEELD: < vandaag (alleen gepubliceerd)
    elseif ( strpos( $class_name, 'order-by-date' ) !== false ) {
        $query_args['post_type'] = 'ras-venue';
        $query_args['post_status'] = 'publish';  // ← DIT!
        $query_args['meta_key'] = $meta_key;
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'DESC';  // Nieuwste gespeeld bovenaan
        $query_args['meta_query'] = array(
            array(
                'key'     => $meta_key,
                'value'   => $today,
                'compare' => '<',
                'type'    => 'DATE',
            ),
        );
    }

    return $query_args;
}, 10, 2 );
// Format date
add_filter( 'generateblocks_dynamic_tag_output', function( $output, $options ) {
    if ( empty( $options['customdateformat'] ) ) {
        return $output;
    }

    $date_value = get_field('ras-date'); // Replace with your actual ACF date field name

    if ( $date_value ) {
        // Since the date is returned as "Y/m/d", adjust the createFromFormat accordingly.
        $formatted_date = DateTime::createFromFormat('Y/m/d', $date_value);
        if ( $formatted_date ) {
            $output = $formatted_date->format('n/j/Y'); // Example: "22. February 2025"
        }
    }

    return $output;
}, 10, 2 );
?>