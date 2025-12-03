<?php get_header();

$search_query = get_search_query();
$results_count = $wp_query->found_posts; ?>


<div class="wrap has-global-padding full-width">
    <div id="content">

        <?php
        $titles_in_banner = class_exists('ACF') ? get_field('titles_in_banner', 'option') : false;
        if (!$titles_in_banner):
        ?>
            <h1 class="main-heading"><?php the_title(); ?></h1>
        <?php endif; ?>

        <div class="meta">
            <p>Showing <?php echo $results_count; ?> results for ‘<?php echo esc_html($search_query); ?>’</p>

            <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
                <div class="search-wrapper">
                    <input type="search" class="search-field"
                        placeholder="Looking for something else?"
                        name="s" />

                    <button type="submit" class="search-button">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.2495 18.8271C15.689 18.8271 19.288 15.2281 19.288 10.7885C19.288 6.34898 15.689 2.75 11.2495 2.75C6.80991 2.75 3.21094 6.34898 3.21094 10.7885C3.21094 15.2281 6.80991 18.8271 11.2495 18.8271Z" stroke="#010035" stroke-width="1.5" stroke-linecap="square" />
                            <path d="M16.7383 16.709L21.2918 21.2506" stroke="#010035" stroke-width="1.5" stroke-linecap="square" />
                        </svg>

                    </button>
                </div>
            </form>


        </div>

        <?php if (have_posts()): ?>
            <div class="search-results">
                <?php while (have_posts()): the_post();


                    $post_type = get_post_type();
                    $post_id    = get_the_ID();
                    $parent_id  = wp_get_post_parent_id($post_id);
                    $children   = get_pages(['child_of' => $post_id]);

                    $label = '';
                    $title = get_the_title();
                    $url = get_permalink();
                    $target = '_self';

                    $excerpt = get_the_excerpt() ? wp_trim_words(get_the_excerpt(), 40, '[...]') : wp_trim_words(get_the_content(), 40, '[...]');

                    if ($post_type === 'page') {
                        if (!$parent_id && $children) {
                            $label = get_the_title($post_id); 
                            $title = 'Overview';
                        }

                        // 2. CHILD PAGE
                        elseif ($parent_id) {
                            $label = get_the_title($parent_id);
                        }

                        else {
                            $label = get_the_title($post_id);
                            $title = 'Overview';
                        }
                    } elseif ($post_type === 'documents') {
                        $label = 'Download';
                        $url = get_permalink();
                        $target = '_blank';
                    } elseif ($post_type === 'post') {
                        $label = 'News';
                        $target = '_blank';
                    } elseif ($post_type === 'external-research') {
                        $label = 'Download';
                        $url = get_field('download_link');
                        $target = '_blank';
                    } elseif ($post_type === 'press-coverage') {
                        $label = 'Press';
                        $url = get_field('external_link');
                        $target = '_blank';
                    }
                ?>

                    <a class="search-result-item" href="<?php echo esc_url($url); ?>" target="<?php echo $target; ?>">

                        <span class="search-label">
                            <?php echo esc_html($label); ?>
                        </span>

                        <h5>
                            <?php echo esc_html($title); ?>
                        </h5>

                        <p class="search-excerpt">
                            <?php echo $excerpt; ?>
                        </p>

                        <p class="search-link">
                            <?php echo esc_url($url); ?>
                        </p>

                    </a>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'textdomain'),
                'next_text' => __('Next', 'textdomain'),
            ]);
            ?>

        <?php else: ?>
            <h4 class="main-heading" style="margin-top: 50px;">Nothing found for <?= esc_html(get_search_query()); ?></h4>
            <p>Sorry, we couldn't find any results for "<strong><?= esc_html(get_search_query()); ?></strong>". Please try using different keywords or check the spelling.</p>

        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>