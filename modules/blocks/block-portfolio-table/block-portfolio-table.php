<?php
    $id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-'.$block['id'];
    $className = 'portfolio-table';
    if (!empty($block['className'])) $className .= ' '.$block['className'];
    if (!empty($block['align'])) $className .= ' align'.$block['align'];
?>

<div class="<?= esc_attr($className); ?>">
  <?php if( have_rows('list') ): ?>
    <?php while( have_rows('list') ) : the_row(); 
        $icon = get_sub_field('icon');
        $company = get_sub_field('company');
        $first_invested = get_sub_field('first_invested');
        $value = get_sub_field('value');
        $url = get_sub_field('url');
    ?>
    
    <?php if($url) : ?>
        <a class="list-item" href="<?= $url; ?>" target="_blank">
    <?php else : ?>
        <div class="list-item">
    <?php endif; ?>
            <img class="icon" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($text); ?>">
            <p class="company"><?php echo esc_html($company); ?></p>
            <p class="first_invested"><span>First Invested</span><?php echo esc_html($first_invested); ?></p>
            <p class="value"><span>Value</span><?php echo esc_html($value); ?></p>
     <?php if($url) : ?>
        </a>
    <?php else : ?>
        </div>
    <?php endif; ?>
       
    <?php endwhile; ?>
<?php endif; ?>
</div>
