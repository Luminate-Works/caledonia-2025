<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'insights-featured'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

$featured = get_posts([
    'post_type'      => 'insights',
    'posts_per_page' => 1,
    'meta_key'       => 'featured',
    'meta_value'     => '1',
]);

$hide_icons   = get_field('hide_icons');

?>

<?php
$wrapper_tag = 'div';
$wrapper_attributes = 'class="' . esc_attr($className) . '"';
?>


<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>

    <?php if ($featured) {
        $post = $featured[0];
        setup_postdata($post);
        // Custom helpers
        $reading_time = get_field('est_reading_time', $post->ID);
        $terms        = get_the_terms($post->ID, 'insights-category');
        $term_name    = $terms && !is_wp_error($terms) ? $terms[0]->name : '';
    ?>

        <h5>Featured</h5>

        <a class="excerpt" href="<?php the_permalink($post); ?>"
            title="<?= esc_attr(get_the_title($post)); ?>"
            aria-label="Read more about <?= esc_attr(get_the_title($post)); ?>">

            <?php if (has_post_thumbnail($post)) : ?>
                <div class="img-wrapper">
                    <?= get_the_post_thumbnail($post->ID, 'full'); ?>
                </div>
            <?php endif; ?>

            <div class="content-wrapper">
                <div class="meta-fields">
                    <p aria-label="Published date: <?= get_the_date('F Y', $post); ?>">
                        <?= get_the_date('F Y', $post); ?>
                    </p>
                    <p class="meta">Reading time <?= esc_html($reading_time); ?> mins</p>

                    <?php if (!$hide_icons): ?>
                        <?php if ($term_name === 'Podcast') : ?>
                            <div class="icon">
                                <!-- Podcast SVG -->
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12.3164" r="11.5" stroke="#010035" stroke-opacity="0.1" />
                                    <path d="M12 18.1799V16.2812" stroke="#010035" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.8064 11.457C16.8128 14.1165 14.6614 16.2776 12.002 16.284C9.34185 16.2776 7.19079 14.1165 7.19728 11.457" stroke="#010035" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0001 13.8883C10.6496 13.8857 9.55731 12.7881 9.56055 11.4383V8.62316C9.56055 7.27592 10.6529 6.18359 12.0001 6.18359C13.3474 6.18359 14.4397 7.27592 14.4397 8.62316V11.4383C14.4429 12.7881 13.3513 13.885 12.0015 13.8883H12.0001Z" stroke="#010035" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        <?php elseif ($term_name === 'Video') : ?>
                            <div class="icon">
                                <!-- Video SVG -->
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12.5" cy="12.3164" r="11.5" stroke="#010035" stroke-opacity="0.1" />
                                    <path d="M9.5 8.75863C9.5 7.70374 10.667 7.06662 11.5543 7.63706L17.0887 11.1948C17.9051 11.7197 17.9051 12.9131 17.0887 13.438L11.5543 16.9958C10.667 17.5662 9.5 16.9291 9.5 15.8742V8.75863Z" stroke="#010035" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <h3><?= get_the_title($post); ?></h3>

                <div class="wp-block-buttons">
                    <div class="wp-block-button is-style-plain">
                        <span class="wp-block-button__link">Read more</span>
                    </div>
                </div>

            </div>

        </a>

    <?php
        wp_reset_postdata();
    } else {
        echo '<p>No featured insight found.</p>';
    }

    ?>

    </div>