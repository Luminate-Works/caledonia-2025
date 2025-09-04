<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'video-modal'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$vm_image      = get_field('vm_image');
$vm_video_title = get_field('vm_video_title');
$vm_video_url   = get_field('vm_video_url');
$vm_duration    = get_field('vm_video_duration');
?>

<div class="<?= esc_attr($className); ?>">

    <a href="<?php echo esc_url($vm_video_url); ?>" class="glightbox">

        <?php if (!empty($vm_image)): ?>
            <img src="<?php echo esc_url($vm_image['url']); ?>" alt="<?php echo esc_attr($vm_image['alt']); ?>" />
        <?php endif; ?>


        <?php if (! empty($vm_video_url)) : ?>
            <div class="video-overlay">
                <?php if (! empty($vm_video_title)) : ?>
                    <p class="video-title">
                        <?php echo esc_html($vm_video_title); ?>
                    </p>
                <?php endif; ?>

                <div class="video-control">
                    <span class="video-icon"></span>
                    <div>
                        <p class="video-play">Play video</p>

                        <?php if (! empty($vm_duration)) : ?>
                            <p class="video-duration">
                                <?php echo esc_html($vm_duration); ?>
                            </p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

    </a>

</div>