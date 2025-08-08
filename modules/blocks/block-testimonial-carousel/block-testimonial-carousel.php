<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'testimonial-carousel' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Query all testimonial posts
$args = [
    'post_type'      => 'testimonials',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'rand',
    'order'          => 'DESC'
];
global $post;

$testimonial_query = new WP_Query($args);
$total = $testimonial_query->post_count; // Get total number of testimonials

// Add an extra class if there is only one slide
if ($total === 1) {
    $className .= ' single-slide';
}
?>

<?php if ($testimonial_query->have_posts()): ?>
    <div class="swiper <?= esc_attr($className); ?>">
        <div class="swiper-wrapper">
            <?php $index = 0;
            while ($testimonial_query->have_posts()): $testimonial_query->the_post();
                $title = esc_html(get_the_title());
                $position = esc_html(get_field('subtitle', $post));
                $keyword = esc_html(get_field('keyword', $post));
                $post_id = get_the_ID();
                $image_id = get_post_thumbnail_id($post_id);
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);            ?>

                <div class="swiper-slide">
                    <div class="image">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                    </div>
                    <blockquote class="testimonial-quote">
                        <svg width="44" height="42" viewBox="0 0 44 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.04 7.85593C10.24 9.77594 5.12 14.8959 4.48 24.6559H13.76V41.9359H0V26.5759C0 12.3359 6.08 3.69593 19.04 0.335938V7.85593ZM24 41.9359V26.5759C24 12.3359 30.08 3.69593 43.04 0.335938V7.85593C34.24 9.77594 29.12 14.8959 28.48 24.6559H37.76V41.9359H24Z" fill="#010035" />
                        </svg>

                        <?php if ($keyword): ?>
                            <h2 class="position"><?= $keyword; ?></h2>
                        <?php endif; ?>

                        <?= wp_kses_post(get_the_content()); ?>

                        <cite>
                            <strong class="title"><?= $title; ?></strong>
                            <?php if ($position): ?>
                                <span class="position"><?= $position; ?></span>
                            <?php endif; ?>
                        </cite>
                    </blockquote>
                </div>

            <?php $index++;
            endwhile;
            wp_reset_postdata(); ?>
        </div>

        <?php get_template_part('/parts/part', 'carousel-nav'); ?>

    </div>
<?php endif; ?>