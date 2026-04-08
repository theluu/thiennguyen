<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Không tìm thấy trang', 'haritics')); ?>
    <section class="ul-section-spacing">
        <div class="ul-container text-center">
            <h1 class="ul-section-title"><?php esc_html_e('404', 'haritics'); ?></h1>
            <p class="ul-section-descr"><?php esc_html_e('Trang bạn đang tìm không tồn tại hoặc đã được di chuyển.', 'haritics'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="ul-btn"><i class="flaticon-fast-forward-double-right-arrows-symbol"></i> <?php esc_html_e('Quay về trang chủ', 'haritics'); ?></a>
        </div>
    </section>
</main>
<?php
get_footer();
