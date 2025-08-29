<?php

$video_url = get_field('video_url');

// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'large-cta' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '') .
    (!empty($video_url) ? ' has-video' : '');

// Get ACF fields
$image = get_field('featured_image');
$title = get_field('title');
$subtitle = get_field('subtitle');
$link = get_field('link');
$link_url = $link['url'] ?? '';
$link_target = !empty($link['target']) && $link['target'] === '_blank' ? '_blank' : '_self';
$override_overlay = get_field('override_overlay');

if ($video_url) {
    $video_info = get_video_info($video_url);
    $video_type = $video_info['video_type'];
    $video_src = $video_info['video_src'];
}

$wrapper_tag = 'div';
$wrapper_attributes = $link
    ? sprintf('class="%s" href="%s" target="%s"', esc_attr($className), esc_url($link_url), esc_attr($link_target))
    : sprintf('class="%s"', esc_attr($className));
?>

<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>
    <?php
    $style = "background-image: url('" . esc_url($image) . "');";
    if ($override_overlay) {
        $style = "--after-bg: " . esc_attr($override_overlay) . "; " . $style;
    }
    ?>

    <?php if ($video_url): ?>
        <div class="large-cta__video-wrapper">
            <div class="video-container">
                <iframe title="banner" class="video-player" data-type="<?php echo $video_type; ?>" src="<?php echo $video_src; ?>" allowfullscreen></iframe>
            </div>
            <div class="video-overlay" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
        </div>
    <?php else: ?>
        <div class="large-cta__image" style="<?php echo $style; ?>"></div>
    <?php endif; ?>
    <div class="wrap">
        <div class="large-cta__heading">
            <h2 class="wp-block-heading is-style-large-heading"><?php echo wp_kses($title, array('em' => array())); ?></h2>
        </div>

        <div class="large-cta__content">
            <div class="inner">
                <p><?= $subtitle ?></p>
            </div>

            <?php if ($link): ?>
                <div class="wp-block-button is-style-bg-white">
                    <a href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target); ?>">
                        <span class="wp-block-button__link">
                            <?= esc_html($link['title'] ?? 'Read More') ?>
                        </span>
                    </a>


                </div>
            <?php endif; ?>
        </div>
    </div>
</<?= $wrapper_tag ?>>