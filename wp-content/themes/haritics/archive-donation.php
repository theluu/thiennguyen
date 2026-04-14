<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
    <?php haritics_render_breadcrumb(__('Mạnh thường quân', 'haritics')); ?>
    <section class="ul-team ul-inner-team ul-section-spacing">
        <div class="ul-container">
            <div class="row row-cols-md-4 row-cols-sm-3 row-cols-2 row-cols-xxs-1 ul-team-row justify-content-center">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col">
                        <article class="ul-team-member haritics-donor-member">
                            <div class="ul-team-member-img">
                                <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                            </div>
                            <div class="ul-team-member-info">
                                <h3 class="ul-team-member-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="ul-team-member-designation"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_donor_type', __('Mạnh thường quân', 'haritics'))); ?></p>
                                <?php if (haritics_get_meta(get_the_ID(), '_contribution_type') !== '') : ?>
                                    <p class="haritics-donor-location"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_contribution_type')); ?></p>
                                <?php endif; ?>
                                <?php if (haritics_get_meta(get_the_ID(), '_campaign_related') !== '') : ?>
                                    <p class="haritics-donor-location"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_campaign_related')); ?></p>
                                <?php endif; ?>
                                <?php if (get_the_excerpt() !== '') : ?>
                                    <p class="haritics-donor-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>
                <?php endwhile; endif; ?>
            </div>
            <?php haritics_render_pagination(); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
