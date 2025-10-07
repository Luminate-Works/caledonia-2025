<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'testimonial-carousel' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get selected testimonials via ACF Relationship field
$testimonials = get_field('select_testimonials'); // Relationship field (array of posts)
$total = $testimonials ? count($testimonials) : 0;

// Add extra class if only one slide
if ($total === 1) {
    $className .= ' single-slide';
}
?>

<?php if ($testimonials): ?>
    <div class="swiper <?= esc_attr($className); ?> fade">
        <div class="swiper-wrapper">
            <?php foreach ($testimonials as $post): 
                setup_postdata($post);

                $title    = esc_html(get_the_title($post->ID));
                $position = esc_html(get_field('subtitle', $post->ID));
                $keyword  = esc_html(get_field('keyword', $post->ID));
                $image_id = get_post_thumbnail_id($post->ID);
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            ?>
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
            <?php endforeach; wp_reset_postdata(); ?>
        </div>

        <?php get_template_part('/parts/part', 'carousel-nav'); ?>
    </div>
<?php endif; ?>