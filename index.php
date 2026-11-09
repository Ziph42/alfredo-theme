<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main>
<section class="proj" id="proj">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <h1 id="pTitle"><?php the_title(); ?></h1>
    <div class="content"><?php the_content(); ?></div>
  <?php endwhile; endif; ?>
</section>
</main>
<?php get_footer();
