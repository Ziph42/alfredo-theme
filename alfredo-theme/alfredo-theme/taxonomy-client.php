<?php
if (!defined('ABSPATH')) { exit; }
get_header();
$term = get_queried_object();
?>
<main class="page">
  <div class="head"><h1><?php echo esc_html($term->name); ?></h1><p>All work for <?php echo esc_html($term->name); ?>. Click any piece to see it larger.</p></div>
  <?php get_template_part('template-parts/gallery', null, ['client' => $term->slug, 'layout' => 'masonry']); ?>
</main>
<?php get_footer();
