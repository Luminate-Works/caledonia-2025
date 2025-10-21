<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'lmn-logo-grid'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
$cleanedClassName = trim(str_replace('lmn-logo-grid', '', $className));

$images = get_field('logo_grid');
$size = 'full';

?>

<div class="<?= esc_attr($className); ?>">
    <?php
    if ($images): ?>
        <ul>
            <?php foreach ($images as $image_id): ?>
                <li>
                    <a href="<?php echo get_post_meta($image_id, '_custom_url', true); ?>" target="_blank">
                        <?php echo wp_get_attachment_image($image_id, $size); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>