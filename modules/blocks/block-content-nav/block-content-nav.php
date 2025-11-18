<?php if (have_rows('contents_navigation')): ?>

    <!-- Desktop Navigation -->
    <ul class="contents<?php if (get_field('fixed_width_buttons')) {
                            echo " fw";
                        } ?>">
        <?php while (have_rows('contents_navigation')): the_row(); ?>
            <li class="content-label">
                <a href="#<?php the_sub_field('id'); ?>">
                    <?php the_sub_field('title'); ?>
                </a>
            </li>
            <?php the_sub_field('subfield_name'); ?>
        <?php endwhile; ?>
    </ul>

    <!-- Mobile Dropdown -->
    <div class="mobile-dropdown">
        <select id="content-select">
            <?php while (have_rows('contents_navigation')): the_row(); ?>
                <option value="#<?php the_sub_field('id'); ?>">
                    <?php the_sub_field('title'); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

<?php endif; ?>