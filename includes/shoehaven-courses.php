<?php
// Register a custom post type for courses
add_action('init', 'register_course_post_type');
function register_course_post_type()
{
    $labels = [
        'name' => __('Courses', 'textdomain'),
        'singular_name' => __('Course', 'textdomain'),
        'menu_name' => __('Shoe courses', 'textdomain'),
        'name_admin_bar' => __('Course', 'textdomain'),
        'add_new' => __('Add New', 'textdomain'),
        'add_new_item' => __('Add New Course', 'textdomain'),
        'new_item' => __('New Course', 'textdomain'),
        'edit_item' => __('Edit Course', 'textdomain'),
        'view_item' => __('View Course', 'textdomain'),
        'all_items' => __('All Courses', 'textdomain'),
        'search_items' => __('Search Courses', 'textdomain'),
        'parent_item_colon' => __('Parent Courses:', 'textdomain'),
        'not_found' => __('No courses found.', 'textdomain'),
        'not_found_in_trash' => __('No courses found in Trash.', 'textdomain'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'course'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'comments'],
        'menu_icon' => 'dashicons-welcome-learn-more',
    ];

    register_post_type('course', $args);
}


// Register a custom taxonomy for course categories
add_action('init', 'register_course_taxonomy');
function register_course_taxonomy()
{
    $labels = [
        'name' => __('Course Categories', 'textdomain'),
        'singular_name' => __('Course Category', 'textdomain'),
        'search_items' => __('Search Course Categories', 'textdomain'),
        'all_items' => __('All Course Categories', 'textdomain'),
        'parent_item' => __('Parent Course Category', 'textdomain'),
        'parent_item_colon' => __('Parent Course Category:', 'textdomain'),
        'edit_item' => __('Edit Course Category', 'textdomain'),
        'update_item' => __('Update Course Category', 'textdomain'),
        'add_new_item' => __('Add New Course Category', 'textdomain'),
        'new_item_name' => __('New Course Category Name', 'textdomain'),
        'menu_name' => __('Course Categories', 'textdomain'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'course-category'],
    ];

    register_taxonomy('course_category', ['course'], $args);
}


?>