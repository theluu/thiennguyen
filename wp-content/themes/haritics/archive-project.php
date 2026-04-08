<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Dự án', 'haritics')); ?>
    <section class="ul-projects ul-section-spacing">
        <div class="ul-container">
            <div class="row ul-bs-row justify-content-center">
                <?php $i = 0; if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col-lg-<?php echo $i % 2 === 0 ? '8' : '4'; ?> col-md-6 col-10 col-xxs-12">
                        <article class="ul-project <?php echo $i % 2 === 0 ? '' : 'ul-project--sm'; ?>">
                            <div class="ul-project-img"><?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?></div>
                            <div class="ul-project-txt">
                                <div>
                                    <h3 class="ul-project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="ul-project-descr"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_location', get_the_excerpt())); ?></p>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="ul-project-btn"><i class="flaticon-up-right-arrow"></i></a>
                            </div>
                        </article>
                    </div>
                <?php $i++; endwhile; endif; ?>
            </div>
            <?php haritics_render_pagination(); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
