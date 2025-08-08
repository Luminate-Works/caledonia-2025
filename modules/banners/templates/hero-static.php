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
    is_singular('post') => 'News & Insights',
    default => get_the_title($page_id),
};

// Determine banner image data
$image_data = match (true) {
    is_404() => get_field('fourofour_banner_styles', 'option'),
    is_search() => get_field('search_banner_styles', 'option'),
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

// Display banner image
if ($image_id) {
    echo '<div class="hero-static__image">';
    echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']);
    echo '</div>';
}

// Display banner content
if ($banner_title && !is_front_page()) {
    echo '<div class="hero-static__content">';
    echo '<div class="wrap">';
    echo '<h1 class="page-title">' . esc_html($title ?: $page_title) . '</h1>';
    if ($subtitle) {
        echo '<p class="hero-image__subtitle">' . esc_html($subtitle) . '</p>';
    }
    echo '</div></div>';
}

// Display front page banner content
if (is_front_page()) {
    echo '<div class="hero-static__content front">';
    echo '<div class="wrap">';
    echo '<h2 class="hero-image__title">' . esc_html($title) . '</h2>';
    if ($subtitle) {
        echo '<p class="hero-image__subtitle">' . esc_html($subtitle) . '</p>';
    }
    echo '</div></div>';
}