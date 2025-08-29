<?php

// ------------------------------------------
// ADD EXTERNAL LINK METABOX
// ------------------------------------------
function add_external_link_meta_field() {
    add_meta_box(
        'external_link_meta_box',
        'External Link',
        'external_link_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_external_link_meta_field' );

function external_link_meta_box_callback( $post ) {
    $external_link = get_post_meta( $post->ID, 'external_link', true );
    echo '<label for="external_link" class="screen-reader-text">External Link: </label>';
    echo '<input type="text" id="external_link" name="external_link" value="' . esc_url( $external_link ) . '" size="100" />';
}

function save_external_link_meta_field( $post_id ) {
    if ( array_key_exists( 'external_link', $_POST ) ) {
        update_post_meta(
            $post_id,
            'external_link',
            esc_url_raw( $_POST['external_link'] )
        );
    }
}
add_action( 'save_post', 'save_external_link_meta_field' );


// ---------------------------
// ajax load more posts
// ---------------------------
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
function load_more_posts() {
    
    $paged = intval($_POST['page']);
    $posts_per_page = intval($_POST['posts_per_page']);
    $post_type = $_POST['post_type'];
    $category = sanitize_text_field($_POST['category']); // Get and sanitize the category

    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'offset' => ($paged - 1) * $posts_per_page,
        'posts_per_page' => $posts_per_page,
        'category_name' => $category, // Include category in the query arguments
    );

    $the_query = new WP_Query($args);
    $output = '';
    if ($the_query->have_posts()) : 
        while ($the_query->have_posts()) : $the_query->the_post();
            ob_start(); 
            get_template_part('parts/part', 'excerpt');
            $output .= ob_get_clean();
        endwhile;
    endif;

    $data = array(
        'posts' => $output,
        'has_more' => $the_query->max_num_pages >= $paged + 1
    );

    echo json_encode($data);
    wp_die();
}


// ------------------------------------------
// AJAX callback: load all team members, with first_name + last_name + HTML
// ------------------------------------------
add_action('wp_ajax_load_team_members','load_team_members_callback');
add_action('wp_ajax_nopriv_load_team_members','load_team_members_callback');

function load_team_members_callback() {

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $args = [
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
    ];

    if ($category) {
        $args['tax_query'] = [[
            'taxonomy' => 'team-category',
            'field'    => 'term_id',
            'terms'    => intval($category),
        ]];
    }

    $query = new WP_Query($args);
    
    $members = [];

    while ($query->have_posts()) {
        $query->the_post();

        $title = get_the_title();
        $first_name = strtok($title, ' ');
        $last_name  = trim(substr($title, strlen($first_name)));

        $team_type_terms = get_the_terms(get_the_ID(), 'team-type');
        $team_type = $team_type_terms && !is_wp_error($team_type_terms) ? $team_type_terms[0]->name : '';

        ob_start(); ?>
        <div class="team__member">
          <div class="profile">
            <div class="image">
              <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('team-member'); ?>
              <?php else: ?>
                <img src="<?= esc_url(get_template_directory_uri().'/assets/images/theme/profile.jpg'); ?>"
                     alt="<?= esc_attr($title); ?>">
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
                    $tel_link = preg_replace('/[^0-9+]/','',$phone); ?>
                    <li><a class="phone" href="tel:<?= esc_attr($tel_link) ?>"
                           rel="noopener noreferrer">Call <?= esc_html($phone) ?></a></li>
                  <?php endif; ?>
                  <?php if ($linkedin): ?>
                    <?php $fn = sanitize_text_field($first_name); ?>
                    <li><a class="li" href="<?= esc_url($linkedin) ?>"
                           rel="noopener noreferrer">View <?= esc_html($fn) ?> on LinkedIn</a></li>
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
            'team_type'  => $team_type,
            'html'       => $html,
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
function load_documents_callback() {
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
            <a class="file document-card <?= esc_attr($term_class); ?>" href="<?= esc_url(get_permalink()); ?>" target="_blank" title="<?= esc_attr(get_the_title()); ?>">
                <span class="date"><?= esc_html($post_date); ?></span>
                <span class="title">
                    <span class="name"><?= esc_html(get_the_title()); ?></span>
                </span>
                <span class="icon">
                    <?= file_get_contents(get_template_directory() . '/assets/images/theme/icon-announcement.svg'); ?>
                    Download PDF
                </span>
                <span class="format"><?= esc_html($file_format); ?></span>
                <?php /*<span class="size"><?= esc_html($file_size); ?></span>*/ ?>
            </a>
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