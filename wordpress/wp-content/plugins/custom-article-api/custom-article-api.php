<?php
/**
 * Plugin Name: Custom Article API
 * Description: A custom plugin to interact with WordPress REST API for getting and sending articles.
 * Version: 1.0
 * Author: Hada Ana
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register the new endpoint for creating posts
function register_custom_post_creation_route() {
    register_rest_route('custom-api', '/create-post', array(
        'methods'  => 'POST',
        'callback' => 'handle_create_post',
        'permission_callback' => '__return_true', // Allow all requests (only for testing!)
    ));
}
add_action('rest_api_init', 'register_custom_post_creation_route');
// Handle the post creation logic
function handle_create_post(WP_REST_Request $request) {
    // Get parameters from request
    $title   = sanitize_text_field($request->get_param('title'));
    $content = sanitize_textarea_field($request->get_param('content'));
    $status  = $request->get_param('status') ? sanitize_text_field($request->get_param('status')) : 'publish';
    $author  = $request->get_param('author') ? intval($request->get_param('author')) : get_current_user_id();

    // Validate required fields
    if (empty($title) || empty($content)) {
        return new WP_REST_Response(array('error' => 'Title and content are required.'), 400);
    }

    // Prepare post data
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => $status,
        'post_author'  => $author,
        'post_type'    => 'post',
    );

    // Insert the post
    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_REST_Response(array('error' => 'Failed to create post.'), 500);
    }

    return new WP_REST_Response(array('message' => 'Post created successfully!', 'post_id' => $post_id), 201);
}




// Register a new endpoint for deleting posts
function register_custom_post_deletion_route() {
    register_rest_route('custom-api', '/delete-post/(?P<id>\d+)', array(
        'methods'  => 'DELETE',
        'callback' => 'handle_delete_post',
        'permission_callback' => '__return_true', // Change this for security in production
    ));
}
add_action('rest_api_init', 'register_custom_post_deletion_route');

// Handle the post deletion logic
function handle_delete_post(WP_REST_Request $request) {
    $post_id = $request->get_param('id');

    if (!$post_id || !is_numeric($post_id)) {
        return new WP_REST_Response(array('error' => 'Invalid post ID'), 400);
    }

    $post = get_post($post_id);

    if (!$post) {
        return new WP_REST_Response(array('error' => 'Post not found'), 404);
    }

    // Delete the post
    $deleted = wp_delete_post($post_id, true); // True for permanent deletion

    if ($deleted) {
        return new WP_REST_Response(array('message' => 'Post deleted successfully!', 'post_id' => $post_id), 200);
    } else {
        return new WP_REST_Response(array('error' => 'Failed to delete post'), 500);
    }
}

