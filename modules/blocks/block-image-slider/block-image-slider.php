<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'image-slides'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$slides = get_field('slides');
?>


<?php if ($slides): ?>
    <div class="<?= esc_attr($className); ?>">

        <div class="swiper image-slider">
            <div class="swiper-wrapper">
                <?php foreach ($slides as $slide): ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_url($slide['image']['url']); ?>" alt="<?php echo esc_attr($slide['image']['alt']); ?>">
                        <?php if (!empty($slide['caption'])): ?>
                            <p class="slide-caption"><?php echo esc_html($slide['caption']); ?></p>
                        <?php endif; ?>
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