<?php
/**
 * Contact form handling.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_contact_recaptcha_is_enabled(): bool
{
    return trim((string) haritics_get_option('recaptcha_v3_site_key')) !== ''
        && trim((string) haritics_get_option('recaptcha_v3_secret_key')) !== '';
}

function haritics_verify_recaptcha_v3_token(string $token): bool
{
    $secret_key = trim((string) haritics_get_option('recaptcha_v3_secret_key'));

    if ($secret_key === '' || $token === '') {
        return false;
    }

    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'timeout' => 10,
            'body' => [
                'secret' => $secret_key,
                'response' => $token,
            ],
        ]
    );

    if (is_wp_error($response)) {
        return false;
    }

    $payload = json_decode(wp_remote_retrieve_body($response), true);

    if (! is_array($payload) || empty($payload['success'])) {
        return false;
    }

    $score = isset($payload['score']) ? (float) $payload['score'] : 0.0;
    $action = sanitize_key((string) ($payload['action'] ?? ''));

    if ($action !== 'contact_form_submit') {
        return false;
    }

    return $score >= 0.5;
}

function haritics_handle_contact_form(): void
{
    if (! isset($_POST['haritics_contact_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['haritics_contact_nonce'])), 'haritics_contact_submit')) {
        wp_safe_redirect(add_query_arg('contact_status', 'invalid', wp_get_referer() ?: haritics_route_url('contact')));
        exit;
    }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));
    $message = wp_kses_post(wp_unslash($_POST['message'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $recaptcha_token = sanitize_text_field(wp_unslash($_POST['recaptcha_token'] ?? ''));
    $redirect = wp_get_referer() ?: haritics_route_url('contact');

    if ($name === '' || $email === '' || $subject === '' || $message === '' || ! is_email($email)) {
        wp_safe_redirect(add_query_arg('contact_status', 'error', $redirect));
        exit;
    }

    if (haritics_contact_recaptcha_is_enabled() && ! haritics_verify_recaptcha_v3_token($recaptcha_token)) {
        wp_safe_redirect(add_query_arg('contact_status', 'recaptcha', $redirect));
        exit;
    }

    $post_id = wp_insert_post([
        'post_type' => 'haritics_message',
        'post_status' => 'publish',
        'post_title' => $subject . ' - ' . $name,
        'post_content' => "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\n{$message}",
    ]);

    if (! is_wp_error($post_id) && $post_id) {
        update_post_meta($post_id, '_contact_email', $email);
        update_post_meta($post_id, '_contact_phone', $phone);
    }

    $admin_email = get_option('admin_email');
    if (is_email($admin_email)) {
        wp_mail($admin_email, $subject, "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\n{$message}");
    }

    wp_safe_redirect(add_query_arg('contact_status', 'success', $redirect));
    exit;
}
add_action('admin_post_nopriv_haritics_contact_submit', 'haritics_handle_contact_form');
add_action('admin_post_haritics_contact_submit', 'haritics_handle_contact_form');
