<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>

<?php
$is_front = is_front_page();
$enable_topbar = false; // Default value for top bar
$enable_header_search = false;
$banner_type = ''; // Default value for banner type

// Check if ACF is installed and activated
if (class_exists('ACF')) {
    $header = get_field('header', 'option');
    $enable_header_search = !empty($header['enable_header_search']);
    $enable_topbar = isset($header['enable_topbar']) ? $header['enable_topbar'] : false; // Set default to false
    $banner_type = get_field('banner_type', get_the_ID());
}
?>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

    <a href="#content" class="skip-link">Skip to content</a>

    <?php
    // Enable top bar and set up widgets if enabled
    if ($enable_topbar):
    ?>
        <div id="topbar">
            <div class="wrap">
                <?php
                if (isset($header['topbar_column_count'])) {
                    for ($i = 1; $i <= $header['topbar_column_count']; $i++) {
                        dynamic_sidebar("topbar-$i");
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <header class="header has-global-padding">
        <div class="wrap">
            <?php if ($is_front): ?>
                <h1 class="branding">
                <?php else: ?>
                    <div class="branding">
                    <?php endif; ?>

                    <a href="<?= esc_url(home_url('/')); ?>" rel="home" title="<?php bloginfo('name'); ?>" aria-label="<?php bloginfo('name'); ?> homepage">
                        <?= file_get_contents(get_stylesheet_directory() . '/assets/images/theme/logo.svg'); ?>
                        <span class="screen-reader-text"><?php bloginfo('name'); ?></span>
                    </a>

                    <?php if ($is_front): ?>
                </h1>
            <?php else: ?>
        </div>
    <?php endif; ?>

    <?php if (has_nav_menu('primary')): ?>
        <button id="nav-expander" class="nav-expander">
            <span class="screen-reader-text">Menu</span>
            <span class="bar"></span>
        </button>

        <nav id="sitenav" class="sitenav">
            <?php
            // Check if ACF is active before using its fields
            if (class_exists('ACF')) {
                $header_options = get_field('header', 'option');
                $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];
            } else {
                $enable_mega_menu = false;
            }

            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'after' => '',
                'menu_class' => $enable_mega_menu ? 'menu has-megamenu' : 'menu',
                'walker' => new Mega_Menu_Walker()
            ]);
            ?>

        </nav>
    <?php endif; ?>

    <?php
    if ($enable_header_search) {
        $search_icon = file_get_contents(get_stylesheet_directory() . '/assets/images/theme/icon-search.svg');

        $search_item = '
                        <div class="menu-item-search">
                            <button class="search-toggle" aria-label="Open search popup">
                                <span class="screen-reader-text">Search</span>' .
            $search_icon .
            '</button>
                        </div>';

        echo $search_item;
    }
    ?>

    <?php //if ($enable_header_search): 
    ?>
    <!-- <div id="header-search-popup" class="search-popup">
                    <button class="search-close" aria-label="Close search popup">&times;</button>
                    <div class="search-popup-inner">
                        <h2>Search the site</h2>
                        <?php //get_search_form(); 
                        ?>
                    </div>
                </div> -->
    <?php //endif; 
    ?>

    </div>
    </header>

    <div class="curtain curtain-1"></div>
    <div class="curtain-overlay curtain-2"></div>

    <main>
        <?php
        // Determine the banner type and template
        if (is_404() || is_search() || is_archive()) {
            // 404, search, and archive pages
            $banner_type     = 'static';
            $banner_template = 'modules/banners/templates/hero-static.php';
        } elseif (is_single()) {
            // Single post/page has its own template
            $banner_type     = 'single';
            $banner_template = 'modules/banners/templates/hero-single.php';
        } else {
            // Only attempt to get the banner type if ACF is available
            if (class_exists('ACF')) {
                $banner_type = get_field('banner_type', get_the_ID());
            }

            if (isset($banner_type) && $banner_type !== 'none') {
                switch ($banner_type) {
                    case 'static':
                        $banner_template = 'modules/banners/templates/hero-static.php';
                        break;
                    case 'video':
                        $banner_template = 'modules/banners/templates/hero-video.php';
                        break;
                    case 'slideshow':
                        $banner_template = 'modules/banners/templates/hero-slideshow.php';
                        break;
                    default:
                        $banner_template = '';
                        break;
                }
            }
        }

        // Include the banner template if it's set and exists
        if (isset($banner_template) && $banner_template && file_exists(get_template_directory() . '/' . $banner_template)) {
            $hero_class = 'hero hero-' . $banner_type;
            if (is_front_page()) {
                $hero_class .= ' home';
            }

            // Get override background options from static_content
            $override_bg_colour = null;
            $override_overlay_size = null;
            $override_overlay = null;
            if (class_exists('ACF')) {
                $banner_content = get_field('static_content', get_the_ID());
                $override_bg_colour = $banner_content['override_bg_colour'] ?? null;
                $override_overlay_size = $banner_content['override_overlay_size'] ?? null;
                $override_overlay = $banner_content['override_overlay'] ?? null;
            }

            if ($override_overlay) {
                $hero_class .= ' overlay-' . sanitize_html_class($override_overlay);
            }

            // Build inline style for hero wrapper
            $hero_style = '';
            if ($override_bg_colour) {
                $hero_style .= 'background-color:' . esc_attr($override_bg_colour) . ';';
            }
            if ($override_overlay_size) {
                $hero_style .= 'background-size:' . esc_attr($override_overlay_size) . ';';
            }

            $hero_attr = $hero_style ? ' style="' . esc_attr($hero_style) . '"' : '';

            echo '<div class="' . esc_attr($hero_class) . '"' . $hero_attr . '>';
            require get_template_directory() . '/' . $banner_template;
            echo '</div>';
        } else {
            echo '<!-- Banner template not found or no banner displayed -->';
        }

        ?>