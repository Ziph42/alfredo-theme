<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<div id="preRed" aria-hidden="true"></div>
<div id="pre" aria-hidden="true">
  <svg id="waves" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <defs>
      <linearGradient id="w1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#B83367"/><stop offset=".4" stop-color="#6e1f3a"/><stop offset="1" stop-color="#3a1224"/></linearGradient>
      <linearGradient id="w2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#DA3832"/><stop offset=".35" stop-color="#B83367"/><stop offset="1" stop-color="#7a1f3c"/></linearGradient>
      <linearGradient id="w3" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#E5483A"/><stop offset=".5" stop-color="#DA3832"/><stop offset="1" stop-color="#B02A26"/></linearGradient>
    </defs>
    <g class="wg" id="g1"><path id="pw1" d="" fill="url(#w1)"/></g>
    <g class="wg" id="g2"><path id="pw2" d="" fill="url(#w2)"/></g>
    <g class="wg" id="g3"><path id="pw3" d="" fill="url(#w3)"/></g>
  </svg>
  <svg viewBox="0 0 1153.1 632.7">
    <path class="p cream" id="pFoot" d="M542.4,233.7c2.6-4.2,4.8-3.6,4.8,1.4v278.4c0,5-4,9-9,9h-167.1c-4.9,0-6.9-3.4-4.2-7.6l175.5-281.1h0Z"/>
    <path class="p red" id="pSlab" d="M376.8,32.4c2.6-4.2,8.7-7.7,13.7-7.7h148c5,0,9,4,9,9v145c0,5-2.1,12.4-4.8,16.6l-236.9,374.4c-2.6,4.2-8.8,7.6-13.8,7.6H50.1c-4.9,0-6.9-3.5-4.3-7.7L376.8,32.4Z"/>
    <rect class="p cream" id="pBar" x="566.5" y="24.7" width="204" height="554" rx="9"/>
    <path class="p red" id="pP" d="M799.2,24.7h47.9c135.5,0,247.9,110.9,247.4,246.4-.5,135.2-110.2,244.6-245.5,244.6h-49.8c-5.3,0-9.7-4.3-9.7-9.7v-130.6c0-5.3,4.3-9.7,9.7-9.7h14.6c52.1,0,95.3-41.7,95.7-93.8.4-52.5-42.1-95.2-94.5-95.2h-15.8c-5.3,0-9.7-4.3-9.7-9.7V34.4c0-5.3,4.3-9.7,9.7-9.7h0Z"/>
    <circle class="p cream" id="pDot" cx="809.1" cy="272.7" r="77.5"/>
  </svg>
  </div>

<main id="top">
  <section class="hero" id="hero">
    <div class="ring" id="ring"><span class="rr glow" aria-hidden="true"><i></i></span><span class="rr" aria-hidden="true"><i></i></span></div>
    <div class="hero-logo" id="heroLogo" aria-hidden="true"><svg viewBox="0 0 1153.1 632.7">
      <path id="hFoot" d="M542.4,233.7c2.6-4.2,4.8-3.6,4.8,1.4v278.4c0,5-4,9-9,9h-167.1c-4.9,0-6.9-3.4-4.2-7.6l175.5-281.1h0Z"/>
      <path id="hSlab" d="M376.8,32.4c2.6-4.2,8.7-7.7,13.7-7.7h148c5,0,9,4,9,9v145c0,5-2.1,12.4-4.8,16.6l-236.9,374.4c-2.6,4.2-8.8,7.6-13.8,7.6H50.1c-4.9,0-6.9-3.5-4.3-7.7L376.8,32.4Z"/>
      <rect id="hBar" x="566.5" y="24.7" width="204" height="554" rx="9"/>
      <path id="hP" d="M799.2,24.7h47.9c135.5,0,247.9,110.9,247.4,246.4-.5,135.2-110.2,244.6-245.5,244.6h-49.8c-5.3,0-9.7-4.3-9.7-9.7v-130.6c0-5.3,4.3-9.7,9.7-9.7h14.6c52.1,0,95.3-41.7,95.7-93.8.4-52.5-42.1-95.2-94.5-95.2h-15.8c-5.3,0-9.7-4.3-9.7-9.7V34.4c0-5.3,4.3-9.7,9.7-9.7h0Z"/>
      <circle id="hDot" cx="809.1" cy="272.7" r="77.5"/>
    </svg></div>
    <div class="hero-copy" id="heroCopy"><h1><?php echo esc_html(alfredo_text('hero_line1')); ?><span><?php echo esc_html(alfredo_text('hero_line2')); ?></span></h1></div>
    <p id="heroSub"><?php echo esc_html(alfredo_text('hero_sub')); ?></p>
    <div class="scrollhint">Scroll</div>
  </section>

  <section class="statement" id="about">
    <h2 id="stmt">
      <?php foreach (alfredo_lines('stmt_lines') as $line) : ?><span class="l"><?php echo esc_html($line); ?></span>
      <?php endforeach; ?>
      <span class="wlwrap">
      <?php foreach (alfredo_lines('stmt_close') as $line) : ?><span class="wl"><?php foreach (preg_split('/\s+/', $line) as $w) { echo '<b class="w">' . esc_html($w) . '</b>'; } ?></span>
      <?php endforeach; ?>
      </span>
    </h2>
  </section>

  <section class="trusted" aria-labelledby="trustedH">
    <p class="tr-kicker"><?php echo esc_html(alfredo_text('clients_kicker')); ?></p>
    <h2 class="tr-h" id="trustedH"><?php echo implode('<br>', array_map('esc_html', alfredo_lines('clients_heading'))); ?></h2>
    <ul class="tr-grid">
      <li style="--w:9.5"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/jack-in-the-box.png" alt="Jack in the Box" width="406" height="420" loading="lazy" decoding="async"></li>
      <li style="--w:24"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/tbm-council.svg" alt="TBM Council" width="3416" height="556" loading="lazy" decoding="async"></li>
      <li style="--w:9.5"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/carolina-ale-house.png" alt="Carolina Ale House" width="403" height="401" loading="lazy" decoding="async"></li>
      <li style="--w:19"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/lutron.png" alt="Lutron" width="900" height="134" loading="lazy" decoding="async"></li>
      <li style="--w:17"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/amgen.png" alt="Amgen" width="900" height="231" loading="lazy" decoding="async"></li>
      <li style="--w:14.5"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/biogen.svg" alt="Biogen" width="124" height="42" loading="lazy" decoding="async"></li>
      <li style="--w:14.5"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/frost-bank.svg" alt="Frost Bank" width="360" height="121" loading="lazy" decoding="async"></li>
      <li style="--w:11.3"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/cisco.png" alt="Cisco" width="315" height="166" loading="lazy" decoding="async"></li>
      <li style="--w:17.4"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/hewlett-packard.png" alt="Hewlett Packard" width="383" height="85" loading="lazy" decoding="async"></li>
      <li style="--w:20.1"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/dell-emc.png" alt="Dell EMC" width="420" height="70" loading="lazy" decoding="async"></li>
      <li style="--w:22.9"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/oracle.png" alt="Oracle" width="266" height="34" loading="lazy" decoding="async"></li>
      <li style="--w:15.9"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/netapp.png" alt="NetApp" width="371" height="99" loading="lazy" decoding="async"></li>
      <li style="--w:16.7"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/national-instruments.png" alt="National Instruments" width="390" height="94" loading="lazy" decoding="async"></li>
      <li style="--w:13.2"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/ebay.png" alt="eBay" width="321" height="123" loading="lazy" decoding="async"></li>
      <li style="--w:16.8"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/amd.png" alt="AMD" width="312" height="74" loading="lazy" decoding="async"></li>
      <li style="--w:22.7"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/bazaarvoice.png" alt="Bazaarvoice" width="476" height="62" loading="lazy" decoding="async"></li>
      <li style="--w:12.7"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/emerson.png" alt="Emerson" width="346" height="145" loading="lazy" decoding="async"></li>
      <li style="--w:16.3"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/applied-materials.png" alt="Applied Materials" width="408" height="103" loading="lazy" decoding="async"></li>
      <li style="--w:11.8"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/equinix.png" alt="Equinix" width="290" height="141" loading="lazy" decoding="async"></li>
      <li style="--w:14.2"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/sungard.png" alt="SunGard Availability Services" width="382" height="128" loading="lazy" decoding="async"></li>
      <li style="--w:14"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/vrbo.svg" alt="Vrbo" width="275" height="91" loading="lazy" decoding="async"></li>
      <li style="--w:17.5"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/panduit.png" alt="Panduit" width="366" height="80" loading="lazy" decoding="async"></li>
      <li style="--w:14.3"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/inmar.png" alt="Inmar" width="398" height="130" loading="lazy" decoding="async"></li>
      <li style="--w:13.7"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/nfp.png" alt="NFP" width="335" height="120" loading="lazy" decoding="async"></li>
      <li style="--w:16.2"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/bancvue.png" alt="BancVue" width="344" height="88" loading="lazy" decoding="async"></li>
      <li style="--w:18.0"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/crosswind.png" alt="Crosswind Media & Public Relations" width="367" height="76" loading="lazy" decoding="async"></li>
      <li style="--w:15.7"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/chasse-consulting.png" alt="Chasse Consulting" width="425" height="116" loading="lazy" decoding="async"></li>
      <li style="--w:14.2"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logos'); ?>/lonestar-logos.png" alt="Lonestar Logos" width="349" height="116" loading="lazy" decoding="async"></li>
    </ul>
  </section>

  <section class="work" id="work">
    <div class="work-label">Selected work</div>
    <?php
    $args = ['post_type' => 'project', 'posts_per_page' => 12, 'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC']];
    // Only projects ticked "Show in Selected Work"; until any are ticked, fall back to the latest projects.
    $q = new WP_Query($args + ['meta_query' => [['key' => '_alfredo_featured', 'value' => '1']]]);
    if (!$q->have_posts()) { $q = new WP_Query($args); }
    if ($q->have_posts()) :
      while ($q->have_posts()) : $q->the_post(); ?>
        <a class="row" href="<?php the_permalink(); ?>"><i class="band"></i><small><?php echo alfredo_project_labels(get_the_ID()); ?></small><h3><?php the_title(); ?></h3></a>
      <?php endwhile; wp_reset_postdata();
    else : ?>
      <p style="padding:0 6vw">No projects yet. Add one under Projects in the WordPress menu.</p>
    <?php endif; ?>
  </section>

  <section class="close" id="contact">
    <div>
      <h2 class="hl"><?php echo esc_html(alfredo_text('contact_line1')); ?><br><?php echo esc_html(alfredo_text('contact_line2')); ?> <em><?php echo esc_html(alfredo_text('contact_word')); ?></em></h2>
      <?php get_template_part('template-parts/contact-form'); ?>
    </div>
  </section>
</main>
<?php get_footer();
