<?php
/**
 * Template Name: About
 *
 * Editable from WordPress:
 *  - Intro text: the About page's own content (Pages > About). First paragraph is shown large.
 *  - Photo: the About page's Featured image.
 *  - Heading and stats: Appearance > Customize > Site text > About page.
 *  - Milestones: Timeline in the admin menu (Order 1 = top).
 */
if (!defined('ABSPATH')) { exit; }
get_header();
while (have_posts()) : the_post();
$intro = trim(get_the_content());
?>
<main class="ab">
  <section class="ab-hero">
    <p class="ab-kicker">About</p>
    <h1><?php echo esc_html(alfredo_text('about_h1')); ?><span><?php echo esc_html(alfredo_text('about_h2')); ?></span></h1>
    <div class="ab-intro">
      <?php if (has_post_thumbnail()) : ?>
      <div class="ab-portrait has-photo"><?php the_post_thumbnail('large', ['alt' => 'Alfredo Paterno']); ?></div>
      <?php else : ?>
      <div class="ab-portrait" role="img" aria-label="Portrait placeholder">
        <svg viewBox="0 0 1153.1 632.7" fill="currentColor" aria-hidden="true"><path d="M542.4,233.7c2.6-4.2,4.8-3.6,4.8,1.4v278.4c0,5-4,9-9,9h-167.1c-4.9,0-6.9-3.4-4.2-7.6l175.5-281.1h0Z"/><path d="M376.8,32.4c2.6-4.2,8.7-7.7,13.7-7.7h148c5,0,9,4,9,9v145c0,5-2.1,12.4-4.8,16.6l-236.9,374.4c-2.6,4.2-8.8,7.6-13.8,7.6H50.1c-4.9,0-6.9-3.5-4.3-7.7L376.8,32.4Z"/><rect x="566.5" y="24.7" width="204" height="554" rx="9"/><path d="M809.1,24.7c-40.3,0-77.9,11.6-109.5,31.6v432.8c31.7,20,69.2,31.6,109.5,31.6,113.4,0,205.4-92,205.4-205.4S922.6,24.7,809.1,24.7Z"/></svg>
        <span>Your portrait here</span>
      </div>
      <?php endif; ?>
      <div class="ab-copy">
        <?php if ($intro !== '') : ?>
        <div class="ed"><?php the_content(); ?></div>
        <?php else : ?>
        <p class="lede">I'm Alfredo Paterno, a motion and graphic designer in Apex, North Carolina. Half of what I make moves. The other half holds still and still has to work just as hard.</p>
        <p>Placeholder: two or three sentences about how you work and who you like working with. What you care about in a project, how you handle a brief, why clients come back. Swap this for your own words.</p>
        <p>Placeholder: a line about life outside the work, whatever you're happy to share.</p>
        <?php endif; ?>
      </div>
    </div>
    <ul class="ab-stats">
      <?php for ($i = 1; $i <= 3; $i++) : $n = alfredo_text('stat' . $i . '_num'); if ($n === '') { continue; } ?>
      <li><b><?php echo esc_html($n); ?></b><span><?php echo esc_html(alfredo_text('stat' . $i . '_label')); ?></span></li>
      <?php endfor; ?>
    </ul>
  </section>

  <section class="tl" aria-label="Career timeline">
    <h2 class="tl-head">The road here</h2>
    <div class="tl-rail" aria-hidden="true"><i class="tl-fill"></i></div>
    <ol class="tl-list">
      <?php foreach (alfredo_timeline_items() as $m) : ?>
      <li class="tl-item<?php echo $m['now'] ? ' tl-now' : ''; ?>">
        <span class="tl-big" aria-hidden="true"><?php echo esc_html($m['big']); ?></span><span class="tl-dot" aria-hidden="true"></span>
        <div class="tl-card">
          <?php if ($m['dates'] !== '') : ?><span class="tl-year"><?php echo esc_html($m['dates']); ?></span><?php endif; ?>
          <h3><?php echo esc_html($m['title']); ?></h3>
          <?php if ($m['role'] !== '') : ?><p class="tl-role"><?php echo esc_html($m['role']); ?></p><?php endif; ?>
          <?php if ($m['text'] !== '') : ?><p><?php echo nl2br(esc_html($m['text'])); ?></p><?php endif; ?>
          <?php $tags = array_filter(array_map('trim', explode(',', (string) $m['tags'])));
          $link = isset($m['link']) ? trim((string) $m['link']) : '';
          if ($tags || $link) : ?><div class="tl-tags"><?php foreach ($tags as $t) { echo '<span>' . esc_html($t) . '</span>'; }
            if ($link) : ?><a class="tl-view" href="<?php echo esc_url($link); ?>" aria-label="View project: <?php echo esc_attr($m['title']); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M1.5 12S5.5 4.5 12 4.5 22.5 12 22.5 12 18.5 19.5 12 19.5 1.5 12 1.5 12Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="12" r="3.4" fill="currentColor"/></svg><em>View project</em></a><?php endif; ?></div><?php endif; ?>
          <?php if ($m['now']) : ?><a class="cta tl-cta" href="<?php echo esc_url(home_url('/#contact')); ?>">Let's talk</a><?php endif; ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
  </section>
</main>
<?php endwhile; get_footer();
