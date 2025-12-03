<?php
if (!defined('ABSPATH')) {
    exit;
}

// ------------------------------------------
// WRAP AROUND VIDEOS
// ------------------------------------------
function lmn_embed_oembed_html($html, $url, $attr, $post_id) {
    return '<div class="video-wrapper">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'lmn_embed_oembed_html', 99, 4);

// ------------------------------------------
// REMOVE EMOJIS
// ------------------------------------------
function lmn_disable_emojicons_tinymce($plugins) {
    return is_array($plugins) ? array_diff($plugins, array('wpemoji')) : array();
}

function lmn_disable_wp_emojicons() {
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    add_filter('tiny_mce_plugins', 'lmn_disable_emojicons_tinymce');
    add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'lmn_disable_wp_emojicons');

// ------------------------------------------
// REMOVE RECENT COMMENTS
// ------------------------------------------
function lmn_remove_recent_comments_style() {
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
        remove_action('wp_head', array(
            $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
            'recent_comments_style'
        ));
    }
}
add_action('widgets_init', 'lmn_remove_recent_comments_style');

// ------------------------------------------
// MOVE YOAST TO BOTTOM
// ------------------------------------------
function lmn_yoast_to_bottom() {
    return 'low';
}
add_filter('wpseo_metabox_prio', 'lmn_yoast_to_bottom');

// ------------------------------------------
// AUTO ALT TAG ON UPLOAD
// ------------------------------------------
function lmn_auto_alt($post_ID) {
    if (wp_attachment_is_image($post_ID)) {
        $my_image_title = get_post($post_ID)->post_title;
        $my_image_title = preg_replace('%\s*[-_\s]+\s*%', ' ', $my_image_title);
        $my_image_title = ucwords(strtolower($my_image_title));
        $my_image_meta = array(
            'ID' => $post_ID,
            'post_title' => $my_image_title,
        );
        update_post_meta($post_ID, '_wp_attachment_image_alt', $my_image_title);
        wp_update_post($my_image_meta);
    }
}
add_action('add_attachment', 'lmn_auto_alt');

// ------------------------------------------
// ADD THEME PALETTE TO ACF COLOUR PICKER
// ------------------------------------------
function lmn_acf_theme_colours() { ?>
    <script type="text/javascript">
    (function() {
        acf.add_filter('color_picker_args', function(args, field) {
            const settings = wp.data.select("core/editor").getEditorSettings();
            let colors = settings.colors.map(function(x) {
                return x.color;
            });
            args.palettes = colors;
            return args;
        });
    })();
    </script>
<?php }
add_action('acf/input/admin_footer', 'lmn_acf_theme_colours');

// ------------------------------------------
// DISABLE FULLSCREEN GUTENBERG - optional
// ------------------------------------------
function lmn_disable_editor_fullscreen_by_default() {
    $script = "window.onload = function() { const isFullscreenMode = wp.data.select('core/edit-post').isFeatureActive('fullscreenMode'); if (isFullscreenMode) { wp.data.dispatch('core/edit-post').toggleFeature('fullscreenMode'); } }";
    wp_add_inline_script('wp-blocks', $script);
}
add_action('enqueue_block_editor_assets', 'lmn_disable_editor_fullscreen_by_default');

// ------------------------------------------
// DEFAULT EXCERPT LENGTH
// ------------------------------------------
function lmn_custom_excerpt_length($length) {
    return 60;
}
add_filter('excerpt_length', 'lmn_custom_excerpt_length', 999);

// ------------------------------------------
// CUSTOM EXCERPT LENGTH
// Usage: echo lmn_excerpt(45);
// ------------------------------------------
function lmn_excerpt($limit) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
    }
    $excerpt = implode(" ", $excerpt) . '...';
    return preg_replace('/\[[^\]]*\]/', '', $excerpt);
}

// ------------------------------------------
// CHECK FOR SUBPAGES
// ------------------------------------------
function lmn_is_child($pageID) {
    global $post;
    return is_page() && $post->post_parent == $pageID;
}

// ------------------------------------------
// PARENT PAGE ID
// ------------------------------------------
function lmn_get_top_parent_page_id() {
    global $post;
    if ($post->ancestors) {
        return end($post->ancestors);
    } else {
        return $post->ID;
    }
}

// ------------------------------------------
// ALTERNATIVE TO FILE_GET_CONTENTS
// ------------------------------------------
function lmn_url_get_contents($url) {
    if (!function_exists('curl_init')) {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// ------------------------------------------
// GET POST LEVELS
// ------------------------------------------
function lmn_get_post_levels($post) {
    $ancestors = ['levels' => 1, 'level_1' => $post];

    if ($post->post_parent) {
        $parent = get_post($post->post_parent);
        $parents_parent = get_post($parent->post_parent);
        $parent_of_parents_parent = get_post($parents_parent->post_parent);

        if (!$parent->post_parent) {
            $ancestors = [
                'levels' => 2,
                'level_1' => $parent,
                'level_2' => $post,
                'parent' => $parent
            ];
        } elseif (!$parents_parent->post_parent) {
            $ancestors = [
                'levels' => 3,
                'level_1' => $parents_parent,
                'level_2' => $parent,
                'level_3' => $post,
                'parent' => $parent,
                'grandparent' => $parents_parent
            ];
        } elseif (!$parent_of_parents_parent->post_parent) {
            $ancestors = [
                'levels' => 4,
                'level_1' => $parent_of_parents_parent,
                'level_2' => $parents_parent,
                'level_3' => $parent,
                'level_4' => $post,
                'parent' => $parent,
                'grandparent' => $parents_parent,
                'greatgrandparent' => $parent_of_parents_parent
            ];
        }
    }

    return $ancestors;
}

// ------------------------------------------
// FIND POST ANCESTOR
// ------------------------------------------
function lmn_find_post_ancestor($post, $return = false) {
    $post_ancestor = $post;
    $level = 1;

    if ($post->post_parent) {
        $parent = get_post($post->post_parent);
        $parents_parent = get_post($parent->post_parent);
        $parent_of_parents_parent = get_post($parents_parent->post_parent);

        if (!$parent->post_parent) {
            $post_ancestor = $parent;
            $level = 2;
        } elseif (!$parents_parent->post_parent) {
            $post_ancestor = $parents_parent;
            $level = 3;
        } elseif (!$parent_of_parents_parent->post_parent) {
            $post_ancestor = $parent_of_parents_parent;
            $level = 4;
        }
    }

    return $return === 'level' ? $level : $post_ancestor;
}

// ------------------------------------------
// CHECK IF CURRENT PAGE IS PARENT
// ------------------------------------------
function lmn_current_is_parent() {
    global $post;
    $children = get_pages(['child_of' => $post->ID]);
    return count($children) > 0;
}

// ------------------------------------------
// SLUGIFY A STRING
// ------------------------------------------
function lmn_slugify($string) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}

# ------------------------------------------
# LOAD BLOCK CSS FILES SEPARATELY
# ------------------------------------------
add_filter( 'should_load_separate_core_block_assets', '__return_false' );


# -------------------------------------------------------
# GET YOUTUBE OR VIMEO VIDEO INFO FROM LINK
# -------------------------------------------------------
function get_video_info($url) {
    $iframe = null;
    $video_src = null;
    $youtube_pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{10,12})/';
    $vimeo_pattern = '/^(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|)(\d+)/';

    if (preg_match($youtube_pattern, $url, $matches)) {
        $video_id = $matches[1];
        $video_type = 'youtube';
    } elseif (preg_match($vimeo_pattern, $url, $matches)) {
        $video_id = $matches[2];
        $video_type = 'vimeo';
    } else {
        return false;
    }

    if ($video_type == 'youtube') {
        $video_src = 'https://www.youtube.com/embed/' . $video_id . '?enablejsapi=1&controls=0&fs=0&iv_load_policy=3&rel=0&showinfo=0&loop=1&playlist=' . $video_id . '&start=1&mute=1&autoplay=1';
    } elseif ($video_type == 'vimeo') {
        $video_src = 'https://player.vimeo.com/video/' . $video_id . '?api=1&byline=0&quality=1080p&portrait=0&title=0&background=1&muted=1&loop=1&autoplay=1&autopause=0&id=' . $video_id;
    }

    return array('video_id' => $video_id, 'video_type' => $video_type, 'video_src' => $video_src);
}

// ------------------------------------------
// diable GF css - optional
// ------------------------------------------
#add_filter( 'gform_disable_css', '__return_true' );

// ------------------------------------------
// PASSWORD PROTECTED CUSTOM THEME commented out - optional
// ------------------------------------------
// function lmn_password_protected_theme_file($file) {
//     return get_stylesheet_directory() . '/modules/plugins/branded-login/branded-login.php';
// }
// add_filter('password_protected_theme_file', 'lmn_password_protected_theme_file');

// ------------------------------------------
// Change date format in admin post type columns
// ------------------------------------------
add_filter('post_date_column_time', 'custom_admin_post_date_time_format', 10, 4);
function custom_admin_post_date_time_format($t_time, $post, $column_name, $mode) {
    return get_the_time('d M Y H:i', $post);
}

// ------------------------------------------
// Add underline option
// ------------------------------------------
function add_underline() {
    wp_enqueue_script(
        'lm8-underline',
        get_template_directory_uri() . '/assets/js/underline-format.js',
        array( 'wp-rich-text', 'wp-editor', 'wp-element', 'wp-components', 'wp-block-editor' ),
        filemtime( get_template_directory() . '/assets/js/underline-format.js' ), // cache-bust
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'add_underline' );

// ------------------------------------------
// Get meta data from S3 URL
// ------------------------------------------
function get_offloaded_file_info($attachment_id) {
    if (empty($attachment_id)) {
        return ['ext' => 'N/A', 'size' => 'N/A'];
    }

    $file_url  = wp_get_attachment_url($attachment_id);
    $file_type = wp_check_filetype($file_url);
    $file_ext  = $file_type['ext'] ?? 'Unknown';

    // Try metadata first
    $meta = wp_get_attachment_metadata($attachment_id);
    $size_bytes = $meta['filesize'] ?? null;

    // Try WP Offload Media Pro helper if available
    if (!$size_bytes && function_exists('as3cf_get_attachment_filesize')) {
        $size_bytes = as3cf_get_attachment_filesize($attachment_id);
    }

    // Convert to MB with 2 decimal places
    if ($size_bytes) {
        $size_mb = round($size_bytes / (1024 * 1024), 2);
    } else {
        $size_mb = 'N/A';
    }

    return [
        'ext'  => $file_ext,
        'size' => is_numeric($size_mb) ? "{$size_mb} mb" : $size_mb,
    ];
}


// ------------------------------------------
// Get image meta data image
// ------------------------------------------
function add_custom_image_field($form_fields, $post) {
    $form_fields['custom_url'] = array(
        'label' => 'Custom URL',
        'input' => 'text',
        'value' => get_post_meta($post->ID, '_custom_url', true),
        'helps' => 'Add a URL associated with this image.'
    );
    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'add_custom_image_field', 10, 2);

function save_custom_image_field($post, $attachment) {
    if (isset($attachment['custom_url'])) {
        update_post_meta($post['ID'], '_custom_url', esc_url($attachment['custom_url']));
    }
    return $post;
}
add_filter('attachment_fields_to_save', 'save_custom_image_field', 10, 2);


// ------------------------------------------
// Allow xhtml
// ------------------------------------------
add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    if (preg_match('/\.xhtml$/i', $filename)) {
        $data['ext']  = 'xhtml';
        $data['type'] = 'application/xhtml+xml';
    }
    return $data;
}, 10, 4);

add_filter('upload_mimes', function($mimes) {
    $mimes['xhtml'] = 'application/xhtml+xml';
    return $mimes;
});


// ------------------------------------------
// Change permalink for post
// ------------------------------------------
function rv_post_type_news_permalink( $permalink, $post ) {
    if ( $post->post_type === 'post' ) {
        return home_url( '/news/' . $post->post_name . '/' );
    }
    return $permalink;
}
add_filter( 'post_type_link', 'rv_post_type_news_permalink', 10, 2 );

function rv_rewrite_rules() {
    add_rewrite_rule(
        '^news/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );
}
add_action( 'init', 'rv_rewrite_rules' );
