<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Kết quả tìm kiếm', 'haritics')); ?>
    <section class="ul-section-spacing">
        <div class="ul-container">
            <div class="ul-section-heading justify-content-center text-center">
                <div>
                    <span class="ul-section-sub-title"><?php esc_html_e('Tìm kiếm nâng cao', 'haritics'); ?></span>
                    <h2 class="ul-section-title"><?php echo esc_html(haritics_get_search_result_label()); ?></h2>
                </div>
            </div>

            <?php if (have_posts()) : ?>
                <div class="row gy-4">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-12">
                            <article class="ul-project ul-project--sm">
                                <div class="ul-project-img">
                                    <?php if (has_post_thumbnail()) : the_post_thumbnail('large', ['alt' => get_the_title()]); endif; ?>
                                </div>
                                <div class="ul-project-txt">
                                    <div>
                                        <span class="ul-section-sub-title"><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? get_post_type()); ?></span>
                                        <h3 class="ul-project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="ul-project-descr"><?php echo esc_html(get_the_excerpt() ?: haritics_get_meta(get_the_ID(), '_location', haritics_get_meta(get_the_ID(), '_venue', ''))); ?></p>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="ul-project-btn"><i class="flaticon-up-right-arrow"></i></a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php haritics_render_pagination(); ?>
            <?php else : ?>
                <div class="text-center">
                    <p class="ul-section-descr"><?php esc_html_e('Chưa tìm thấy kết quả phù hợp với bộ lọc hiện tại.', 'haritics'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
