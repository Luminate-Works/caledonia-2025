<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'hover-boxes' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

?>

<div class="<?= esc_attr($className) ?>">

    <?php if (have_rows('hover_boxes')): ?>
        <?php
        $i = 0;
        while (have_rows('hover_boxes')): the_row();
            $title       = get_sub_field('title');
            $sub_heading = get_sub_field('sub_heading');
            $content     = get_sub_field('content');
            $bg_image      = get_sub_field('bg_image');

            $position_class = ($i % 2 === 0) ? 'box box-left' : 'box box-right';
        ?>
            <div class="<?= esc_attr($position_class) ?>">

                <?php if (!empty($bg_image)): ?>
                    <div class="box-bg" style="background-image: url('<?= esc_url($bg_image['url']); ?>');"></div>
                <?php endif; ?>

                <div class="box-content">
                    <?php if ($title): ?>
                        <h3><?= esc_html($title) ?></h3>
                    <?php endif; ?>

                    <?php if ($sub_heading): ?>
                        <p class="sub-heading"><?= esc_html($sub_heading) ?></p>
                    <?php endif; ?>

                    <?php if ($content): ?>
                        <p><?= esc_html($content) ?></p>
                    <?php endif; ?>
                </div>

            </div>
        <?php
            $i++;
        endwhile;
        ?>
    <?php endif; ?>

</div>