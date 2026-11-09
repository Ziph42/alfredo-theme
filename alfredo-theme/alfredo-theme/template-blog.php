<?php
/**
 * Template Name: Blog Index
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$posts = new WP_Query(['post_type' => 'post', 'posts_per_page' => 20, 'ignore_sticky_posts' => true]);
$first = $posts->have_posts() ? $posts->posts[0] : null;
?>
<main class="page">
  <div class="head"><h1>Blog</h1><p>Process notes, tutorials and thoughts on motion and design.</p></div>
  <?php if (!$first) : ?>
    <p class="empty">No posts yet. Add one under Posts in the WordPress menu.</p>
  <?php else :
    $cats = get_the_category($first->ID); ?>
    <a class="feat" href="<?php echo esc_url(get_permalink($first)); ?>">
      <?php if (has_post_thumbnail($first)) : echo '<div class="art" style="background:none;padding:0;overflow:hidden">' . get_the_post_thumbnail($first, 'large', ['style' => 'width:100%;height:100%;object-fit:cover']) . '</div>';
      else : ?><div class="art" style="background:linear-gradient(135deg,#DA3832,#B83367)">Featured</div><?php endif; ?>
      <div><span class="tag"><?php echo $cats ? esc_html($cats[0]->name) : 'Blog'; ?></span><h2><?php echo esc_html(get_the_title($first)); ?></h2><p><?php echo esc_html(wp_trim_words(get_the_excerpt($first), 28)); ?></p></div>
    </a>
    <div class="blist">
      <?php foreach (array_slice($posts->posts, 1) as $p) : $c = get_the_category($p->ID);
        $mins = max(1, (int) round(str_word_count(wp_strip_all_tags($p->post_content)) / 200)); ?>
        <a class="brow" href="<?php echo esc_url(get_permalink($p)); ?>">
          <span class="d"><?php echo esc_html(get_the_date('M j, Y', $p)); ?></span>
          <h3><?php echo esc_html(get_the_title($p)); ?></h3>
          <span class="t"><?php echo $c ? esc_html($c[0]->name) : ''; ?></span>
          <span class="r"><?php echo (int) $mins; ?> min</span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php get_footer();
