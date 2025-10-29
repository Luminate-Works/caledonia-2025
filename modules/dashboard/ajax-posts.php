<?php

// ------------------------------------------
// ADD EXTERNAL LINK METABOX
// ------------------------------------------
// function add_external_link_meta_field()
// {
//     add_meta_box(
//         'external_link_meta_box',
//         'External Link',
//         'external_link_meta_box_callback',
//         'post',
//         'normal',
//         'high'
//     );
// }
// add_action('add_meta_boxes', 'add_external_link_meta_field');

// function external_link_meta_box_callback($post)
// {
//     $external_link = get_post_meta($post->ID, 'external_link', true);
//     echo '<label for="external_link" class="screen-reader-text">External Link: </label>';
//     echo '<input type="text" id="external_link" name="external_link" value="' . esc_url($external_link) . '" size="100" />';
// }

// function save_external_link_meta_field($post_id)
// {
//     if (array_key_exists('external_link', $_POST)) {
//         update_post_meta(
//             $post_id,
//             'external_link',
//             esc_url_raw($_POST['external_link'])
//         );
//     }
// }
// add_action('save_post', 'save_external_link_meta_field');


// ------------------------------------------
// AJAX callback: load WP posts with filters
// ------------------------------------------
add_action('wp_ajax_load_wp_posts', 'load_wp_posts_callback');
add_action('wp_ajax_nopriv_load_wp_posts', 'load_wp_posts_callback');

function load_wp_posts_callback()
{
    $offset   = isset($_POST['offset'])         ? intval($_POST['offset']) : 0;
    $per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 5;
    $category = isset($_POST['category'])       ? sanitize_text_field($_POST['category']) : '';
    $tag      = isset($_POST['tag'])            ? sanitize_text_field($_POST['tag']) : '';
    $year     = isset($_POST['year'])           ? intval($_POST['year']) : '';
    $search   = !empty($_POST['search'])        ? sanitize_text_field($_POST['search']) : '';
    $limit_number = isset($_POST['limit_number']) ? intval($_POST['limit_number']) : 0;

    $tag_post_ids = [];

    // step 1: find posts with tag name matching search term
    if ($search) {
        $matching_tags = get_terms([
            'taxonomy'   => 'post_tag',
            'hide_empty' => false,
            'name__like' => $search,
        ]);

        if (!is_wp_error($matching_tags) && $matching_tags) {
            $tag_ids = wp_list_pluck($matching_tags, 'term_id');

            $tag_posts = get_posts([
                'post_type'   => 'post',
                'post_status' => 'publish',
                'fields'      => 'ids',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => $tag_ids,
                ]],
            ]);

            $tag_post_ids = $tag_posts;
        }
    }

    // step 2: build main query args
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit_number ? $limit_number : $per_page,
        'offset'         => $limit_number ? 0 : $offset,
    ];

    if ($category) {
        $args['category_name'] = $category;
    }

    if ($tag) {
        $args['tag'] = $tag;
    }

    if ($year) {
        $args['year'] = $year;
    }

    // step 3: combine post IDs from content match and tag name match
    // if ($search) {
    //     $content_ids = get_posts([
    //         'post_type'   => 'post',
    //         'post_status' => 'publish',
    //         'fields'      => 'ids',
    //         's'           => $search,
    //         'numberposts' => -1,
    //     ]);

    //     $args['post__in'] = array_unique(array_merge($content_ids, $tag_post_ids));
    // }

    if ($search) {
        $content_ids = get_posts([
            'post_type'   => 'post',
            'post_status' => 'publish',
            'fields'      => 'ids',
            's'           => $search,
            'numberposts' => -1,
        ]);

        $merged_ids = array_unique(array_merge($content_ids, $tag_post_ids));

        // If no matches, return immediately with 0 posts
        if (empty($merged_ids)) {
            wp_send_json([
                'posts' => [],
                'total' => 0,
            ]);
        }

        $args['post__in'] = $merged_ids;
    }

    // step 4: run query and output posts
    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('/parts/part', 'excerpt'); // Adjust this to match your template
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => ob_get_clean(),
            ];
        }
    }

    wp_reset_postdata();

    $total = $limit_number ? min($limit_number, $query->found_posts) : $query->found_posts;

    wp_send_json([
        'posts' => $posts,
        'total' => $total,
    ]);
}

// ------------------------------------------
// AJAX callback: load WP Insights with filters
// ------------------------------------------
add_action('wp_ajax_load_wp_insights', 'load_wp_insights_callback');
add_action('wp_ajax_nopriv_load_wp_insights', 'load_wp_insights_callback');

function load_wp_insights_callback()
{
    $offset   = isset($_POST['offset'])         ? intval($_POST['offset']) : 0;
    $per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 5;
    $category = isset($_POST['category'])       ? sanitize_text_field($_POST['category']) : '';
    $tag      = isset($_POST['tag'])            ? sanitize_text_field($_POST['tag']) : '';
    $year     = isset($_POST['year'])           ? intval($_POST['year']) : '';
    $search   = !empty($_POST['search'])        ? sanitize_text_field($_POST['search']) : '';
    $limit_number = isset($_POST['limit_number']) ? intval($_POST['limit_number']) : 0;

    $tag_post_ids = [];

    // step 1: find posts with tag name matching search term
    if ($search) {
        $matching_tags = get_terms([
            'taxonomy'   => 'post_tag',
            'hide_empty' => false,
            'name__like' => $search,
        ]);

        if (!is_wp_error($matching_tags) && $matching_tags) {
            $tag_ids = wp_list_pluck($matching_tags, 'term_id');

            $tag_posts = get_posts([
                'post_type'   => 'insights',
                'post_status' => 'publish',
                'fields'      => 'ids',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => $tag_ids,
                ]],
                'meta_key'       => 'featured',
                'meta_value'     => '0',
            ]);

            $tag_post_ids = $tag_posts;
        }
    }

    // step 2: build main query args
    $args = [
        'post_type'      => 'insights',
        'post_status'    => 'publish',
        'posts_per_page' => $limit_number ? $limit_number : $per_page,
        'offset'         => $limit_number ? 0 : $offset,
        'meta_key'       => 'featured',
        'meta_value'     => '0',
    ];

    if ($category) {
        $args['tax_query'][] = [
            'taxonomy' => 'insights-category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['category']),
        ];
    }

    if ($tag) {
        $args['tag'] = $tag;
    }

    if ($year) {
        $args['year'] = $year;
    }

    // step 3: combine post IDs from content match and tag name match
    // if ($search) {
    //     $content_ids = get_posts([
    //         'post_type'   => 'post',
    //         'post_status' => 'publish',
    //         'fields'      => 'ids',
    //         's'           => $search,
    //         'numberposts' => -1,
    //     ]);

    //     $args['post__in'] = array_unique(array_merge($content_ids, $tag_post_ids));
    // }

    if ($search) {
        $content_ids = get_posts([
            'post_type'   => 'insights',
            'post_status' => 'publish',
            'fields'      => 'ids',
            's'           => $search,
            'numberposts' => -1,
            'meta_key'       => 'featured',
            'meta_value'     => '0',
        ]);

        $merged_ids = array_unique(array_merge($content_ids, $tag_post_ids));

        // If no matches, return immediately with 0 posts
        if (empty($merged_ids)) {
            wp_send_json([
                'posts' => [],
                'total' => 0,
            ]);
        }

        $args['post__in'] = $merged_ids;
    }

    // step 4: run query and output posts
    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('/parts/part', 'excerpt-insights'); // Adjust this to match your template
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => ob_get_clean(),
            ];
        }
    }

    wp_reset_postdata();

    $total = $limit_number ? min($limit_number, $query->found_posts) : $query->found_posts;

    wp_send_json([
        'posts' => $posts,
        'total' => $total,
    ]);
}


// ------------------------------------------
// AJAX callback: load WP Research with filters
// ------------------------------------------
add_action('wp_ajax_load_wp_research', 'load_wp_research_callback');
add_action('wp_ajax_nopriv_load_wp_research', 'load_wp_research_callback');

function load_wp_research_callback()
{
    $offset   = isset($_POST['offset'])         ? intval($_POST['offset']) : 0;
    $per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 5;
    $category = isset($_POST['category'])       ? sanitize_text_field($_POST['category']) : '';
    $tag      = isset($_POST['tag'])            ? sanitize_text_field($_POST['tag']) : '';
    $year     = isset($_POST['year'])           ? intval($_POST['year']) : '';
    $search   = !empty($_POST['search'])        ? sanitize_text_field($_POST['search']) : '';
    $limit_number = isset($_POST['limit_number']) ? intval($_POST['limit_number']) : 0;

    $tag_post_ids = [];

    // step 1: find posts with tag name matching search term
    if ($search) {
        $matching_tags = get_terms([
            'taxonomy'   => 'external-research-category',
            'hide_empty' => false,
            'name__like' => $search,
        ]);

        if (!is_wp_error($matching_tags) && $matching_tags) {
            $tag_ids = wp_list_pluck($matching_tags, 'term_id');

            $tag_posts = get_posts([
                'post_type'   => 'external-research',
                'post_status' => 'publish',
                'fields'      => 'ids',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'external-research-category',
                    'field'    => 'term_id',
                    'terms'    => $tag_ids,
                ]],
            ]);

            $tag_post_ids = $tag_posts;
        }
    }

    // step 2: build main query args
    $args = [
        'post_type'      => 'external-research',
        'post_status'    => 'publish',
        'posts_per_page' => $limit_number ? $limit_number : $per_page,
        'offset'         => $limit_number ? 0 : $offset,
    ];

    if ($category) {
        $args['tax_query'][] = [
            'taxonomy' => 'external-research-category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['category']),
        ];
    }

    if ($tag) {
        $args['tag'] = $tag;
    }

    if ($year) {
        $args['year'] = $year;
    }

    // step 3: combine post IDs from content match and tag name match
    // if ($search) {
    //     $content_ids = get_posts([
    //         'post_type'   => 'post',
    //         'post_status' => 'publish',
    //         'fields'      => 'ids',
    //         's'           => $search,
    //         'numberposts' => -1,
    //     ]);

    //     $args['post__in'] = array_unique(array_merge($content_ids, $tag_post_ids));
    // }

    if ($search) {
        $content_ids = get_posts([
            'post_type'   => 'external-research',
            'post_status' => 'publish',
            'fields'      => 'ids',
            's'           => $search,
            'numberposts' => -1,
        ]);

        $merged_ids = array_unique(array_merge($content_ids, $tag_post_ids));

        // If no matches, return immediately with 0 posts
        if (empty($merged_ids)) {
            wp_send_json([
                'posts' => [],
                'total' => 0,
            ]);
        }

        $args['post__in'] = $merged_ids;
    }

    // step 4: run query and output posts
    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('/parts/part', 'excerpt-research'); // Adjust this to match your template
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => ob_get_clean(),
            ];
        }
    }

    wp_reset_postdata();

    $total = $limit_number ? min($limit_number, $query->found_posts) : $query->found_posts;

    wp_send_json([
        'posts' => $posts,
        'total' => $total,
    ]);
}


// ------------------------------------------
// AJAX callback: load WP press_coverage with filters
// ------------------------------------------
add_action('wp_ajax_load_wp_press_coverage', 'load_wp_press_coverage_callback');
add_action('wp_ajax_nopriv_load_wp_press_coverage', 'load_wp_press_coverage_callback');

function load_wp_press_coverage_callback()
{
    $offset   = isset($_POST['offset'])         ? intval($_POST['offset']) : 0;
    $per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;
    $category = isset($_POST['category'])       ? sanitize_text_field($_POST['category']) : '';
    $tag      = isset($_POST['tag'])            ? sanitize_text_field($_POST['tag']) : '';
    $year     = isset($_POST['year'])           ? intval($_POST['year']) : '';
    $search   = !empty($_POST['search'])        ? sanitize_text_field($_POST['search']) : '';
    $limit_number = isset($_POST['limit_number']) ? intval($_POST['limit_number']) : 0;

    $tag_post_ids = [];

    // step 1: find posts with tag name matching search term
    if ($search) {
        $matching_tags = get_terms([
            'taxonomy'   => 'post_tag',
            'hide_empty' => false,
            'name__like' => $search,
        ]);

        if (!is_wp_error($matching_tags) && $matching_tags) {
            $tag_ids = wp_list_pluck($matching_tags, 'term_id');

            $tag_posts = get_posts([
                'post_type'   => 'press-coverage',
                'post_status' => 'publish',
                'fields'      => 'ids',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => $tag_ids,
                ]]
            ]);

            $tag_post_ids = $tag_posts;
        }
    }

    // step 2: build main query args
    $args = [
        'post_type'      => 'press-coverage',
        'post_status'    => 'publish',
        'posts_per_page' => $limit_number ? $limit_number : $per_page,
        'offset'         => $limit_number ? 0 : $offset
    ];

    if ($category) {

        $args['tax_query'][] = [
            'taxonomy' => 'press-coverage-category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['category']),
        ];
    }

    if ($tag) {
        $args['tag'] = $tag;
    }

    if ($year) {
        $args['year'] = $year;
    }

    // step 3: combine post IDs from content match and tag name match
    // if ($search) {
    //     $content_ids = get_posts([
    //         'post_type'   => 'post',
    //         'post_status' => 'publish',
    //         'fields'      => 'ids',
    //         's'           => $search,
    //         'numberposts' => -1,
    //     ]);

    //     $args['post__in'] = array_unique(array_merge($content_ids, $tag_post_ids));
    // }

    if ($search) {
        $content_ids = get_posts([
            'post_type'   => 'press-coverage',
            'post_status' => 'publish',
            'fields'      => 'ids',
            's'           => $search,
            'numberposts' => -1,
        ]);

        $merged_ids = array_unique(array_merge($content_ids, $tag_post_ids));

        // If no matches, return immediately with 0 posts
        if (empty($merged_ids)) {
            wp_send_json([
                'posts' => [],
                'total' => 0,
            ]);
        }

        $args['post__in'] = $merged_ids;
    }

    // step 4: run query and output posts
    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('/parts/part', 'excerpt-coverage'); // Adjust this to match your template
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => ob_get_clean(),
            ];
        }
    }

    wp_reset_postdata();

    $total = $limit_number ? min($limit_number, $query->found_posts) : $query->found_posts;

    wp_send_json([
        'posts' => $posts,
        'total' => $total,
    ]);
}


// ------------------------------------------
// AJAX callback: load all team members, with first_name + last_name + HTML
// ------------------------------------------
add_action('wp_ajax_load_team_members', 'load_team_members_callback');
add_action('wp_ajax_nopriv_load_team_members', 'load_team_members_callback');

function load_team_members_callback()
{

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $type     = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    $args = [
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
    ];

    // --- Taxonomy filtering ---
    $tax_query = [];

    if ($category) {
        $tax_query[] = [
            'taxonomy' => 'team-category',
            'field'    => 'term_id',
            'terms'    => intval($category),
        ];
    }

    if ($type) {
        $tax_query[] = [
            'taxonomy' => 'team-type',
            'field'    => 'name',
            'terms'    => $type,
        ];
    }

    if ($tax_query) {
        $args['tax_query'] = [
            'relation' => 'AND',
            ...$tax_query
        ];
    }

    $query = new WP_Query($args);
    $members = [];

    while ($query->have_posts()) {
        $query->the_post();

        $title = get_the_title();
        $first_name = strtok($title, ' ');
        $last_name  = trim(substr($title, strlen($first_name)));

        $team_type_terms = get_the_terms(get_the_ID(), 'team-type');
        $team_types = $team_type_terms && !is_wp_error($team_type_terms)
            ? wp_list_pluck($team_type_terms, 'name')
            : [];

        ob_start(); ?>
        <div class="team__member">
            <div class="profile">
                <div class="image">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('team-member'); ?>
                    <?php else: ?>
                        <img src="<?= esc_url(get_template_directory_uri() . '/assets/images/theme/profile.jpg'); ?>"
                            alt="<?= esc_attr($title); ?>">
                    <?php endif; ?>
                </div>
                <div class="title">
                    <h3><?= esc_html($title); ?></h3>
                    <p class="meta e2"><?= esc_html(get_field('position')); ?></p>
                </div>
            </div>
            <div class="bio">
                <div class="bio-content">
                    <?php
                    $phone = get_field('contact_number');
                    $linkedin = get_field('linkedin_profile');
                    if ($phone || $linkedin): ?>
                        <ul class="contact-details">
                            <?php if ($phone):
                                $tel_link = preg_replace('/[^0-9+]/', '', $phone); ?>
                                <li><a class="phone" href="tel:<?= esc_attr($tel_link) ?>">Call <?= esc_html($phone) ?></a></li>
                            <?php endif; ?>
                            <?php if ($linkedin): ?>
                                <li><a class="li" href="<?= esc_url($linkedin) ?>">View <?= esc_html($first_name) ?> on LinkedIn</a></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php
        $html = ob_get_clean();

        $members[] = [
            'id'         => get_the_ID(),
            'title'      => $title,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'position'   => get_field('position'),
            'team_type'  => $team_types,
            'html'       => $html,
        ];
    }

    wp_reset_postdata();

    wp_send_json(['members' => $members]);
}


// ------------------------------------------
// AJAX callback: load all team members, with first_name + last_name + HTML
// ------------------------------------------
add_action('wp_ajax_load_team_media', 'load_team_media_callback');
add_action('wp_ajax_nopriv_load_team_media', 'load_team_media_callback');

function load_team_media_callback()
{
    $args = [
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
    ];

    $query = new WP_Query($args);

    $members = [];

    while ($query->have_posts()) {
        $query->the_post();

        $title = get_the_title();
        $first_name = strtok($title, ' ');
        $last_name  = trim(substr($title, strlen($first_name)));

        $cat_terms = get_the_terms(get_the_ID(), 'team-category');
        $team_category_id   = $cat_terms && !is_wp_error($cat_terms) ? $cat_terms[0]->term_id : '';
        $team_category_name = $cat_terms && !is_wp_error($cat_terms) ? $cat_terms[0]->name    : '';
        ob_start(); ?>
        <div class="team__member">
            <div class="profile">
                <div class="image">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('team-member'); ?>
                    <?php else: ?>
                        <img src="<?= esc_url(get_template_directory_uri() . '/assets/images/theme/profile.jpg'); ?>" alt="<?= esc_attr($title); ?>">
                    <?php endif; ?>
                </div>
                <div class="title">
                    <h3><?= esc_html($title); ?></h3>
                    <p class="meta"><?= esc_html(get_field('position')); ?></p>
                </div>
            </div>
            <div class="bio">
                <div class="bio-content">
                    <?php
                    $phone = get_field('contact_number');
                    $linkedin = get_field('linkedin_profile');
                    if ($phone || $linkedin):
                    ?>
                        <ul class="contact-details">
                            <?php if ($phone):
                                $tel_link = preg_replace('/[^0-9+]/', '', $phone); ?>
                                <li><a class="phone" href="tel:<?= esc_attr($tel_link) ?>" rel="noopener noreferrer">Call <?= esc_html($phone) ?></a></li>
                            <?php endif; ?>
                            <?php if ($linkedin): ?>
                                <li><a class="li" href="<?= esc_url($linkedin) ?>" rel="noopener noreferrer">View <?= esc_html($first_name) ?> on LinkedIn</a></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        $members[] = [
            'id'              => get_the_ID(),
            'title'           => $title,
            'first_name'      => $first_name,
            'last_name'       => $last_name,
            'position'        => get_field('position'),
            'member_category_id'   => $team_category_id,
            'member_category_name' => $team_category_name,
            'html'            => $html,
        ];
    }
    wp_reset_postdata();

    wp_send_json(['members' => $members]);
}

add_action('wp_ajax_load_documents', 'load_documents_callback');
add_action('wp_ajax_nopriv_load_documents', 'load_documents_callback');
// ------------------------------------------
// AJAX callback: load document posts with category and year filter
// ------------------------------------------
function load_documents_callback()
{
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 9;
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : '';
    $year = isset($_POST['year']) ? intval($_POST['year']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $allowed_terms = isset($_POST['allowed_terms']) ? json_decode(stripslashes($_POST['allowed_terms']), true) : [];

    if (!is_array($allowed_terms)) {
        $allowed_terms = [];
    }

    $args = [
        'post_type'      => 'documents',
        'posts_per_page' => $posts_per_page,
        'offset'         => $offset,
        'order'          => 'DESC',
        'orderby'        => 'date',
        's'              => $search,
        'tax_query'      => [],
    ];

    if (!empty($allowed_terms)) {
        $args['tax_query'][] = [
            'taxonomy' => 'document-type',
            'field'    => 'term_id',
            'terms'    => $allowed_terms,
            'operator' => 'IN',
        ];
    }

    if (!empty($filter)) {
        $args['tax_query'][] = [
            'taxonomy' => 'document-type',
            'field'    => 'slug',
            'terms'    => $filter,
        ];
    }

    if (!empty($year)) {
        $args['date_query'] = [
            [
                'year' => $year,
            ]
        ];
    }

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $terms = get_the_terms(get_the_ID(), 'document-type');
            $term_class = '';
            if (!empty($terms) && !is_wp_error($terms)) {
                $term = $terms[0];
                $term_class = sanitize_html_class($term->slug);
                $term_name = $term->name;
            }

            $post_date = get_the_date('j M Y');

            $file_url = get_field('file');
            $file_format = '';
            $file_size = '';

            if ($file_url) {
                // get file extension
                $file_format = strtoupper(pathinfo($file_url, PATHINFO_EXTENSION));

                // get attachment ID from URL
                $attachment_id = attachment_url_to_postid($file_url);
                if ($attachment_id) {
                    $file_path = get_attached_file($attachment_id);
                    if ($file_path && file_exists($file_path)) {
                        $size_in_bytes = filesize($file_path);
                        $file_size = size_format($size_in_bytes, 2);
                    }
                }
            }

            ob_start(); ?>
            <div class="file document-card <?= esc_attr($term_class); ?>">
                <span class="date"><?= esc_html($post_date); ?></span>
                <span class="title">
                    <span class="name"><?= esc_html(get_the_title()); ?></span>
                </span>

                <!-- Webcast link -->
                <?php
                $webcast_url = get_field('webcast');
                if ($webcast_url): ?>
                    <a class="icon glightbox" href="<?= esc_url($webcast_url['url']); ?>">
                        <?= file_get_contents(get_template_directory() . '/assets/images/theme/icon-video.svg'); ?>
                        <?= $webcast_url['title'] ? esc_html($webcast_url['title']) : 'Video'; ?>
                    </a>
                <?php endif; ?>

                <!-- Webcast link -->
                <?php
                $webcast_url2 = get_field('webcast_2');
                if ($webcast_url2): ?>
                    <a class="icon glightbox" href="<?= esc_url($webcast_url2['url']); ?>">
                        <?= file_get_contents(get_template_directory() . '/assets/images/theme/icon-video.svg'); ?>
                        <?= $webcast_url2['title'] ? esc_html($webcast_url2['title']) : 'Video'; ?>
                    </a>
                <?php endif; ?>

                <!-- Presentation link -->
                <?php
                $presentation_url = get_field('presentation');
                if ($presentation_url): ?>
                    <a class="icon" href="<?= esc_url($presentation_url); ?>" target="_blank" title="View Presentation">
                        <?= file_get_contents(get_template_directory() . '/assets/images/theme/icon-esef.svg'); ?>
                        Presentation slides
                    </a>
                <?php endif; ?>

                <!-- PDF link -->
                <?php
                $report_url = get_field('report');
                $link_url = $file_url ?: $report_url;

                if ($link_url): ?>
                    <a class="icon" href="<?= esc_url($link_url); ?>" target="_blank" title="<?= esc_attr(get_the_title()); ?>">
                        <?= file_get_contents(get_template_directory() . '/assets/images/theme/icon-announcement.svg'); ?>
                        PDF
                    </a>
                <?php endif; ?>

                <!-- ESEF link -->
                <?php
                $esef_url = get_field('esef');
                if ($esef_url): ?>
                    <a class="icon" href="<?= esc_url($esef_url['url']); ?>" target="_blank" title="View ESEF">
                        <?= file_get_contents(get_template_directory() . '/assets/images/theme/icon-esef.svg'); ?>
                        ESEF
                    </a>
                <?php endif; ?>

                <?php /*<span class="format"><?= esc_html($file_format); ?></span>*/ ?>
                <?php /*<span class="size"><?= esc_html($file_size); ?></span>*/ ?>
            </div>
<?php
            $html = ob_get_clean();
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => $html,
            ];
        }
    }
    wp_reset_postdata();

    wp_send_json(['posts' => $posts]);
}


add_action('wp_ajax_load_more_events', 'load_more_events');
add_action('wp_ajax_nopriv_load_more_events', 'load_more_events');

function load_more_events()
{
    check_ajax_referer('load_more_events', 'nonce');

    $exclude_raw = isset($_POST['exclude']) ? wp_unslash($_POST['exclude']) : '[]';
    $exclude = json_decode($exclude_raw, true);
    if (!is_array($exclude)) {
        $exclude = [];
    }
    // sanitize ids
    $exclude = array_map('intval', $exclude);
    $exclude = array_filter($exclude);

    $today  = date('Ymd');

    $args = [
        'post_type'      => 'calendar',
        'posts_per_page' => -1,
        'post__not_in'   => $exclude,
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => 'event_date',
                'compare' => '<',
                'value'   => $today,
            ],
        ],
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()): $query->the_post();
            set_query_var('calendar', 'historical');
            // uses the same partial
            get_template_part('parts/part', 'event');
        endwhile;
        wp_reset_postdata();
    endif;

    wp_die();
}
