<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'link-group'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$title = get_field('title');
?>

<div class="<?= esc_attr($className); ?>">
    <?php if ($title) { ?>
        <div class="link-group__title">
            <p><?= $title; ?></p>
        </div>
    <?php } ?>

    <?php if (have_rows('links')): ?>
        <ul class="link-group__list">
            <?php
            while (have_rows('links')): the_row();
                $link = get_sub_field('link');
                $link_url = $link['url'] ?? '';
                $link_target = !empty($link['target']) && $link['target'] === '_blank' ? '_blank' : '_self';
            ?>
                <li class="link-group__item">
                    <a href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>">
                        <span class="btn">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12.8159" r="11.25" transform="rotate(90 12 12.8159)" stroke="#FF8A45" stroke-width="1.5" />
                                <path d="M12 1.4165L12 14.6165" stroke="#FF8A45" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.1992 10.4165L11.9992 14.6165L7.79922 10.4165" stroke="#FF8A45" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span><?= esc_html($link['title'] ?? 'Read More') ?></span>

                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

</div>