<?php

// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'image-cta' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$image = get_field('featured_image');
$title = get_field('title');
$subtitle = get_field('subtitle');
$link = get_field('link');
$link_url = $link['url'] ?? '';
$link_target = !empty($link['target']) && $link['target'] === '_blank' ? '_blank' : '_self';

$wrapper_tag = $link ? 'a' : 'div';
$wrapper_attributes = $link 
    ? sprintf('class="%s" href="%s" target="%s"', esc_attr($className), esc_url($link_url), esc_attr($link_target))
    : sprintf('class="%s"', esc_attr($className));
?>

<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>
    <div class="image-cta__image" style="background-image: url(<?= esc_url($image) ?>);"></div>
    
    <div class="image-cta__content">
        <div class="inner">
            <h2><?= esc_html($title) ?></h2>
            <?php echo $subtitle; ?>
            <?= $subtitle ?>
        </div>

        <?php if ($link): ?>
            <span class="btn">
                <?= esc_html($link['title'] ?? 'Read more') ?>
            </span>
        <?php endif; ?>
    </div>
</<?= $wrapper_tag ?>>