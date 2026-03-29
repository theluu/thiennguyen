<?php
if (! defined('ABSPATH')) {
    exit;
}

haritics_set_current_template('404.html');
get_header();
?>
<main class="overflow-hidden">
<?php haritics_render_part('main', haritics_get_current_template()); ?>
</main>
<?php
get_footer();
