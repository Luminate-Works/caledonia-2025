<?php

// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'large-cta' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$image = get_field('featured_image');
$title = get_field('title');
$subtitle = get_field('subtitle');
$link = get_field('link');
$link_url = $link['url'] ?? '';
$link_target = !empty($link['target']) && $link['target'] === '_blank' ? '_blank' : '_self';

$wrapper_tag = 'div';
$wrapper_attributes = $link
    ? sprintf('class="%s" href="%s" target="%s"', esc_attr($className), esc_url($link_url), esc_attr($link_target))
    : sprintf('class="%s"', esc_attr($className));
?>

<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>
    <div class="large-cta__image" style="background-image: url(<?= esc_url($image) ?>);"></div>

    <div class="wrap">
        <div class="large-cta__heading">
            <h2 class="wp-block-heading is-style-large-heading"><?php echo wp_kses($title, array('em' => array())); ?></h2>
        </div>

        <div class="large-cta__content">
            <div class="inner">
                <p><?= $subtitle ?></p>
            </div>

            <?php if ($link): ?>
                <span class="btn">
                    <?= esc_html($link['title'] ?? 'Read More') ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</<?= $wrapper_tag ?>>