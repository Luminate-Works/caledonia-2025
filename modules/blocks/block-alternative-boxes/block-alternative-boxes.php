<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'alternative-boxes'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');


?>

<?php
$wrapper_tag = 'div';
$wrapper_attributes = 'class="' . esc_attr($className) . '"';
?>

<?php if (have_rows('alternative_boxes')): ?>
    <<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>

        <?php while (have_rows('alternative_boxes')): the_row();
            $image = get_sub_field('image');
            $title = get_sub_field('title');
            $content = get_sub_field('content');

            $row_number = str_pad(get_row_index(), 2, '0', STR_PAD_LEFT);
        ?>
            <div class="alternative-box">

                <div class="numbers">
                    <?php echo $row_number; ?>
                </div>

                <div class="content-wrapper">

                    <?php if ($image): ?>
                        <div class="alt-box-image">
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                        </div>
                    <?php endif; ?>

                    <div class="content">
                        <?php if ($title): ?>
                            <h3><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>
                        <?php if ($content): ?>
                            <p><?php echo wp_kses_post($content); ?></p>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        <?php endwhile; ?>

    </div>
<?php endif; ?>