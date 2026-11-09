<?php
/**
 * Template Name: Portfolio Gallery
 * Used by the Graphic Design and Animation pages. The page slug picks the section.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$slug = get_post_field('post_name', get_queried_object_id());
$intro = [
    'graphic-design' => 'Presentations, infographics, icons and campaigns for companies that ship. Click any piece to see it larger.',
    'animation'      => 'Motion graphics, training and product videos. Click a card to play.',
];
$text = has_excerpt() ? get_the_excerpt() : (isset($intro[$slug]) ? $intro[$slug] : '');
?>
<main class="page">
  <div class="head"><h1><?php echo wp_kses_post(str_replace(' ', '<br>', esc_html(get_the_title()))); ?></h1><p><?php echo esc_html($text); ?></p></div>
  <?php get_template_part('template-parts/gallery', null, ['section' => $slug, 'layout' => $slug === 'animation' ? 'video' : 'masonry']); ?>
</main>
<?php get_footer();
