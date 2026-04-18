<?php
/**
 * Lưu đơn Ứng tuyển (haritics_apply) và Muốn đóng góp (haritics_contribute) từ trang dự án.
 */

if (! defined('ABSPATH')) {
    exit;
}

function haritics_project_public_form_url(int $project_id, string $fragment): string
{
    $base = get_permalink($project_id);

    if (! is_string($base) || $base === '') {
        return '#';
    }

    return rtrim($base, '/') . '#' . ltrim($fragment, '#');
}

function haritics_handle_project_apply_submit(): void
{
    $redirect = wp_get_referer() ?: home_url('/');

    if (! isset($_POST['haritics_apply_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['haritics_apply_nonce'])), 'haritics_project_apply')) {
        wp_safe_redirect(add_query_arg('haritics_req', 'invalid', $redirect));
        exit;
    }

    $project_id = absint($_POST['project_id'] ?? 0);
    $role = sanitize_key((string) ($_POST['apply_role'] ?? ''));
    $name = sanitize_text_field(wp_unslash($_POST['applicant_name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['applicant_email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['applicant_phone'] ?? ''));
    $message = wp_kses_post(wp_unslash($_POST['applicant_message'] ?? ''));

    $project = get_post($project_id);

    if (! $project || $project->post_type !== 'project' || $project->post_status !== 'publish') {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    if ($role !== 'leader' && $role !== 'volunteer') {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    if ($name === '' || $email === '' || ! is_email($email)) {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    $role_label = $role === 'leader'
        ? __('Lãnh đạo dự án', 'haritics')
        : __('Nhân sự / tình nguyện', 'haritics');

    $title = sprintf(
        /* translators: 1: role, 2: project title */
        __('Ứng tuyển %1$s — %2$s', 'haritics'),
        $role_label,
        get_the_title($project)
    );

    $post_id = wp_insert_post([
        'post_type' => 'haritics_apply',
        'post_status' => 'pending',
        'post_title' => $title,
        'post_content' => $message !== '' ? $message : __('(Không có nội dung bổ sung)', 'haritics'),
    ], true);

    if (is_wp_error($post_id) || ! $post_id) {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    update_post_meta($post_id, '_project_id', (string) $project_id);
    update_post_meta($post_id, '_apply_role', $role);
    update_post_meta($post_id, '_applicant_name', $name);
    update_post_meta($post_id, '_applicant_email', $email);
    update_post_meta($post_id, '_applicant_phone', $phone);

    wp_safe_redirect(add_query_arg('haritics_req', 'apply_ok', get_permalink($project_id) ?: $redirect));
    exit;
}

function haritics_handle_project_contribute_submit(): void
{
    $redirect = wp_get_referer() ?: home_url('/');

    if (! isset($_POST['haritics_contribute_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['haritics_contribute_nonce'])), 'haritics_project_contribute')) {
        wp_safe_redirect(add_query_arg('haritics_req', 'invalid', $redirect));
        exit;
    }

    $project_id = absint($_POST['project_id'] ?? 0);
    $name = sanitize_text_field(wp_unslash($_POST['contributor_name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['contributor_email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['contributor_phone'] ?? ''));
    $note = wp_kses_post(wp_unslash($_POST['contribution_note'] ?? ''));

    $project = get_post($project_id);

    if (! $project || $project->post_type !== 'project' || $project->post_status !== 'publish') {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    if ($name === '' || $email === '' || ! is_email($email)) {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    $title = sprintf(
        /* translators: %s: project title */
        __('Muốn đóng góp — %s', 'haritics'),
        get_the_title($project)
    );

    $post_id = wp_insert_post([
        'post_type' => 'haritics_contribute',
        'post_status' => 'pending',
        'post_title' => $title,
        'post_content' => $note !== '' ? $note : __('(Không có ghi chú)', 'haritics'),
    ], true);

    if (is_wp_error($post_id) || ! $post_id) {
        wp_safe_redirect(add_query_arg('haritics_req', 'error', $redirect));
        exit;
    }

    update_post_meta($post_id, '_project_id', (string) $project_id);
    update_post_meta($post_id, '_applicant_name', $name);
    update_post_meta($post_id, '_applicant_email', $email);
    update_post_meta($post_id, '_applicant_phone', $phone);
    update_post_meta($post_id, '_contribution_note', $note);

    wp_safe_redirect(add_query_arg('haritics_req', 'contribute_ok', get_permalink($project_id) ?: $redirect));
    exit;
}

add_action('admin_post_nopriv_haritics_project_apply_submit', 'haritics_handle_project_apply_submit');
add_action('admin_post_haritics_project_apply_submit', 'haritics_handle_project_apply_submit');
add_action('admin_post_nopriv_haritics_project_contribute_submit', 'haritics_handle_project_contribute_submit');
add_action('admin_post_haritics_project_contribute_submit', 'haritics_handle_project_contribute_submit');
