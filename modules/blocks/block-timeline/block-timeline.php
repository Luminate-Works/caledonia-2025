<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'timeline' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

?>

<?php if (have_rows('timeline_event')): ?>
<div class="<?= esc_attr($className) ?>">

    <?php while (have_rows('timeline_event')): the_row(); ?>
    <div class="timeline__event-block">
        
        <?php if ($date = get_sub_field('date')): ?>
        <div class="timeline__date">
            <h2><?= esc_html($date) ?></h2>
        </div>
        <?php endif; ?>

        <?php if (have_rows('events')): ?>
        <div class="timeline__events">
            <?php while (have_rows('events')): the_row(); ?>
                <div class="event">
                    <?php the_sub_field('event'); ?>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <span class="divider"></span>
        
    </div>
    <?php endwhile; ?>

</div>
<?php endif; ?>
