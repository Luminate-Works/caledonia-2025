<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'testimonial-boxes'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

?>

<div class="<?= esc_attr($className); ?>">

    <?php if (have_rows('testimonial_boxes')) : ?>
        <?php while (have_rows('testimonial_boxes')) : the_row();
            $bg_colour       = get_sub_field('background_colour');
            $accent_colour   = get_sub_field('accent_colour');
            $title           = get_sub_field('title');
            $content         = get_sub_field('content');
            $date            = get_sub_field('date');
            $stats_one_value = get_sub_field('stats_one_value');
            $stats_one_label = get_sub_field('stats_one_label');
            $stats_two_value = get_sub_field('stats_two_value');
            $stats_two_label = get_sub_field('stats_two_label');
            $button          = get_sub_field('button');
            $bg_image        = get_sub_field('background_image');
            $quote           = get_sub_field('quote');
            $quote_name      = get_sub_field('quote_name');
            $quote_title     = get_sub_field('quote_title');
        ?>

            <div class="box">

                <div class="testimonial-content" style="background-color: <?= esc_attr($bg_colour); ?>; border-top: 4px solid <?= esc_attr($accent_colour); ?>;">
                    <?php if ($title) : ?>
                        <h3><?= esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($content) : ?>
                        <p><?= esc_html($content); ?></p>
                    <?php endif; ?>

                    <div class="stats-wrapper">

                        <?php if ($date) : ?>
                            <p class="date"><?= esc_html($date); ?></p>
                        <?php endif; ?>

                        <?php if ($stats_one_value || $stats_two_value) : ?>
                            <div class="stats">
                                <?php if ($stats_one_value) : ?>
                                    <div class="stat">
                                        <span class="value"><?= esc_html($stats_one_value); ?></span>
                                        <span class="label"><?= esc_html($stats_one_label); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($stats_two_value) : ?>
                                    <div class="stat">
                                        <span class="value"><?= esc_html($stats_two_value); ?></span>
                                        <span class="label"><?= esc_html($stats_two_label); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($button) : ?>
                            <div class="wp-block-button is-style-bg-white">
                                <a class="wp-block-button__link" href="<?= esc_url($button['url']); ?>" target="<?= esc_attr($button['target'] ?: '_self'); ?>">
                                    <?= esc_html($button['title']); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>


                <div class="testimonial-image" style="background-image: url('<?= esc_url($bg_image['url']); ?>');">

                    <?php if ($quote) : ?>

                        <div class="quote-wrapper">
                            <blockquote>
                                <span class="quote-mark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="42" viewBox="0 0 44 42" fill="none">
                                        <path d="M19.04 7.52C10.24 9.44 5.12 14.56 4.48 24.32H13.76V41.6H0V26.24C0 12 6.08 3.35999 19.04 0V7.52ZM24 41.6V26.24C24 12 30.08 3.35999 43.04 0V7.52C34.24 9.44 29.12 14.56 28.48 24.32H37.76V41.6H24Z" fill="white" />
                                    </svg>
                                </span>
                                <?= esc_html($quote); ?>
                            </blockquote>
                        <?php endif; ?>

                        <?php if ($quote_name || $quote_title) : ?>
                            <cite>
                                <?= esc_html($quote_name); ?>
                                <?php if ($quote_title) : ?>
                                    <span class="role"><?= esc_html($quote_title); ?></span>
                                <?php endif; ?>
                            </cite>
                        <?php endif; ?>
                        </div>

                </div>

            </div>





        <?php endwhile; ?>
    <?php endif; ?>
</div>