<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<?php while (have_posts()) : the_post();
  $video = get_post_meta(get_the_ID(), '_alfredo_video', true);
  $labels = wp_strip_all_tags(str_replace('<br>', ' / ', alfredo_project_labels(get_the_ID())));
?>
<main>
<section class="proj" id="proj">
  <?php $sec = get_the_terms(get_the_ID(), 'section'); $back = ($sec && !is_wp_error($sec)) ? home_url('/' . $sec[0]->slug . '/') : home_url('/#work'); ?>
  <a class="back" href="<?php echo esc_url($back); ?>">&larr; All work</a>
  <div class="meta" id="pMeta"><?php echo esc_html($labels); ?></div>
  <h1 id="pTitle"><?php the_title(); ?></h1>
  <?php
  // Show the featured image only when the body has no images of its own, so nothing appears twice.
  $raw = get_post_field('post_content', get_the_ID());
  $has_imgs = has_block('gallery') || has_block('image') || stripos($raw, '<img') !== false;
  if ($video) : ?>
  <div class="shot"><?php $embed = alfredo_video_html($video, has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'large') : ''); echo $embed ? $embed : '<a href="' . esc_url($video) . '">Watch video</a>'; ?></div>
  <?php elseif (!$has_imgs && has_post_thumbnail()) : ?>
  <figure class="full"><?php the_post_thumbnail('full'); ?></figure>
  <?php endif; ?>
  <div class="content"><?php the_content(); ?></div>
  <?php $next = get_adjacent_post(false, '', false); if (!$next) { $next = get_adjacent_post(false, '', true); }
  if ($next) : ?><a class="next" href="<?php echo esc_url(get_permalink($next)); ?>">Next: <?php echo esc_html(get_the_title($next)); ?> &rarr;</a><?php endif; ?>
</section>
</main>
<?php endwhile; get_footer();
