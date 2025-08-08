<a class="excerpt" href="<?php the_permalink(); ?>" title="<?= esc_attr(get_the_title()); ?>" aria-label="Read more about <?= esc_attr(get_the_title()); ?>">
		
    <span class="feat-wrap">
        <span class="feat" style="background-image: url(<?= get_the_post_thumbnail_url(); ?>);"></span>
        <span class="btn">
            <?= file_get_contents(get_template_directory().'/assets/images/custom/icon-button-arrow.svg'); ?>
        </span>
    </span>

    <h3><?= get_the_title(); ?></h3>

    <p class="meta" aria-label="Published date: <?= get_the_date(); ?>"><?= get_the_date(); ?></p>

    <?php if (get_field('excerpt')) { ?>
        <p><?php echo excerpt(15); ?></p>
    <?php } ?>
    
</a>