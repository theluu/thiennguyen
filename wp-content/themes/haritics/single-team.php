<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header();
while (have_posts()) : the_post();
    $current_post_id = get_the_ID();
    $social_links = haritics_get_post_social_links(get_the_ID());
    $skills = [
        ['label' => haritics_translate_skill_label(haritics_get_meta(get_the_ID(), '_skill_one_label', 'Leadership')), 'value' => (int) haritics_get_meta(get_the_ID(), '_skill_one_value', '90')],
        ['label' => haritics_translate_skill_label(haritics_get_meta(get_the_ID(), '_skill_two_label', 'Field Coordination')), 'value' => (int) haritics_get_meta(get_the_ID(), '_skill_two_value', '85')],
        ['label' => haritics_translate_skill_label(haritics_get_meta(get_the_ID(), '_skill_three_label', 'Community Outreach')), 'value' => (int) haritics_get_meta(get_the_ID(), '_skill_three_value', '80')],
    ];
    $related_team = get_posts([
        'post_type' => 'team',
        'posts_per_page' => 4,
        'post_status' => 'publish',
        'post__not_in' => [$current_post_id],
    ]);
    ?>
    <main>
        <?php haritics_render_breadcrumb(get_the_title()); ?>
        <div class="ul-section-spacing">
            <div class="ul-container">
                <div class="ul-team-details">
                    <div class="row justify-content-between gx-0 gy-3">
                        <div class="col-md-5">
                            <div class="ul-team-details-img">
                                <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="txt">
                                <h3 class="ul-team-details-name ul-section-title"><?php the_title(); ?></h3>
                                <h6 class="ul-team-details-role"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_role')); ?></h6>
                                <p class="ul-team-details-descr"><?php echo esc_html(haritics_get_meta(get_the_ID(), '_quote', get_the_excerpt())); ?></p>
                                <ul class="ul-team-details-infos">
                                    <?php if (haritics_get_meta(get_the_ID(), '_phone') !== '') : ?><li class="ul-team-details-info"><a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', haritics_get_meta(get_the_ID(), '_phone'))); ?>"><i class="flaticon-telephone-call"></i> <?php echo esc_html(haritics_get_meta(get_the_ID(), '_phone')); ?></a></li><?php endif; ?>
                                    <?php if (haritics_get_meta(get_the_ID(), '_email') !== '') : ?><li class="ul-team-details-info"><a href="mailto:<?php echo antispambot(haritics_get_meta(get_the_ID(), '_email')); ?>"><i class="flaticon-email"></i> <?php echo esc_html(haritics_get_meta(get_the_ID(), '_email')); ?></a></li><?php endif; ?>
                                </ul>
                                <?php haritics_render_social_links($social_links, 'ul-team-details-socials'); ?>
                                <div class="ul-team-details-experiences">
                                    <h3 class="ul-donation-details-summary-title"><?php esc_html_e('Kỹ năng nổi bật', 'haritics'); ?></h3>
                                    <div class="experiences-wrapper">
                                        <?php foreach ($skills as $skill) : ?>
                                            <div class="ul-team-details-experience">
                                                <h6 class="experience-title"><?php echo esc_html($skill['label']); ?></h6>
                                                <div class="ul-donation-progress-2">
                                                    <div class="ul-progress-container">
                                                        <div class="skill-progressbar ul-progressbar" data-ul-progress-value="<?php echo esc_attr((string) $skill['value']); ?>">
                                                            <div class="skill-progress-label ul-progress-label">00%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="haritics-content ul-section-spacing--top">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($related_team !== []) : ?>
            <section class="ul-team ul-inner-team ul-section-spacing">
                <div class="ul-container">
                    <div class="ul-section-heading justify-content-center text-center">
                        <div>
                            <span class="ul-section-sub-title"><?php esc_html_e('Đội ngũ đồng hành', 'haritics'); ?></span>
                            <h2 class="ul-section-title"><?php esc_html_e('Đội ngũ liên quan', 'haritics'); ?></h2>
                        </div>
                    </div>
                    <div class="row row-cols-md-4 row-cols-sm-2 row-cols-1 ul-team-row justify-content-center">
                        <?php foreach ($related_team as $member) : ?>
                            <div class="col">
                                <article class="ul-team-member">
                                    <div class="ul-team-member-img">
                                        <?php echo get_the_post_thumbnail($member->ID, 'large', ['alt' => get_the_title($member)]); ?>
                                        <?php haritics_render_social_links(haritics_get_post_social_links($member->ID), 'ul-team-member-socials'); ?>
                                    </div>
                                    <div class="ul-team-member-info">
                                        <h3 class="ul-team-member-name"><a href="<?php echo esc_url(get_permalink($member)); ?>"><?php echo esc_html(get_the_title($member)); ?></a></h3>
                                        <p class="ul-team-member-designation"><?php echo esc_html(haritics_get_meta($member->ID, '_role')); ?></p>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
<?php endwhile; get_footer(); ?>
