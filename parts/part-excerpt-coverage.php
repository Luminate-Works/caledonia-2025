<?php

$terms = get_the_terms(get_the_ID(), 'press-coverage-category');
$external = get_field('external_link');

if (! empty($terms) && ! is_wp_error($terms)) {
    $term     = $terms[0];
    $term_name = $term->name;
    $term_slug = $term->slug;
}

?>

<a class="excerpt" target="_blank" href="<?php echo $external; ?>" title="<?= esc_attr(get_the_title()); ?>" aria-label="Read more about <?= esc_attr(get_the_title()); ?>">

    <div class="content-wrapper">

        <div class="meta-fields">
            <p aria-label="Published date: <?= get_the_date('F Y'); ?>"><?= get_the_date('F Y'); ?></p>
            <p class="meta"><?php echo $term_name; ?></p>

        </div>

        <h3 class="e2"><?= get_the_title(); ?></h3>

        <?php /*if (get_field('excerpt')) { ?>
        <p><?php echo excerpt(15); ?></p>
    <?php } */ ?>

        <div class="wp-block-buttons">
            <div class="wp-block-button is-style-plain"><span class="wp-block-button__link">Read more</span></div>
        </div>

    </div>

</a>