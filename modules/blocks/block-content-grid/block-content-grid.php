<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'lmn-content-grid'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
$cleanedClassName = trim(str_replace('lmn-content-grid', '', $className));

$content_grid = get_field('content_grid');

?>

<div id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">
    <?php if ($content_grid): ?>
        <div class="content-grid-wrapper">
            <?php foreach ($content_grid as $item): ?>
                <?php
                $image = $item['image'];
                $overlay_logo = $item['overlay_logo'];
                $title = $item['title'];
                $content = $item['content'];
                $link = $item['link'];
                ?>

                <div class="content-grid-item">
                    <?php if ($image): ?>
                        <div class="content-grid-image">
                            <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" />
                            <?php if ($overlay_logo): ?>
                                <div class="overlay-logo">
                                    <img src="<?= esc_url($overlay_logo['url']); ?>" alt="<?= esc_attr($overlay_logo['alt']); ?>" />
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($title): ?>
                        <h3><?= esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($content): ?>
                        <p><?= esc_html($content); ?></p>
                    <?php endif; ?>

                    <?php if ($link && isset($link['url'])): ?>
                        <div class="wp-block-button is-style-bg">
                            <a href="<?= esc_url($link['url']); ?>"
                                target="<?= esc_attr($link['target'] ? $link['target'] : '_self'); ?>"
                                class="wp-block-button__link">
                                <?= esc_html($link['title'] ? $link['title'] : 'Learn More'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>