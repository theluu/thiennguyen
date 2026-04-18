<?php
/**
 * Registers content types and taxonomies.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_register_content_types(): void
{
    $supports = ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'];

    register_post_type('team', [
        'labels' => [
            'name' => __('Đội ngũ', 'haritics'),
            'singular_name' => __('Thành viên đội ngũ', 'haritics'),
        ],
        'public' => true,
        'has_archive' => haritics_route_path('team'),
        'rewrite' => ['slug' => haritics_route_path('team')],
        'menu_icon' => 'dashicons-groups',
        'supports' => $supports,
        'show_in_rest' => true,
    ]);

    register_post_type('project', [
        'labels' => [
            'name' => __('Dự án', 'haritics'),
            'singular_name' => __('Dự án', 'haritics'),
        ],
        'public' => true,
        'has_archive' => haritics_route_path('project'),
        'rewrite' => ['slug' => haritics_route_path('project')],
        'menu_icon' => 'dashicons-portfolio',
        'supports' => $supports,
        'show_in_rest' => true,
    ]);

    register_post_type('donation', [
        'labels' => [
            'name' => __('Mạnh thường quân', 'haritics'),
            'singular_name' => __('Mạnh thường quân', 'haritics'),
        ],
        'public' => true,
        'has_archive' => haritics_route_path('donation'),
        'rewrite' => ['slug' => haritics_route_path('donation')],
        'menu_icon' => 'dashicons-money-alt',
        'supports' => $supports,
        'show_in_rest' => true,
    ]);

    register_post_type('event', [
        'labels' => [
            'name' => __('Sự kiện', 'haritics'),
            'singular_name' => __('Sự kiện', 'haritics'),
        ],
        'public' => true,
        'has_archive' => haritics_route_path('event'),
        'rewrite' => ['slug' => haritics_route_path('event')],
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => $supports,
        'show_in_rest' => true,
    ]);

    register_post_type('haritics_message', [
        'labels' => [
            'name' => __('Tin nhắn liên hệ', 'haritics'),
            'singular_name' => __('Tin nhắn liên hệ', 'haritics'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title', 'editor'],
    ]);

    register_post_type('haritics_apply', [
        'labels' => [
            'name' => __('Ứng tuyển dự án', 'haritics'),
            'singular_name' => __('Đơn ứng tuyển', 'haritics'),
            'add_new_item' => __('Thêm đơn ứng tuyển', 'haritics'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-clipboard',
        'supports' => ['title', 'editor'],
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);

    register_post_type('haritics_contribute', [
        'labels' => [
            'name' => __('Muốn đóng góp', 'haritics'),
            'singular_name' => __('Đăng ký đóng góp', 'haritics'),
            'add_new_item' => __('Thêm đăng ký', 'haritics'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-heart',
        'supports' => ['title', 'editor'],
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);

    register_taxonomy('project_category', ['project'], [
        'label' => __('Danh mục dự án', 'haritics'),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => haritics_route_path('project_category')],
    ]);

    register_taxonomy('event_category', ['event'], [
        'label' => __('Danh mục sự kiện', 'haritics'),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => haritics_route_path('event_category')],
    ]);

    register_taxonomy('donation_category', ['donation'], [
        'label' => __('Danh mục mạnh thường quân', 'haritics'),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => haritics_route_path('donation_category')],
    ]);
}
add_action('init', 'haritics_register_content_types');
