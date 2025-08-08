<?php

// ------------------------------------------
// ADD EXTERNAL LINK METABOX
// ------------------------------------------
function add_external_link_meta_field() {
    add_meta_box(
        'external_link_meta_box',
        'External Link',
        'external_link_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_external_link_meta_field' );

function external_link_meta_box_callback( $post ) {
    $external_link = get_post_meta( $post->ID, 'external_link', true );
    echo '<label for="external_link" class="screen-reader-text">External Link: </label>';
    echo '<input type="text" id="external_link" name="external_link" value="' . esc_url( $external_link ) . '" size="100" />';
}

function save_external_link_meta_field( $post_id ) {
    if ( array_key_exists( 'external_link', $_POST ) ) {
        update_post_meta(
            $post_id,
            'external_link',
            esc_url_raw( $_POST['external_link'] )
        );
    }
}
add_action( 'save_post', 'save_external_link_meta_field' );


// ---------------------------
// ajax load more posts
// ---------------------------
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
function load_more_posts() {
    $paged = intval($_POST['page']);
    $posts_per_page = intval($_POST['posts_per_page']);
    $post_type = $_POST['post_type'];
    $category = sanitize_text_field($_POST['category']); // Get and sanitize the category

    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'offset' => ($paged - 1) * $posts_per_page,
        'posts_per_page' => $posts_per_page,
        'category_name' => $category, // Include category in the query arguments
    );

    $the_query = new WP_Query($args);
    $output = '';
    if ($the_query->have_posts()) : 
        while ($the_query->have_posts()) : $the_query->the_post();
            ob_start(); 
            get_template_part('parts/part', 'excerpt');
            $output .= ob_get_clean();
        endwhile;
    endif;

    $data = array(
        'posts' => $output,
        'has_more' => $the_query->max_num_pages >= $paged + 1
    );

    echo json_encode($data);
    wp_die();
}