<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'scrolling-text'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

$bg_image        = get_field('st_background');
$heading    = get_field('st_heading');
$words      = get_field('st_words');

?>

<?php
$wrapper_tag = 'div';
$wrapper_attributes = 'class="' . esc_attr($className) . '"';
?>

<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>

    <div class="scrolling-image" style="background-image: url('<?= esc_url($bg_image['url']); ?>');">



        <?php if ($heading): ?>
            <div class="half">
                <h3><?php echo esc_html($heading); ?></h3>
            </div>
        <?php endif; ?>


        <?php if ($words): ?>
            <div class="half">
                <ul>
                    <?php foreach ($words as $row): ?>
                        <li><?php echo esc_html($row['word']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


    </div>

    </div>