<?php


function create_fishing_spots_cpt()
{
    $labels = array(
        'name' => __('Fishing Spots'),
        'singular_name' => __('Fishing Spot')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'rewrite' => array('slug' => 'fishing-spots'),
        'label' => __('Fishing Spots', 'text_domain'),
        'description' => __('Fishing Spots', 'text_domain'),
        'supports' => array('title', 'editor', 'excerpt', 'publicize', 'thumbnail', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes',),
        'taxonomies' => array('file-under'),
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-admin-post',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'show_in_rest' => true,
    );
    register_post_type('fishing spots', $args);
}
add_action('init', 'create_fishing_spots_cpt');