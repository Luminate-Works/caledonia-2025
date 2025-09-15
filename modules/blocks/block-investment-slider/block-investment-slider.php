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
                $logo = get_field('logo_white', $post->ID);
                $equity = get_field('caledonian_equity', $post->ID);
                $date = get_field('investment_date', $post->ID);
                $type = get_field('investment_type', $post->ID);
                $status = get_field('realised_status', $post->ID);
                $hero = get_the_post_thumbnail_url($post->ID, 'full');

                $terms = get_the_terms($post->ID, 'investment-type');
                $term_classes = '';
                $term_list = '';
                if ($terms && !is_wp_error($terms)) {
                    $term_slugs = wp_list_pluck($terms, 'slug');
                    $term_classes = ' ' . implode(' ', array_map('sanitize_html_class', $term_slugs));

                    $term_names = wp_list_pluck($terms, 'name');
                    $term_list = implode(', ', $term_names);
                }
                ?>

                <div class="investment-slide swiper-slide<?= esc_attr($term_classes); ?>">
                    <div class="investment-content">
                        <?php if ($term_list): ?>
                            <div class="taxonomy">
                                <?php echo esc_html($term_list); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="logo">
                            <?php if ($logo) echo wp_get_attachment_image($logo['id'], 'medium'); ?>
                        </div>
                        <h3><?php the_title(); ?></h3>
                        <?php the_content(); ?>

                        <div class="controls">
                            <div class="swiper-pagination"></div>

                            <div class="swiper-controls">
                                <div class="swiper-button-next swiper-nav">

                                    <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="20" cy="20.9355" r="19" stroke="#fff" stroke-width="2" />
                                        <path d="M13.8691 20.54H25.5358" stroke="#fff" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19.7031 14.7061L25.5365 20.5394L19.7031 26.3727" stroke="#fff" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </div>

                                <div class="swiper-button-prev swiper-nav">

                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="20" cy="20" r="19" transform="matrix(-1 0 0 1 40 0)" stroke="#fff" stroke-width="2" />
                                        <path d="M26.1309 19.6045H14.4642" stroke="#fff" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20.2969 13.7705L14.4635 19.6038L20.2969 25.4372" stroke="#fff" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="investment-meta" style="background-image: url('<?php echo esc_url($hero); ?>')">
                        <div class="meta-box">
                            <div class="meta-box-group">
                                <div class="stat">
                                    <span>Caledonia equity</span>
                                    <h3><?php echo esc_html($equity); ?></h3>
                                </div>
                                <div class="stat">
                                    <span>Date of investment</span>
                                    <p><?php echo esc_html($date); ?></p>
                                </div>
                            </div>
                            <div class="meta-box-group">
                                <div class="stat">
                                    <span>Type of investment</span>
                                    <p><?php echo esc_html($type); ?></p>
                                </div>
                                <div class="stat">
                                    <span>Realised status</span>
                                    <p><?php echo esc_html($status); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;
            wp_reset_postdata(); ?>
            


        </div>

    </div>

<?php endif; ?>