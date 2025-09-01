<?php

$page_id = get_the_ID();
$banner_content = get_field('static_content', $page_id);
$banner_title = get_field('titles_in_banner', 'option') ?? null;


// Get override fields
$override_text_colour = $banner_content['override_text_colour'] ?? null;
$override_text_opacity = $banner_content['override_text_opacity'] ?? null;

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

if (!is_front_page()) {
    $title_pt1 = $banner_content['title_pt1'] ?? '';
    $title_pt2 = $banner_content['title_pt2'] ?? '';
}

// Build inline styles
$text_style = '';
if ($override_text_colour) {
    $text_style = 'color:' . esc_attr($override_text_colour) . ';';
}

$span_style = '';
if ($override_text_opacity !== null && $override_text_opacity !== '') {
    $span_style = 'opacity:' . esc_attr($override_text_opacity) . ';';
}

// Display banner image
if ($image_id) {
    $body_classes = get_body_class();

    $hero_classes = 'hero-static__image';
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
    echo '<div class="hero-static__content ' . ($image_id ? '' : 'no-image') . '">';
    echo '<div class="wrap">';
    echo '<h1 class="page-title" style="' . esc_attr($text_style) . '">';
    echo '<span style="' . esc_attr($span_style) . '">' . esc_html($title_pt1 ?: $page_title) . '</span>';
    echo '<span class="sub">' . esc_html($title_pt2) . '</span>';
    echo '</h1>';
    if ($subtitle) {
        echo '<p class="hero-image__subtitle">' . esc_html($subtitle) . '</p>';
    }
    echo '</div></div>';
}

// Display front page banner content
if (is_front_page()) {
    echo '<div class="hero-static__content front">';
    echo '<div class="wrap">';
    echo '<h2 class="hero-image__title" style="' . esc_attr($text_style) . '">' . esc_html($title) . '</h2>';
    if ($subtitle) {
        echo '<p class="hero-image__subtitle">' . esc_html($subtitle) . '</p>';
    }
    echo '</div></div>';
}
