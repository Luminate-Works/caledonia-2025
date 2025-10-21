<?php

$categories = get_the_terms(get_the_ID(), 'external-research-category');
$download = get_field('dowload_link');

if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
    $term = $categories[0];
    $term_name = $term->name;
    $term_slug = $term->slug;
}

?>

<a class="excerpt" target="_blank" href="<?php echo $download; ?>" title="<?= esc_attr(get_the_title()); ?>" aria-label="Read more about <?= esc_attr(get_the_title()); ?>">

    <div class="meta-fields">
        <p aria-label="Published date: <?= get_the_date('F Y'); ?>"><?= get_the_date('F Y'); ?></p>
        <p class="meta"><?php echo $term_name; ?></p>
    </div>

    <h3 class="equal"><?= get_the_title(); ?></h3>

    <?php /*if (get_field('excerpt')) { ?>
        <p><?php echo excerpt(15); ?></p>
    <?php } */ ?>

    <div class="wp-block-buttons">
        <div class="wp-block-button is-style-plain"><span class="wp-block-button__link">Read more</span></div>
    </div>

</a>