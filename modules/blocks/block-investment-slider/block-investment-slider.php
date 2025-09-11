<?php

global $post;

// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'swiper investment-slider'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

$investments = get_field('investments');
?>


<?php if ($investments): ?>
    <div class="<?= esc_attr($className); ?>">

        <div class="swiper-wrapper">


            <?php foreach ($investments as $post): setup_postdata($post); ?>
                <?php
                $logo = get_field('company_logo', $post->ID);
                $equity = get_field('caledonian_equity', $post->ID);
                $date = get_field('investment_date', $post->ID);
                $type = get_field('investment_type', $post->ID);
                $status = get_field('realised_status', $post->ID);
                $hero = get_the_post_thumbnail_url($post->ID, 'full');
                ?>

                <div class="investment-slide swiper-slide">
                    <div class="investment-content">
                        <div class="logo">
                            <?php if ($logo) echo wp_get_attachment_image($logo, 'medium'); ?>
                        </div>
                        <h3><?php the_title(); ?></h3>
                        <?php the_content(); ?>

                        <div class="controls">
                            <?php get_template_part('/parts/part', 'carousel-nav'); ?>
                        </div>
                    </div>
                    <div class="investment-meta" style="background-image: url('<?php echo esc_url($hero); ?>')">
                        <div class="meta-box">
                            <div><strong>Caledonia equity</strong> <?php echo esc_html($equity); ?></div>
                            <div><strong>Date of investment</strong> <?php echo esc_html($date); ?></div>
                            <div><strong>Type of investment</strong> <?php echo esc_html($type); ?></div>
                            <div><strong>Realised status</strong> <?php echo esc_html($status); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach;
            wp_reset_postdata(); ?>


        </div>

    </div>

<?php endif; ?>