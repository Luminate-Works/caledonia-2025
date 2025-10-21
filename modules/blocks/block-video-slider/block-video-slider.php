<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'video-slides'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$download_videos = get_field('downloadable_videos');
$slides = get_field('slides');
?>

<?php if ($slides): ?>
    <div id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">

        <div class="swiper video-slider">
            <div class="swiper-wrapper">
                <?php foreach ($slides as $slide): ?>
                    <?php
                    $video_id  = $slide['video']['ID'] ?? null;
                    $video_url = $slide['video']['url'] ?? '';
                    $info      = $video_id ? get_offloaded_file_info($video_id) : ['ext' => 'N/A', 'size' => 'N/A'];
                    ?>
                    <div class="swiper-slide">
                        <div class="media-wrapper">
                            <video src="<?php echo esc_url($video_url); ?>" muted playsinline></video>

                            <?php if ($download_videos): ?>
                                <a class="download-btn" href="<?php echo esc_url($video_url); ?>" target="_blank" download>
                                    <div class="icon"></div>
                                    <div class="text">
                                        Download Video <span><?php echo esc_html($info['ext']); ?> - <?php echo esc_html($info['size']); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="swiper-pagination"></div>

            <div class="swiper-controls">
                <div class="swiper-button-next swiper-nav">
                    <svg width="56" height="57" viewBox="0 0 56 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M56 28.3159C56 43.7799 43.464 56.3159 28 56.3159C12.536 56.3159 0 43.7799 0 28.3159C0 12.8519 12.536 0.315918 28 0.315918C43.464 0.315918 56 12.8519 56 28.3159Z" fill="white" />
                        <path d="M21 28.5386H35" stroke="#010035" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M28 21.5386L35 28.5386L28 35.5386" stroke="#010035" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>

                <div class="swiper-button-prev swiper-nav">
                    <svg width="56" height="57" viewBox="0 0 56 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 28.3159C0 43.7799 12.536 56.3159 28 56.3159C43.464 56.3159 56 43.7799 56 28.3159C56 12.8519 43.464 0.315918 28 0.315918C12.536 0.315918 0 12.8519 0 28.3159Z" fill="white" />
                        <path d="M35 28.5386H21" stroke="#010035" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M28 21.5386L21 28.5386L28 35.5386" stroke="#010035" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>