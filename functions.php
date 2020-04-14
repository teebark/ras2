<?php 
// JS for responsive tables
/**
 * Enqueue a script
 */
function myprefix_enqueue_scripts() {
	wp_register_script('table-resp', get_stylesheet_directory_uri() . '/js/table.js', array() );
    wp_enqueue_script( 'table-resp', get_stylesheet_directory_uri() . '/js/table.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'myprefix_enqueue_scripts' );
?>