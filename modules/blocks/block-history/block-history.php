<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'history-timeline' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$history_years = get_field('history_years') ?: [];
?>

<?php if ($history_years): ?>

    <div class="<?= esc_attr($className); ?>">

        <?php foreach ($history_years as $index => $year_data): ?>
            <div class="history-year-item" data-year="<?php echo esc_attr($year_data['year']); ?>">
                <!-- Background Image -->
                <?php if (!empty($year_data['background_image'])): ?>
                    <div class="history-background" style="background-image: url('<?php echo esc_url($year_data['background_image']['sizes']['large'] ?? $year_data['background_image']['url']); ?>');">
                    </div>
                <?php endif; ?>

                <div class="wrap">

                    <span class="year-overlay"> <?php echo esc_html(preg_replace('/[^0-9]/', '', $year_data['year'])); ?></span>

                    <div class="history-left">
                        <?php if (!empty($year_data['year'])): ?>
                            <h2><?php echo esc_html($year_data['year']); ?></h2>
                        <?php endif; ?>


                        <div class="history-content">
                            <?php if (!empty($year_data['title'])): ?>
                                <p class="title"><?php echo esc_html($year_data['title']); ?></hp>
                                <?php endif; ?>

                                <?php if (!empty($year_data['text'])): ?>
                                <p><?php echo nl2br(esc_html($year_data['text'])); ?></p>
                            <?php endif; ?>
                        </div>


                        <?php if (!empty($year_data['quote_group']['quote'])): ?>
                            <div class="history-quote">
                                <blockquote>
                                    "<?php echo esc_html($year_data['quote_group']['quote']); ?>"
                                </blockquote>
                                <div class="quote-attribution">
                                    <?php if (!empty($year_data['quote_group']['quote_name'])): ?>
                                        <span class="quote-name"><?php echo esc_html($year_data['quote_group']['quote_name']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($year_data['quote_group']['quote_position'])): ?>
                                        <span class="quote-position"><?php echo esc_html($year_data['quote_group']['quote_position']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>


                    <div class="history-images">
                        <?php if (!empty($year_data['main_image'])): ?>
                            <div class="main-image">
                                <img src="<?php echo esc_url($year_data['main_image']['sizes']['large'] ?? $year_data['main_image']['url']); ?>"
                                    alt="<?php echo esc_attr($year_data['main_image']['alt'] ?? ''); ?>" />
                                <?php if (!empty($year_data['main_image_caption'])): ?>
                                    <div class="image-caption">
                                        <div class="caption-avatar">
                                            <?php if (!empty($year_data['main_image_caption_image'])): ?>
                                                <img src="<?php echo esc_url($year_data['main_image_caption_image']['sizes']['thumbnail'] ?? $year_data['main_image_caption_image']['url']); ?>"
                                                    alt="<?php echo esc_attr($year_data['main_image_caption_image']['alt'] ?? ''); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="caption-text">
                                            <p><?php echo esc_html($year_data['main_image_caption']); ?></p>
                                            <p class="sub"><?php echo esc_html($year_data['main_image_sub-caption']); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($year_data['supporting_image']) && empty($year_data['main_image_caption'])): ?>
                                    <div class="supporting-image">
                                        <img src="<?php echo esc_url($year_data['supporting_image']['sizes']['medium'] ?? $year_data['supporting_image']['url']); ?>"
                                            alt="<?php echo esc_attr($year_data['supporting_image']['alt'] ?? ''); ?>">
                                    </div>
                                <?php endif; ?>
                            </div>


                        <?php endif; ?>


                    </div>


                </div>

            </div>
        <?php endforeach; ?>

    </div>

<?php endif; ?>