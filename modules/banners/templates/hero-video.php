<?php

$page_id = get_the_ID();
$banner_content = get_field('video_banner', $page_id);
$banner_title = get_field('titles_in_banner', 'option') ?? null;

// Determine page title
$page_title = is_404() ? 'Page Not Found' : (is_search() ? 'Search Results for: ' . get_search_query() : (is_archive() ? get_the_archive_title() : get_the_title($page_id)));

// Determine banner image
if (is_404()) {
    $image_data = get_field('fourofour_banner_styles', 'option');
} elseif (is_search()) {
    $image_data = get_field('search_banner_styles', 'option');
} elseif (isset($banner_content['image'])) {
    $image_data = $banner_content;
} else {
    $image_data = get_field('default_banner_styles', 'option');
}

$image_id = $image_data['image'] ?? null;
if (!$image_id) {
    $default_image_data = get_field('default_banner_styles', 'option');
    $image_id = $default_image_data['image'] ?? null;
}

$banner_type = $banner_content['video_type'] ?? '';

if ($banner_type === 'external') {
    $video_url = $banner_content['video_link'] ?? '';
    $video_info = get_video_info($video_url);
    $video_type = $video_info['video_type'];
    $video_src = $video_info['video_src'];
}

if ($banner_type === 'hosted') {
    $video_url = $banner_content['video_file'] ?? '';
}

$title = $banner_content['title'] ?? '';
$subtitle = $banner_content['subtitle'] ?? '';

if (is_front_page()) {
    $title_pt1 = $banner_content['title_pt1'] ?? '';
    $title_pt2 = $banner_content['title_pt2'] ?? '';
    $title_pt3 = $banner_content['title_pt3'] ?? '';
}

?>

<?php if ($image_id): ?>
    <div class="hero-video__image">

        <?php if ($banner_type == 'external'): ?>

            <?= wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']); ?>
            <iframe title="banner" class="video-player" data-type="<?php echo $video_type; ?>" src="<?php echo $video_src; ?>" allowfullscreen></iframe>

        <?php elseif ($banner_type == 'hosted') : ?>
            <?= wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']); ?>

            <video class="video-player" data-type="hosted" autoplay muted loop playsinline aria-hidden="true">
                <source src="<?= $video_url; ?>" type="video/mp4">
            </video>

        <?php endif; ?>

    </div>
<?php endif; ?>

<?php if ($banner_title && !is_front_page()) : ?>
    <div class="hero-video__content">
        <div class="wrap">
            <h1 class="page-title"><?= esc_html($page_title); ?></h1>
            <?php if ($subtitle) : ?>
                <p class="hero-image__subtitle"><?= esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (is_front_page()) : ?>
    <div class="hero-video__content front">
        <div class="wrap">
            <h2 class="hero-image__title">
                <?php if ($title_pt1) : ?><span><?= esc_html($title_pt1); ?></span><?php endif; ?>
                <?php if ($title_pt2) : ?><span><?= esc_html($title_pt2); ?></span><?php endif; ?>
                <?php if ($title_pt3) : ?><span><?= esc_html($title_pt3); ?></span><?php endif; ?>
            </h2>
            <?php if ($subtitle) : ?>
                <p class="hero-image__subtitle"><?= esc_html($subtitle); ?></p>
            <?php endif; ?>

        </div>
    </div>
<?php endif; ?>