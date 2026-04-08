<?php
/**
 * Template Name: Contact Page
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$status = sanitize_text_field(wp_unslash($_GET['contact_status'] ?? ''));
$intro = haritics_get_option('contact_intro', __('Chúng tôi luôn sẵn sàng lắng nghe nhu cầu đồng hành, tài trợ, hợp tác triển khai dự án và các đề xuất từ cộng đồng.', 'haritics'));
$default_map_iframe = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0762457458724!2d105.83415957596991!3d21.03045648764147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abbd41f7a72f%3A0xc17ad1ad2d4d4e60!2zSOG7kyBIb8OgbiBLaeG6v20!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
$map_iframe = trim(haritics_get_option('contact_map_iframe'));
if ($map_iframe === '') {
    $map_iframe = $default_map_iframe;
}
$success_message = haritics_get_option('contact_success_message', __('Cảm ơn bạn. Chúng tôi đã nhận được thông tin và sẽ phản hồi sớm.', 'haritics'));
$recaptcha_site_key = trim((string) haritics_get_option('recaptcha_v3_site_key'));
$recaptcha_enabled = function_exists('haritics_contact_recaptcha_is_enabled') && haritics_contact_recaptcha_is_enabled();
?>
<main>
    <?php haritics_render_breadcrumb(__('Liên hệ', 'haritics')); ?>

    <div class="ul-contact-infos">
        <div class="ul-section-spacing ul-container">
            <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                <div class="col">
                    <div class="ul-contact-info">
                        <div class="icon"><i class="flaticon-phone-call"></i></div>
                        <div class="txt">
                            <span class="title"><?php esc_html_e('Số điện thoại', 'haritics'); ?></span>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', haritics_get_option('hotline', '19001234'))); ?>"><?php echo esc_html(haritics_get_option('hotline', '1900 1234')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="ul-contact-info">
                        <div class="icon"><i class="flaticon-comment"></i></div>
                        <div class="txt">
                            <span class="title"><?php esc_html_e('Địa chỉ email', 'haritics'); ?></span>
                            <a href="mailto:<?php echo antispambot(haritics_get_option('email', 'lienhe@thiennguyen.vn')); ?>"><?php echo esc_html(haritics_get_option('email', 'lienhe@thiennguyen.vn')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="ul-contact-info">
                        <div class="icon"><i class="flaticon-location"></i></div>
                        <div class="txt">
                            <span class="title"><?php esc_html_e('Địa chỉ văn phòng', 'haritics'); ?></span>
                            <span class="descr"><?php echo esc_html(haritics_get_option('address', __('Hà Nội', 'haritics'))); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($map_iframe !== '') : ?>
        <div class="ul-contact-map"><?php echo $map_iframe; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    <?php endif; ?>

    <section class="ul-inner-contact ul-section-spacing">
        <div class="ul-section-heading justify-content-center text-center">
            <div>
                <span class="ul-section-sub-title"><?php esc_html_e('Liên hệ với chúng tôi', 'haritics'); ?></span>
                <h2 class="ul-section-title"><?php esc_html_e('Hãy để lại thông tin cho chúng tôi bất cứ lúc nào', 'haritics'); ?></h2>
                <p class="ul-section-descr"><?php echo esc_html($intro); ?></p>
            </div>
        </div>

        <div class="ul-inner-contact-container">
            <?php if ($status === 'success') : ?>
                <div class="haritics-form-message success"><?php echo esc_html($success_message); ?></div>
            <?php elseif ($status === 'recaptcha') : ?>
                <div class="haritics-form-message error"><?php esc_html_e('Xác minh reCAPTCHA không thành công. Vui lòng thử lại.', 'haritics'); ?></div>
            <?php elseif ($status !== '') : ?>
                <div class="haritics-form-message error"><?php esc_html_e('Vui lòng kiểm tra lại thông tin đã nhập.', 'haritics'); ?></div>
            <?php endif; ?>

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="ul-contact-form ul-form" data-recaptcha-enabled="<?php echo $recaptcha_enabled ? 'true' : 'false'; ?>">
                <input type="hidden" name="action" value="haritics_contact_submit">
                <?php wp_nonce_field('haritics_contact_submit', 'haritics_contact_nonce'); ?>
                <input type="hidden" name="recaptcha_token" value="">
                <div class="row row-cols-2 row-cols-xxs-1 ul-bs-row">
                    <div class="col">
                        <div class="form-group"><input type="text" name="name" placeholder="<?php esc_attr_e('Họ và tên', 'haritics'); ?>" required></div>
                    </div>
                    <div class="col">
                        <div class="form-group"><input type="email" name="email" placeholder="<?php esc_attr_e('Địa chỉ email', 'haritics'); ?>" required></div>
                    </div>
                    <div class="col-12">
                        <div class="form-group"><input type="text" name="phone" placeholder="<?php esc_attr_e('Số điện thoại', 'haritics'); ?>"></div>
                    </div>
                    <div class="col-12">
                        <div class="form-group"><input type="text" name="subject" placeholder="<?php esc_attr_e('Chủ đề', 'haritics'); ?>" required></div>
                    </div>
                    <div class="col-12">
                        <div class="form-group"><textarea name="message" placeholder="<?php esc_attr_e('Nhập nội dung tin nhắn', 'haritics'); ?>" required></textarea></div>
                    </div>
                    <div class="col-12 text-center">
                        <button class="ul-btn" type="submit"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Gửi liên hệ', 'haritics'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
<?php if ($recaptcha_enabled) : ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr($recaptcha_site_key); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('.ul-contact-form');

            if (!form || typeof grecaptcha === 'undefined') {
                return;
            }

            var submitButton = form.querySelector('button[type="submit"]');
            var tokenField = form.querySelector('input[name="recaptcha_token"]');
            var siteKey = <?php echo wp_json_encode($recaptcha_site_key); ?>;
            var action = 'contact_form_submit';

            form.addEventListener('submit', function (event) {
                if (form.dataset.recaptchaReady === 'true') {
                    return;
                }

                event.preventDefault();

                if (submitButton) {
                    submitButton.disabled = true;
                }

                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, {action: action}).then(function (token) {
                        tokenField.value = token;
                        form.dataset.recaptchaReady = 'true';
                        form.submit();
                    }).catch(function () {
                        form.dataset.recaptchaReady = 'false';

                        if (submitButton) {
                            submitButton.disabled = false;
                        }

                        window.alert('Xac minh reCAPTCHA that bai. Vui long thu lai.');
                    });
                });
            });
        });
    </script>
<?php endif; ?>
<?php get_footer(); ?>
