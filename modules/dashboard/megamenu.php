<?php

class Mega_Menu_Walker extends Walker_Nav_Menu {

    // Start Level
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $header_options = get_field('header', 'option');
        $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];

        if ($enable_mega_menu && $depth === 0) {
            $output .= '<div class="megamenu child-menu"><div class="wrap">';

            if (!empty($this->current_item_featured_image) || !empty($this->current_item_description)) {
                $output .= '<div class="mm-info" style="background-image: url(' . $this->current_item_featured_image . ');">';
            }

            // Add the featured image if it exists
            // if (!empty($this->current_item_featured_image)) {
            //     $output .= sprintf('<img src="%s" alt="%s">', esc_url($this->current_item_featured_image), esc_attr($this->current_item_title));
            // }

            // Add the description if it exists
            if (!empty($this->current_item_description)) {
                $output .= '<div class="mm-description">';
                
                // Add parent page title with link
                if (!empty($this->current_item_parent_title) && !empty($this->current_item_parent_url)) {
                    $output .= sprintf('<h2 class="title"><a href="%s">%s</a></h2>', esc_url($this->current_item_parent_url), esc_html($this->current_item_parent_title));
                }
                
                $output .= sprintf('<p>%s</p></div>', esc_html($this->current_item_description));
            }

            if (!empty($this->current_item_featured_image) || !empty($this->current_item_description)) {
                $output .= '</div>'; // Close .mm-info
            }
        }

        $output .= '<div class="mm-menu">';
        $output .= '<ul class="sub-menu' . (!$enable_mega_menu && $depth === 0 ? ' child-menu' : '') . '">';
    }

    // End Level
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $header_options = get_field('header', 'option');
        $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];

        if ($enable_mega_menu && $depth === 0) {
            $output .= '</ul>';
            //$output .= dynamic_sidebar("mm-tool");
            $output .= '<div class="mega-menu-footer">';
            $output .= '<p class="mm-tools"><em>Share Price London</em> <iframe src="https://irtools.co.uk/tools/share_price/593aef1d-9aca-4401-9c50-93082eef8e6b"></iframe></p>'; 
            $output .= '<p class="mm-linkedin"><a href="https://www.linkedin.com/company/caledonia-investments/" target="_blank">LinkedIn</p>'; 
            $output .= '<p class="mm-external"><a href="https://caledoniaprivatecapital.com/" target="_blank">Caledonia Private Capital</p>'; 
            $output .= '</div>'; // Close .mm-menu
            
            $output .= '</div>'; // Close .mm-menu
            $output .= '</div></div>';
        } else {
            $output .= '</ul></div>';
        }
    }

    // Start Element
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $classes = implode(' ', $item->classes);

        // Open the <li> and <a> tags
        $output .= sprintf( '<li class="menu-item %s"><a href="%s">%s</a>',
            esc_attr($classes),
            esc_url( $item->url ),
            esc_html( $item->title )
        );

        // Add sub-toggle if the item has children
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= '<span class="sub-toggle"></span>';
        }

        // Store the description and featured image for use in start_lvl
        if ($depth === 0 && in_array('menu-item-has-children', $item->classes)) {
            // Fetch the featured image from the ACF field (assuming it's stored as an ID)
            $featured_image_id = get_field('featured_image', $item);
            if ($featured_image_id) {
                $this->current_item_featured_image = wp_get_attachment_url($featured_image_id);
            } else {
                $this->current_item_featured_image = '';
            }
            
            $this->current_item_description = $item->description;
            $this->current_item_title = $item->title; // Storing the title for alt text

            // Get parent page title and URL
            if ($item->menu_item_parent) {
                $parent_id = $item->menu_item_parent;
                $parent_item = get_post($parent_id);
                if ($parent_item) {
                    $this->current_item_parent_title = get_the_title($parent_item->ID);
                    $this->current_item_parent_url = get_permalink($parent_item->ID);
                }
            } else {
                $this->current_item_parent_title = esc_html($item->title);
                $this->current_item_parent_url = esc_url($item->url);
            }
        } else {
            $this->current_item_featured_image = '';
            $this->current_item_description = '';
            $this->current_item_title = '';
            $this->current_item_parent_title = '';
            $this->current_item_parent_url = '';
        }
    }

    // End Element
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= '</li>';
    }
}




/*

class Mega_Menu_Walker extends Walker_Nav_Menu {

    // Start Level
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $header_options = get_field('header', 'option');
        $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];

        if ($enable_mega_menu && $depth === 0) {
            $output .= '<div class="megamenu child-menu"><div class="wrap">';

            if (!empty($this->current_item_featured_image) || !empty($this->current_item_description)) {
                $output .= '<div class="mm-info">';
            }

            // Add the featured image if it exists
            if (!empty($this->current_item_featured_image)) {
                $output .= sprintf('<div class="mm-featured-image"><img src="%s" alt="%s"></div>', esc_url($this->current_item_featured_image), esc_attr($this->current_item_title));
            }

            // Add the description if it exists
            if (!empty($this->current_item_description)) {
                $output .= '<div class="mm-description">';
                
                // Add parent page title with link
                if (!empty($this->current_item_parent_title) && !empty($this->current_item_parent_url)) {
                    $output .= sprintf('<p class="title"><a href="%s">%s</a></p>', esc_url($this->current_item_parent_url), esc_html($this->current_item_parent_title));
                }
                
                $output .= sprintf('<p>%s</p></div>', esc_html($this->current_item_description));
            }

            if (!empty($this->current_item_featured_image) || !empty($this->current_item_description)) {
                $output .= '</div>'; // Close .mm-info
            }
        }

        $output .= '<ul class="sub-menu' . (!$enable_mega_menu && $depth === 0 ? ' child-menu' : '') . '">';
    }

    // End Level
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $header_options = get_field('header', 'option');
        $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];

        if ($enable_mega_menu && $depth === 0) {
            $output .= '</ul></div></div>';
        } else {
            $output .= '</ul>';
        }
    }

    // Start Element
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $classes = implode(' ', $item->classes);

        // Open the <li> and <a> tags
        $output .= sprintf( '<li class="menu-item %s"><a href="%s">%s</a>',
            esc_attr($classes),
            esc_url( $item->url ),
            esc_html( $item->title )
        );

        // Add sub-toggle if the item has children
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= '<span class="sub-toggle"></span>';
        }

        // Store the description and featured image for use in start_lvl
        if ($depth === 0 && in_array('menu-item-has-children', $item->classes)) {
            // Fetch the featured image from the ACF field (assuming it's stored as an ID)
            $featured_image_id = get_field('featured_image', $item);
            if ($featured_image_id) {
                $this->current_item_featured_image = wp_get_attachment_url($featured_image_id);
            } else {
                $this->current_item_featured_image = '';
            }
            
            $this->current_item_description = $item->description;
            $this->current_item_title = $item->title; // Storing the title for alt text

            // Get parent page title and URL
            if ($item->menu_item_parent) {
                $parent_id = $item->menu_item_parent;
                $parent_item = get_post($parent_id);
                if ($parent_item) {
                    $this->current_item_parent_title = get_the_title($parent_item->ID);
                    $this->current_item_parent_url = get_permalink($parent_item->ID);
                }
            } else {
                $this->current_item_parent_title = esc_html($item->title);
                $this->current_item_parent_url = esc_url($item->url);
            }
        } else {
            $this->current_item_featured_image = '';
            $this->current_item_description = '';
            $this->current_item_title = '';
            $this->current_item_parent_title = '';
            $this->current_item_parent_url = '';
        }
    }

    // End Element
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= '</li>';
    }
}

*/

