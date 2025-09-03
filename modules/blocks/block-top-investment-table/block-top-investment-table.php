<?php
    $id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-'.$block['id'];
    $className = 'top-investment-table';
    if (!empty($block['className'])) $className .= ' '.$block['className'];
    if (!empty($block['align'])) $className .= ' align'.$block['align'];
?>

<div class="<?= esc_attr($className); ?>">
  <?php if( have_rows('list') ): ?>
    <div class="list-item heading">
     <p class="name">Name</p>
     <p class="pool">Pool</p>
     <p class="geography">Geography<sup>1</sup></p>
     <p class="business">Business</p>
     <p class="value">Value (£m)</p>
     <p class="net_assets">Net assets (%)</p>
    </div>
    <?php while( have_rows('list') ) : the_row(); 
        $icon = get_sub_field('icon');
        $name = get_sub_field('name');
        $pool = get_sub_field('pool');
        $geography = get_sub_field('geography');
        $business = get_sub_field('business');
        $value = get_sub_field('value');
        $net_assets = get_sub_field('net_assets');
    ?>
        <div class="list-item">
            <div class="icon-wrapper">
                <img class="icon" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($text); ?>">
                <p class="name"><?php echo esc_html($name); ?></p>
            </div>
            <p class="pool"><?php echo esc_html($pool); ?></p>
            <p class="geography"><?php echo esc_html($geography); ?></p>
            <p class="business"><?php echo esc_html($business); ?></p>
            <p class="value"><?php echo esc_html($value); ?></p>
            <p class="net_assets"><?php echo esc_html($net_assets); ?></p>
        </div>
   
       
    <?php endwhile; ?>
<?php endif; ?>
</div>
