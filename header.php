<?php if (!defined('ABSPATH')) { exit; } ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php /* Page transition: if we arrived through the stripe wipe, paint the stripes before anything else so the new page never flashes. */ ?>
<script>(function(){try{if(sessionStorage.getItem('apWipe')==='1'&&!(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)){var d=document.documentElement;d.classList.add('ap-arrive');setTimeout(function(){d.classList.remove('ap-arrive')},4000);}}catch(e){}})();</script>
<style>html.ap-arrive::before{content:"";position:fixed;inset:0;z-index:2147483647;pointer-events:none;background:linear-gradient(121deg,#DA3832 50%,#F4EBD9 50%)}</style>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="nav">
  <a class="wvlogo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Alfredo Paterno home">
    <svg viewBox="0 0 1153.1 632.7" fill="currentColor" aria-hidden="true"><path d="M542.4,233.7c2.6-4.2,4.8-3.6,4.8,1.4v278.4c0,5-4,9-9,9h-167.1c-4.9,0-6.9-3.4-4.2-7.6l175.5-281.1h0Z"/><path d="M376.8,32.4c2.6-4.2,8.7-7.7,13.7-7.7h148c5,0,9,4,9,9v145c0,5-2.1,12.4-4.8,16.6l-236.9,374.4c-2.6,4.2-8.8,7.6-13.8,7.6H50.1c-4.9,0-6.9-3.5-4.3-7.7L376.8,32.4Z"/><rect x="566.5" y="24.7" width="204" height="554" rx="9"/><path d="M799.2,24.7h47.9c135.5,0,247.9,110.9,247.4,246.4-.5,135.2-110.2,244.6-245.5,244.6h-49.8c-5.3,0-9.7-4.3-9.7-9.7v-130.6c0-5.3,4.3-9.7,9.7-9.7h14.6c52.1,0,95.3-41.7,95.7-93.8.4-52.5-42.1-95.2-94.5-95.2h-15.8c-5.3,0-9.7-4.3-9.7-9.7V34.4c0-5.3,4.3-9.7,9.7-9.7h0Z"/><circle cx="809.1" cy="272.7" r="77.5"/></svg>
  </a>
  <div class="navc"><ul>
    <li><a href="<?php echo esc_url(home_url('/#work')); ?>"><span class="nw"><span class="nw-pre" aria-hidden="true"><span>Selected</span></span>Work</span></a></li>
    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
    <li><a href="<?php echo esc_url(home_url('/#contact')); ?>">Contact</a></li>
  </ul>
    <button class="burger" id="burger" type="button" aria-label="Open menu" aria-expanded="false"><i></i><i></i><i></i></button></div>
  <a class="cta" href="<?php echo esc_url(home_url('/#contact')); ?>">Let's talk</a>
</header>
<nav id="menu" aria-label="Full menu" aria-hidden="true">
  <div class="cols">
    <div class="big">
      <?php
      $items = [['Home', home_url('/')], ['Graphic Design', home_url('/graphic-design/')], ['Animation', home_url('/animation/')], ['Blog', home_url('/blog/')], ['Resume', home_url('/resume/')], ['About', home_url('/about/')], ['Contact', home_url('/#contact')]];
      foreach ($items as $i => $it) : $d = number_format(.3 + $i * .05, 2); ?>
        <a href="<?php echo esc_url($it[1]); ?>" style="transition-delay:<?php echo esc_attr($d . 's,' . $d . 's,0s'); ?>"><small>0<?php echo (int) ($i + 1); ?></small><?php echo esc_html($it[0]); ?></a>
      <?php endforeach; ?>
    </div>
    <?php $clients = get_terms(['taxonomy' => 'client', 'hide_empty' => false]);
    if (!is_wp_error($clients) && $clients) : ?>
    <div class="side"><h5>By client</h5><div class="cl">
      <?php foreach ($clients as $t) : ?><a href="<?php echo esc_url(get_term_link($t)); ?>"><?php echo esc_html($t->name); ?></a><?php endforeach; ?>
    </div></div>
    <?php endif; ?>
  </div>
  <div class="foot"><a href="mailto:freddypaterno@gmail.com">freddypaterno@gmail.com</a><a href="https://linkedin.com/in/alfredopaterno">LinkedIn</a><span>Apex, North Carolina</span></div>
</nav>

