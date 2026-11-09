<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main class="page">
<?php while (have_posts()) : the_post();
  $cats = get_the_category(); $mins = max(1, (int) round(str_word_count(wp_strip_all_tags(get_the_content())) / 200)); ?>
  <article class="article">
    <a class="back" href="<?php echo esc_url(home_url('/blog/')); ?>">&larr; All posts</a>
    <h1><?php the_title(); ?></h1>
    <div class="by"><?php echo esc_html(($cats ? $cats[0]->name . ' / ' : '') . get_the_date('M j, Y') . ' / ' . $mins . ' min read'); ?></div>
    <?php if (has_post_thumbnail()) : ?><div class="hero" style="padding:0;overflow:hidden"><?php the_post_thumbnail('large', ['style' => 'width:100%;height:100%;object-fit:cover']); ?></div><?php endif; ?>
    <div class="entry"><?php the_content(); ?></div>
  </article>
<?php endwhile; ?>
</main>
<?php get_footer();
