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
            
            <?php if($icon) : ?>
            <div class="icon-wrapper">
                <img class="icon" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($text); ?>">
            </div>
           <?php endif; ?>
                 
            <p class="company <?= ($icon) ? "" : 'no-icon'?>"><?php echo esc_html($company); ?></p>
            
            <p class="first_invested">
                 <?php if($icon) : ?>
                    <span>First Invested</span>
                    <?php echo esc_html($first_invested); ?>
                    <?php else : ?>
                        <span class="no-value"></span>
                    <?php endif; ?>
            </p>

            <p class="value"><span>Value</span><?php echo esc_html($value); ?></p>
            
     <?php if($url) : ?>
        <span class="hover-link"></span>
        </a>
    <?php else : ?>
        </div>
    <?php endif; ?>
       
    <?php endwhile; ?>
<?php endif; ?>

<?php if(get_field('total_row')) : ?>
    <div class="list-item total-row <?= (get_field('white_background')) ? "white-bg" : ""; ?>">
        <p class="company no-icon">Total</p>
        <p class="value"><span>Value</span><?php echo esc_html(get_field('total_value')); ?></p>
    </div>
<?php endif; ?>
</div>
