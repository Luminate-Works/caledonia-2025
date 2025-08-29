<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'testimonial-card'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

$bg_image        = get_field('tc_background');
$quote           = get_field('tc_quote');
$quote_name      = get_field('tc_quote_name');
$quote_title     = get_field('tc_quote_title');

?>

<?php
$wrapper_tag = 'div';
$wrapper_attributes = 'class="' . esc_attr($className) . '"';
?>

<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>

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