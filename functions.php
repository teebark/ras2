<?php
/* Disable secondary navigation on mobile */
add_action( 'wp_enqueue_scripts', 'generate_dequeue_secondary_nav_mobile', 999 );
function generate_dequeue_secondary_nav_mobile() {
    wp_dequeue_style( 'generate-secondary-nav-mobile' );
}
?>