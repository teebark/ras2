<?php
// Add External Link to Featured Image with Custom Field
add_filter('post_thumbnail_html','add_external_link_on_page_post_thumbnail',10);
    function add_external_link_on_page_post_thumbnail( $html ) {
    if( is_singular() ) {
            global $post;
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size='full', false );
			$src = get_the_post_thumbnail_url($post,'full');
            $name = get_post_meta($post->ID, 'ExternalUrl', true);
            if( $name ) {
                    $html = '<a href="' . ( $src ) . '" target="_blank" >' . $html . '</a>';
            }
    }
    return $html;
    }
// Make image size on post page = medium large
add_filter( 'generate_page_header_default_size', 'generate_custom_default_page_header_size' );
function generate_custom_default_page_header_size()
{
      return 'medium_large';
}
?>