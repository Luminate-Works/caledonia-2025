<?php

$page_id = get_the_ID();
$banner_content = get_field('static_content', $page_id);
$banner_title = get_field('titles_in_banner', 'option') ?? null;


// Determine page title based on context
$page_title = match (true) {
    is_404() => 'Page Not Found',
    is_search() => 'Search Results for: ' . get_search_query(),
    is_archive() => get_the_archive_title(),
    is_singular('team') => 'Team',
    default => get_the_title($page_id),
};

// Determine banner image data
$image_data = match (true) {
    is_singular('post') => has_post_thumbnail()
        ? ['image' => get_post_thumbnail_id($page_id)]
        : get_field('search_banner_styles', 'option'),
    is_singular('team') => get_field('default_banner_styles', 'option'),
    !empty($banner_content['image']) => $banner_content,
    default => get_field('default_banner_styles', 'option')
};

// Fallback image if none found
$image_id = $image_data['image'] ?? get_field('default_banner_styles', 'option')['image'] ?? null;

$custom_css = $banner_content['banner_custom_css'] ?? null;
$title = $banner_content['title'] ?? '';
$subtitle = $banner_content['subtitle'] ?? '';

$reading_time = get_field('est_reading_time');

function custom_post_type_label( $post_id = null ) {
    $post_id   = $post_id ?: get_the_ID();
    $post_type = get_post_type( $post_id );

    if ( $post_type === 'post' ) {
        return 'NEWS';
    }

    $obj = get_post_type_object( $post_type );
    return $obj ? $obj->labels->singular_name : '';
}


// Display banner image 
if ($image_id) {
    $body_classes = get_body_class();

    $hero_classes = 'hero-single__image';
    if (in_array('page-child', $body_classes)) {
        $hero_classes .= ' is-child';
    }

    echo '<div class="' . esc_attr($hero_classes) . '">';

    if (in_array('page-child', $body_classes)) {
        echo '<div class="wrap">';
    }

    echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']);

    if (in_array('page-child', $body_classes)) {
        echo '</div>';
    }

    echo '</div>';
}

// Display banner content
if ($banner_title && !is_front_page()) {
    echo '<div class="has-global-padding hero-single__content ' . ($image_id ? '' : 'no-image') . '">';
    echo '<div class="wrap">';

    echo '<div class="meta-fields">';
    echo '<p class="category">'. custom_post_type_label() .'</p>';
    echo '<p aria-label="Published date: ' . get_the_date('F Y') . '">' . get_the_date('F Y') . '</p>';
    echo '<p class="meta">Reading time: ' . $reading_time . ' mins</p>';
    echo '</div>';

    echo '<h1 class="page-title">';
    echo '<span>' . esc_html($page_title) . '</span>';
    echo '</h1>';

    if ($subtitle) {
        echo '<p class="hero-image__subtitle">' . esc_html($subtitle) . '</p>';
    }

    echo '</div></div>';
}
