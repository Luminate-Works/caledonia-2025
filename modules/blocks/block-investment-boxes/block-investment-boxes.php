<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'lmn-investment-boxes'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
$cleanedClassName = trim(str_replace('lmn-investment-boxes', '', $className));
?>

<div class="<?= esc_attr($className); ?>">

    <?php if (have_rows('investment_boxes')): ?>
        <?php while (have_rows('investment_boxes')): the_row();
            $image = get_sub_field('icon');
            $heading = get_sub_field('title');
            $desc = get_sub_field('content');
            $stats_title = get_sub_field('stat_title');
            $stats_number = get_sub_field('stat_value');
            $stats_label = get_sub_field('stat_label');
            $btn = get_sub_field('link');
        ?>

            <?php if ($btn): ?>
                <a href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target'] ?: '_self'); ?>" class="box">
                <?php endif; ?>


                <?php if ($image): ?>
                    <figure class="icon">
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    </figure>
                <?php endif; ?>

                <?php if ($heading): ?>
                    <h4><?php echo esc_html($heading); ?></h4>
                <?php endif; ?>

                <?php if ($desc): ?>
                    <p class="content"><?php echo esc_html($desc); ?></p>
                <?php endif; ?>

                <div class="bottom">

                    <div class="statistics">

                        <?php if ($stats_number): ?>
                            <p class="value"><?php echo esc_html($stats_number); ?></p>
                        <?php endif; ?>

                        <?php if ($stats_title): ?>
                            <p class="equal title"><?php echo esc_html($stats_title); ?></p>
                        <?php endif; ?>

                        <?php if ($stats_label): ?>
                            <p class="sub-title"><?php echo esc_html($stats_label); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if ($btn): ?>
                        <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                            <div class="wp-block-button is-style-plain-white">
                                <div class="wp-block-button__link wp-element-button">
                                    <?php echo esc_html($btn['title']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <?php if ($btn): ?>
                </a>
            <?php endif; ?>

        <?php endwhile; ?>
        
    <?php endif; ?>

</div>