<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'lmn-icon-list'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
$cleanedClassName = trim(str_replace('lmn-icon-list', '', $className));

if (get_field('dark_text')) {
    $className .= ' dark_text';
}

if (get_field('row_layout')) {
    $className .= ' row_layout';
}

if (get_field('border_bottom')) {
    $className .= ' border_bottom';
}

?>

<div class="<?= esc_attr($className); ?>">
    <?php if (have_rows('list')): ?>
        <?php while (have_rows('list')) : the_row();
            $icon = get_sub_field('icon');
            $text = get_sub_field('text');
        ?>
            <div class="list-item">
                <img class="icon" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($text); ?>">
                <p class="text"><?php echo esc_html($text); ?></p>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>