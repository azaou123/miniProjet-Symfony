<?php
/**
 * Plugin Name: Custom Category and Tag API
 * Description: A custom plugin to interact with WordPress REST API for creating and deleting categories and tags.
 * Version: 1.1
 * Author: Hada Ana
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register category creation route
function register_custom_category_creation_route() {
    register_rest_route('custom-api', '/create-category', array(
        'methods'  => 'POST',
        'callback' => 'handle_create_category',
        'permission_callback' => '__return_true', // Allow all requests (only for testing)
    ));
}
add_action('rest_api_init', 'register_custom_category_creation_route');

// Handle category creation
function handle_create_category(WP_REST_Request $request) {
    $name = sanitize_text_field($request->get_param('name'));
    $slug = sanitize_text_field($request->get_param('slug'));

    if (empty($name)) {
        return new WP_REST_Response(array('error' => 'Category name is required'), 400);
    }

    $category = wp_insert_term($name, 'category', array('slug' => $slug));

    if (is_wp_error($category)) {
        return new WP_REST_Response(array('error' => 'Failed to create category'), 500);
    }

    return new WP_REST_Response(array('message' => 'Category created successfully!', 'category_id' => $category['term_id']), 201);
}

// Register tag creation route
function register_custom_tag_creation_route() {
    register_rest_route('custom-api', '/create-tag', array(
        'methods'  => 'POST',
        'callback' => 'handle_create_tag',
        'permission_callback' => '__return_true', // Allow all requests (only for testing)
    ));
}
add_action('rest_api_init', 'register_custom_tag_creation_route');

// Handle tag creation
function handle_create_tag(WP_REST_Request $request) {
    $name = sanitize_text_field($request->get_param('name'));
    $slug = sanitize_text_field($request->get_param('slug'));

    if (empty($name)) {
        return new WP_REST_Response(array('error' => 'Tag name is required'), 400);
    }

    $tag = wp_insert_term($name, 'post_tag', array('slug' => $slug));

    if (is_wp_error($tag)) {
        return new WP_REST_Response(array('error' => 'Failed to create tag'), 500);
    }

    return new WP_REST_Response(array('message' => 'Tag created successfully!', 'tag_id' => $tag['term_id']), 201);
}

// Register category deletion route
function register_custom_category_deletion_route() {
    register_rest_route('custom-api', '/delete-category/(?P<id>\d+)', array(
        'methods'  => 'DELETE',
        'callback' => 'handle_delete_category',
        'permission_callback' => '__return_true', // Allow all requests (only for testing)
    ));
}
add_action('rest_api_init', 'register_custom_category_deletion_route');

// Handle category deletion
function handle_delete_category(WP_REST_Request $request) {
    $category_id = $request->get_param('id');

    if (empty($category_id) || !is_numeric($category_id)) {
        return new WP_REST_Response(array('error' => 'Invalid category ID'), 400);
    }

    $category = get_category($category_id);

    if (!$category) {
        return new WP_REST_Response(array('error' => 'Category not found'), 404);
    }

    // Delete the category
    $deleted = wp_delete_category($category_id);

    if ($deleted) {
        return new WP_REST_Response(array('message' => 'Category deleted successfully!', 'category_id' => $category_id), 200);
    } else {
        return new WP_REST_Response(array('error' => 'Failed to delete category'), 500);
    }
}

// Register tag deletion route
function register_custom_tag_deletion_route() {
    register_rest_route('custom-api', '/delete-tag/(?P<id>\d+)', array(
        'methods'  => 'DELETE',
        'callback' => 'handle_delete_tag',
        'permission_callback' => '__return_true', // Allow all requests (only for testing)
    ));
}
add_action('rest_api_init', 'register_custom_tag_deletion_route');

// Handle tag deletion
function handle_delete_tag(WP_REST_Request $request) {
    $tag_id = $request->get_param('id');

    if (empty($tag_id) || !is_numeric($tag_id)) {
        return new WP_REST_Response(array('error' => 'Invalid tag ID'), 400);
    }

    $tag = get_term($tag_id, 'post_tag');

    if (!$tag) {
        return new WP_REST_Response(array('error' => 'Tag not found'), 404);
    }

    // Delete the tag
    $deleted = wp_delete_term($tag_id, 'post_tag');

    if ($deleted) {
        return new WP_REST_Response(array('message' => 'Tag deleted successfully!', 'tag_id' => $tag_id), 200);
    } else {
        return new WP_REST_Response(array('error' => 'Failed to delete tag'), 500);
    }
}
