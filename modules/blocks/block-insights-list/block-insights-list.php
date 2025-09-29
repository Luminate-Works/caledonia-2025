<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'insights-list'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

$insights = get_field('select_insights');

?>

<?php
$wrapper_tag = 'div';
$wrapper_attributes = 'class="' . esc_attr($className) . '"';
?>


<<?= $wrapper_tag ?> <?= $wrapper_attributes ?>>

    <?php if ($insights): ?>
        <h5>Top Reads</h5>
        <ul>
            <?php $counter = 1;
            foreach ($insights as $insight):
                $permalink = get_permalink($insight->ID);
                $title = get_the_title($insight->ID);
            ?>
                <li>
                    <span><?php echo $counter; ?></span>
                    <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                </li>
            <?php $counter++;
            endforeach; ?>
        </ul>
    <?php endif; ?>


    </div>