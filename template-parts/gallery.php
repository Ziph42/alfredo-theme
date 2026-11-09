<?php
/**
 * Gallery grid with filters and lightbox.
 * Args: section (slug), client (slug), layout ('masonry'|'video').
 */
if (!defined('ABSPATH')) { exit; }
$section = isset($args['section']) ? $args['section'] : '';
$client  = isset($args['client']) ? $args['client'] : '';
$layout  = isset($args['layout']) ? $args['layout'] : 'masonry';

$query = ['post_type' => 'project', 'posts_per_page' => -1, 'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC']];
$tax = [];
if ($section) { $tax[] = ['taxonomy' => 'section', 'field' => 'slug', 'terms' => $section]; }
if ($client)  { $tax[] = ['taxonomy' => 'client', 'field' => 'slug', 'terms' => $client]; }
if ($tax) { $query['tax_query'] = $tax; }
$loop = new WP_Query($query);

$items = []; $discs = []; $clients = [];
foreach ($loop->posts as $p) {
    $id = $p->ID;
    $d = get_the_terms($id, 'discipline'); $c = get_the_terms($id, 'client');
    $d = ($d && !is_wp_error($d)) ? $d : []; $c = ($c && !is_wp_error($c)) ? $c : [];
    foreach ($d as $t) { $discs[$t->slug] = $t->name; }
    foreach ($c as $t) { $clients[$t->slug] = $t->name; }
    $video = get_post_meta($id, '_alfredo_video', true);
    $items[] = [
        'id' => $id, 'title' => get_the_title($id), 'link' => get_permalink($id),
        'thumb' => get_post_thumbnail_id($id), 'src' => get_the_post_thumbnail_url($id, 'large'),
        'cats' => array_map(function ($t) { return $t->slug; }, $d),
        'meta' => implode(', ', array_map(function ($t) { return $t->name; }, $d)),
        'client' => $c ? $c[0]->slug : '', 'client_name' => $c ? $c[0]->name : '',
        'video' => '',
    ];
}
asort($discs); asort($clients);
?>
<?php if (!$items) : ?>
  <p class="empty">No projects here yet. Add some under Projects in the WordPress menu.</p>
<?php else : ?>
<div class="filters">
  <div class="chips">
    <button type="button" class="on" data-cat="all">All</button>
    <?php foreach ($discs as $slug => $name) : ?><button type="button" data-cat="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></button><?php endforeach; ?>
  </div>
  <?php if (count($clients) > 0 && !$client) : ?>
  <label class="sel"><span>Client</span>
    <select aria-label="Filter by client">
      <option value="all">All clients</option>
      <?php foreach ($clients as $slug => $name) : ?><option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></option><?php endforeach; ?>
    </select>
  </label>
  <?php endif; ?>
  <span class="count"></span>
</div>

<div id="gallery" class="<?php echo $layout === 'video' ? 'vgrid' : 'masonry'; ?>">
  <?php foreach ($items as $i => $it) :
    $meta = trim($it['client_name'] . ($it['client_name'] && $it['meta'] ? ' / ' : '') . $it['meta']);
    $attrs = 'data-title="' . esc_attr($it['title']) . '" data-meta="' . esc_attr($meta) . '" data-link="' . esc_url($it['link']) . '" data-src="' . esc_url($it['src']) . '" data-cat="' . esc_attr(implode(' ', $it['cats'])) . '" data-client="' . esc_attr($it['client']) . '"';
    $image = $it['thumb'] ? wp_get_attachment_image($it['thumb'], 'large', false, ['loading' => 'lazy', 'alt' => $it['title']]) : '<div class="ph g' . ($i % 6) . '">' . esc_html($it['title']) . '</div>';
    if ($layout === 'video') : ?>
      <a class="vcard" href="<?php echo esc_url($it['link']); ?>" <?php echo $attrs; ?>>
        <?php echo $image; ?><div class="play"><i></i></div>
        <div class="meta"><h3><?php echo esc_html($it['title']); ?></h3><small><?php echo esc_html($meta); ?></small></div>
      </a>
    <?php else : ?>
      <a class="tile" href="<?php echo esc_url($it['link']); ?>" <?php echo $attrs; ?>>
        <?php echo $image; ?>
        <div class="cap"><span><?php echo esc_html($it['title']); ?></span><span><?php echo esc_html($it['meta']); ?></span></div>
      </a>
    <?php endif; endforeach; ?>
</div>

<?php endif; ?>
