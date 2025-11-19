<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'related-pages'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
?>

<?php
$featured_posts = get_field('select_page');
$block_title = get_field('rp_title');
$bg_image = get_field('rp_background_image');
if ($featured_posts):
?>
    <div class="<?= esc_attr($className); ?>">

        <div class="wrap has-global-padding">

            <?php if (!empty($bg_image)): ?>
                <div class="bg" style="background-image: url('<?php echo esc_url($bg_image['url']); ?>');"></div>
            <?php endif; ?>

            <div class="glass"></div>

            <div class="fade title">
                <h3><?php echo esc_html($block_title); ?></h3>
            </div>

            <ul>
                <?php foreach ($featured_posts as $featured_post):
                    $permalink = get_permalink($featured_post->ID);
                    $title = get_the_title($featured_post->ID);
                ?>

                    <li class="fade">
                        <a href="<?php echo esc_url($permalink); ?>">
                            <span class="btn">
                                <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="16" cy="16.3159" r="15.2" stroke="#FF8A45" stroke-width="1.6" />
                                    <path d="M11.0938 15.9995H20.4271" stroke="#FF8A45" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M15.7617 11.3328L20.4284 15.9994L15.7617 20.6661" stroke="#FF8A45" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="equal"><?php echo esc_html($title); ?></p>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>
    </div>
<?php endif; ?>