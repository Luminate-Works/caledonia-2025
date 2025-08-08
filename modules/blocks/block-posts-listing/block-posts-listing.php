<?php
    $id = $block['anchor'] ?? ('block-' . $block['id']);
    $className = 'block-posts-grid' .
        (isset($block['className']) ? ' ' . $block['className'] : '') .
        (isset($block['align']) ? ' align' . $block['align'] : '');

    // Get ACF fields data
    $term = get_term(get_field('category'));
    $category = (!is_wp_error($term) && $term instanceof WP_Term) ? $term->slug : '';
    $filter = get_field('filters');
    $listing_type = get_field('listing_type');
?>
<div class="<?= esc_attr($className) ?>">

<?php if ($filter && $listing_type === 'all') : // Check if filter is true and listing type is 'all' ?>
    <div class="category-filter">
        <select id="category-select">
            <option value=""><?= esc_html__('Categories: All', 'lmn'); ?></option>
            <?php
            $categories = get_categories();
            foreach ($categories as $cat) {
                echo '<option value="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</option>';
            }
            ?>
        </select>
        <button id="reset-filter" class="btn"><?= esc_html__('Reset', 'lmn'); ?></button>
    </div>
<?php endif; ?>

<div id="posts-container" class="block-posts">

    <?php
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 18,
        'orderby' => 'date',
        'order' => 'DESC',
        'category_name' => $category, // Add the category to the query arguments
    );

    global $post;

    $the_query = new WP_Query( $args );

    $posts_count = 0;
    if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
        get_template_part('parts/part', 'excerpt');
        $posts_count++;
    endwhile; wp_reset_postdata(); else :
        _e( 'Sorry, no posts matched your criteria.', 'lmn' );
    endif;
    ?>
</div>

<div class="loadmore-container">
    <button id="loadmore" class="btn aligncenter xs<?php echo ($posts_count < 18) ? ' inactive' : ''; ?>">Load more</button>
</div>
</div>

<script>


ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

jQuery(document).ready(function ($) {
    var page = 1; // Start at page 1
    var category = "<?php echo esc_js($category); ?>"; // Default category

    // Function to update URL hash
    function updateHash() {
        if (category) {
            window.location.hash = 'category=' + category;
        } else {
            window.location.hash = '';
        }
    }

    // Function to get category from URL hash
    function getCategoryFromHash() {
        var hash = window.location.hash.substring(1);
        var params = new URLSearchParams(hash);
        return params.get('category');
    }

    // Initialize filter from hash on page load
    var initialCategory = getCategoryFromHash();
    if (initialCategory) {
        category = initialCategory;
        $('#category-select').val(category);
    }

    // Initially disable the reset button if no category is selected
    toggleResetButton();

    // Function to load more posts
    function loadMorePosts(resetPage = false) {
        if (resetPage) {
            page = 1;
            $('#posts-container').html(''); // Clear current posts
            $('#loadmore').removeClass('inactive'); // Enable Load More button
        }

        $.ajax({
            url: ajaxurl,
            data: {
                'action': 'load_more_posts',
                'page': page,
                'post_type': 'post',
                'posts_per_page': 18,
                'post_status': 'publish',
                'category': category // Include category in the AJAX request
            },
            type: 'POST',
            success: function (response) {
                var data = JSON.parse(response);
                if (data.posts) {
                    var $data = $(data.posts);
                    $data.hide();
                    $('#posts-container').append($data);
                    $data.fadeIn(900);
                }
                if (!data.has_more) {
                    $('#loadmore').addClass('inactive'); // Disable Load More button if no more posts
                } else {
                    $('#loadmore').removeClass('inactive');
                }
                page++; // Increment page only after successful load
            },
            error: function () {
                $('#loadmore').addClass('inactive'); // Disable Load More button in case of an error
            }
        });
    }

    // Load more posts when button is clicked
    $('#loadmore').click(function() {
        loadMorePosts(); // Load more posts without resetting the page count
    });

    // Enable/disable reset button based on filter selection
    function toggleResetButton() {
        if ($('#category-select').val()) {
            $('#reset-filter').removeClass('inactive');
        } else {
            $('#reset-filter').addClass('inactive');
        }
    }

    // Change category filter
    $('#category-select').change(function() {
        category = $(this).val();
        loadMorePosts(true); // Reset page count and load posts with the selected category
        toggleResetButton(); // Toggle reset button state
        updateHash(); // Update the URL hash
    });

    // Reset filter
    $('#reset-filter').click(function() {
        if (!$(this).hasClass('inactive')) {
            $('#category-select').val(''); // Reset select box
            category = ''; // Reset category
            loadMorePosts(true); // Reset page count and load unfiltered posts
            toggleResetButton(); // Toggle reset button state
            updateHash(); // Update the URL hash to remove category
        }
    });

    // Load posts initially with the category from the hash, if any
    loadMorePosts(true);
});

</script>
