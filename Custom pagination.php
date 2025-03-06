<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	if (function_exists('etheme_child_styles')){
		etheme_child_styles();
	}
}

//Custom Pagination

function custom_advanced_pagination() {
    $pages = array(
        28886 => "1",
        28972 => "2",
        28977 => "3",
        29208 => "4",
        28998 => "5",
        29003 => "6",
        29008 => "7",
        29013 => "8",
        29018 => "9",
		29293 => "10",
    );

    $current_page_id = get_queried_object_id();
    $page_ids = array_keys($pages);
    $current_index = array_search($current_page_id, $page_ids);
    $total_pages = count($pages);

    echo '<div class="custom-pagination">';

    // Previous Button
    if ($current_index > 0) {
        $prev_id = $page_ids[$current_index - 1];
        echo '<a class="page-item prev" href="' . get_permalink($prev_id) . '">«</a>';
    }

    if (wp_is_mobile()) {
        // Mobile View
        echo '<a class="page-item" href="' . get_permalink(28886) . '">1</a>'; // Static first page
        echo '<span class="dots">...</span>'; // Separator

        // Determine remaining pages
        $left_end = max(1, $current_index - 1); // Remaining pages on left of dynamic section
        $right_start = min($current_index + 1, $total_pages - 2); // Dynamic pages start from here
        $right_end = min($total_pages - 1, $right_start + 2); // Show next 2 pages

        // Show remaining pages before the separator
        for ($i = 1; $i < $left_end; $i++) {
            $id = $page_ids[$i];
            echo '<a class="page-item" href="' . get_permalink($id) . '">' . $pages[$id] . '</a>';
        }

        // Show live page
        echo '<span class="page-item current">' . $pages[$current_page_id] . '</span>';

        // Show next 2 pages after the live page
        for ($i = $right_start; $i <= $right_end; $i++) {
            if ($i < $total_pages) {
                $id = $page_ids[$i];
                echo '<a class="page-item" href="' . get_permalink($id) . '">' . $pages[$id] . '</a>';
            }
        }
    } else {
        // Desktop View: Keep original pagination
        foreach ([28886, 28972] as $id) {
            echo ($id == $current_page_id) 
                ? '<span class="page-item current">' . $pages[$id] . '</span>' 
                : '<a class="page-item" href="' . get_permalink($id) . '">' . $pages[$id] . '</a>';
        }

        echo '<span class="dots">...</span>'; // Separator

        $start = max(2, min($total_pages - 5, $current_index - 2));
        $end = min($total_pages - 1, $start + 4);

        for ($i = $start; $i <= $end; $i++) {
            $id = $page_ids[$i];
            echo ($id == $current_page_id) 
                ? '<span class="page-item current">' . $pages[$id] . '</span>' 
                : '<a class="page-item" href="' . get_permalink($id) . '">' . $pages[$id] . '</a>';
        }
    }

    // Next Button
    if ($current_index < count($page_ids) - 1) {
        $next_id = $page_ids[$current_index + 1];
        echo '<a class="page-item next" href="' . get_permalink($next_id) . '">»</a>';
    }

    echo '</div>';
}


// Enable shortcode for Elementor
function advanced_pagination_shortcode() {
    ob_start();
    custom_advanced_pagination();
    return ob_get_clean();
}
add_shortcode('advanced_pagination', 'advanced_pagination_shortcode');
