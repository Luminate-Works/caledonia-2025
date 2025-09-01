<?php
    $id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-'.$block['id'];
    $className = 'pie-chart';
    if (!empty($block['className'])) $className .= ' '.$block['className'];
    if (!empty($block['align'])) $className .= ' align'.$block['align'];
?>

<div class="<?= esc_attr($className); ?>">
  <?php if( have_rows('list') ): ?>
    <?php while( have_rows('list') ) : the_row(); 
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
