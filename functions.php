<?php
/**
 * Alfredo Paterno theme.
 */
if (!defined('ABSPATH')) { exit; }
require_once get_template_directory() . '/inc/editable.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');   // tags video embeds with their shape (16:9, 9:16...) so they size correctly
    add_post_type_support('page', 'excerpt');
    add_theme_support('html5', ['style', 'script']);
});

add_action('wp_enqueue_scripts', function () {
    $dir = get_template_directory();
    // version each file by its last-modified time, so a re-uploaded file shows up immediately
    $ver = function ($f) use ($dir) { return file_exists($dir . $f) ? filemtime($dir . $f) : null; };
    // Adobe Fonts web project: Neue Haas Grotesk Display (headings) + Area Normal (body)
    wp_enqueue_style('alfredo-fonts', 'https://use.typekit.net/djd6onk.css', [], null);
    wp_enqueue_style('alfredo-main', get_template_directory_uri() . '/assets/css/main.css', ['alfredo-fonts'], $ver('/assets/css/main.css'));
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true);
    wp_enqueue_script('gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], '3.12.5', true);
    if (is_page_template('template-gallery.php') || is_tax('client')) {
        wp_enqueue_script('alfredo-gallery', get_template_directory_uri() . '/assets/js/gallery.js', [], $ver('/assets/js/gallery.js'), true);
    }
    wp_enqueue_script('alfredo-site', get_template_directory_uri() . '/assets/js/site.js', ['gsap', 'gsap-st'], $ver('/assets/js/site.js'), true);
    if (is_front_page()) { wp_enqueue_script('alfredo-globe', get_template_directory_uri() . '/assets/js/globe.js', ['alfredo-site'], $ver('/assets/js/globe.js'), true); }
});

// Projects + disciplines
add_action('init', function () {
    register_post_type('project', [
        'labels' => ['name' => 'Projects', 'singular_name' => 'Project', 'add_new_item' => 'Add New Project'],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-art',
        'rewrite' => ['slug' => 'work'],
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'show_in_rest' => true,
    ]);
    register_taxonomy('section', 'project', [
        'labels' => ['name' => 'Sections', 'singular_name' => 'Section'],
        'public' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'section'],
        'show_in_rest' => true,
        'default_term' => ['name' => 'Graphic Design', 'slug' => 'graphic-design'],
    ]);
    register_taxonomy('client', 'project', [
        'labels' => ['name' => 'Clients', 'singular_name' => 'Client'],
        'public' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'client'],
        'show_in_rest' => true,
    ]);
    register_taxonomy('discipline', 'project', [
        'labels' => ['name' => 'Disciplines', 'singular_name' => 'Discipline'],
        'public' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'discipline'],
        'show_in_rest' => true,
    ]);
});

// Video URL box on the project editor
add_action('add_meta_boxes', function () {
    add_meta_box('alfredo_video', 'Video', function ($post) {
        wp_nonce_field('alfredo_video', 'alfredo_video_nonce');
        $val = get_post_meta($post->ID, '_alfredo_video', true);
        echo '<p>Paste a Vimeo or YouTube link, or pick a video you uploaded to WordPress. Leave empty to show the featured image instead.</p>';
        echo '<p style="display:flex;gap:8px"><input type="url" id="alfredo_video" name="alfredo_video" value="' . esc_attr($val) . '" style="flex:1" placeholder="https://vimeo.com/123456789">';
        echo '<button type="button" class="button" id="alfredo_video_pick">Choose from Media Library</button></p>';
    }, 'project', 'normal', 'high');
});
// "Show in Selected Work" checkbox: only ticked projects appear in the home page list
add_action('add_meta_boxes', function () {
    add_meta_box('alfredo_featured', 'Home page', function ($post) {
        wp_nonce_field('alfredo_featured', 'alfredo_featured_nonce');
        $on = get_post_meta($post->ID, '_alfredo_featured', true) === '1';
        echo '<label><input type="checkbox" name="alfredo_featured" value="1"' . checked($on, true, false) . '> Show in Selected Work</label>';
        echo '<p class="description">Use this for your case studies. Single gallery images normally stay off.</p>';
    }, 'project', 'side', 'high');
});
add_action('save_post_project', function ($id) {
    if (!isset($_POST['alfredo_featured_nonce']) || !wp_verify_nonce($_POST['alfredo_featured_nonce'], 'alfredo_featured')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!current_user_can('edit_post', $id)) { return; }
    update_post_meta($id, '_alfredo_featured', empty($_POST['alfredo_featured']) ? '0' : '1');
});
// A "Selected Work" column in the Projects list, so it's easy to see which are featured
add_filter('manage_project_posts_columns', function ($c) { $c['alfredo_featured'] = 'Selected Work'; return $c; });
add_action('manage_project_posts_custom_column', function ($col, $id) {
    if ($col === 'alfredo_featured') { echo get_post_meta($id, '_alfredo_featured', true) === '1' ? '&#9733;' : '&mdash;'; }
}, 10, 2);

// "Choose from Media Library" button for the video box
add_action('admin_enqueue_scripts', function ($hook) {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'project' || !in_array($hook, ['post.php', 'post-new.php'], true)) { return; }
    wp_enqueue_media();
    wp_add_inline_script('jquery-core', "jQuery(function($){var f;$('#alfredo_video_pick').on('click',function(e){e.preventDefault();if(!f){f=wp.media({title:'Choose a video',library:{type:'video'},button:{text:'Use this video'},multiple:false});f.on('select',function(){var a=f.state().get('selection').first().toJSON();$('#alfredo_video').val(a.url);});}f.open();});});");
});

// A line in the editor that is only a video-file link (.mp4/.mov/.webm) becomes a player,
// the same way WordPress already does for Vimeo and YouTube links.
add_filter('render_block', function ($html, $block) {
    if (!in_array($block['blockName'], ['core/paragraph', 'core/embed'], true)) { return $html; }
    $text = $block['blockName'] === 'core/embed'
        ? (isset($block['attrs']['url']) ? $block['attrs']['url'] : '')
        : trim(html_entity_decode(wp_strip_all_tags($html)));
    if (!preg_match('~^https?://\S+\.(mp4|m4v|webm|mov|ogv)(\?\S*)?$~i', $text)) { return $html; }
    return '<figure class="wp-block-video">' . alfredo_video_html(esc_url_raw($text)) . '</figure>';
}, 10, 2);

// Turn a project's video value into a player: Vimeo/YouTube embed, or a native player for uploaded files
function alfredo_video_html($url, $poster = '') {
    if (!$url) { return ''; }
    $ext = strtolower(pathinfo((string) wp_parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (in_array($ext, ['mp4', 'm4v', 'webm', 'mov', 'ogv'], true)) {
        $p = $poster ? ' poster="' . esc_url($poster) . '"' : '';
        return '<video src="' . esc_url($url) . '"' . $p . ' controls playsinline preload="metadata"></video>';
    }
    $embed = wp_oembed_get($url, ['width' => 1100]);
    return $embed ? $embed : '';
}
add_action('save_post_project', function ($id) {
    if (!isset($_POST['alfredo_video_nonce']) || !wp_verify_nonce($_POST['alfredo_video_nonce'], 'alfredo_video')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!current_user_can('edit_post', $id)) { return; }
    update_post_meta($id, '_alfredo_video', isset($_POST['alfredo_video']) ? esc_url_raw($_POST['alfredo_video']) : '');
});

// Flush permalinks once on theme switch so /work/ URLs work
add_action('after_switch_theme', function () {
    // Sections
    foreach (['graphic-design' => 'Graphic Design', 'animation' => 'Animation'] as $slug => $name) {
        if (!term_exists($slug, 'section')) { wp_insert_term($name, 'section', ['slug' => $slug]); }
    }
    // Pages used by the menu (created only if missing)
    $pages = [
        'graphic-design' => ['Graphic Design', 'template-gallery.php'],
        'animation'      => ['Animation', 'template-gallery.php'],
        'blog'           => ['Blog', 'template-blog.php'],
        'resume'         => ['Resume', ''],
        'about'          => ['About', ''],
    ];
    foreach ($pages as $slug => $p) {
        if (get_page_by_path($slug)) { continue; }
        $id = wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $p[0], 'post_name' => $slug]);
        if ($id && !is_wp_error($id) && $p[1]) { update_post_meta($id, '_wp_page_template', $p[1]); }
    }
    flush_rewrite_rules();
});

// ---- Bulk add: Projects > Bulk Add ----
add_action('admin_menu', function () {
    add_submenu_page('edit.php?post_type=project', 'Bulk Add', 'Bulk Add', 'edit_posts', 'alfredo-bulk', 'alfredo_bulk_page');
});

function alfredo_bulk_page() {
    if (!current_user_can('edit_posts')) { return; }
    wp_enqueue_media();
    $sections = get_terms(['taxonomy' => 'section', 'hide_empty' => false]);
    $clients  = get_terms(['taxonomy' => 'client', 'hide_empty' => false]);
    $discs    = get_terms(['taxonomy' => 'discipline', 'hide_empty' => false]);
    $made = isset($_GET['made']) ? (int) $_GET['made'] : 0;
    ?>
    <div class="wrap">
      <h1>Bulk Add Projects</h1>
      <?php if ($made) : ?><div class="notice notice-success is-dismissible"><p><?php echo (int) $made; ?> project(s) created. <a href="<?php echo esc_url(admin_url('edit.php?post_type=project')); ?>">View them</a>.</p></div><?php endif; ?>
      <p>Choose several images at once. Each one becomes its own project, with the file name as the title. Everything you pick below is applied to all of them, so add one client or type at a time.</p>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="alfredo_bulk">
        <?php wp_nonce_field('alfredo_bulk', 'alfredo_bulk_nonce'); ?>
        <input type="hidden" name="ids" id="alfredo-ids" value="">
        <p><button type="button" class="button button-primary" id="alfredo-pick">Choose images</button> <span id="alfredo-count">None selected</span></p>
        <div id="alfredo-thumbs" style="display:flex;gap:6px;flex-wrap:wrap;margin:10px 0"></div>
        <table class="form-table" role="presentation">
          <tr><th>Section</th><td>
            <select name="section">
              <?php if (!is_wp_error($sections)) { foreach ($sections as $t) { echo '<option value="' . esc_attr($t->term_id) . '">' . esc_html($t->name) . '</option>'; } } ?>
            </select></td></tr>
          <tr><th>Client</th><td>
            <input type="text" name="client" list="alfredo-clients" class="regular-text" placeholder="e.g. Dell EMC">
            <datalist id="alfredo-clients"><?php if (!is_wp_error($clients)) { foreach ($clients as $t) { echo '<option value="' . esc_attr($t->name) . '">'; } } ?></datalist></td></tr>
          <tr><th>Type</th><td>
            <input type="text" name="disciplines" list="alfredo-discs" class="regular-text" placeholder="e.g. Presentation, Infographic">
            <datalist id="alfredo-discs"><?php if (!is_wp_error($discs)) { foreach ($discs as $t) { echo '<option value="' . esc_attr($t->name) . '">'; } } ?></datalist>
            <p class="description">Separate several types with commas.</p></td></tr>
          <tr><th>Status</th><td>
            <label><input type="radio" name="status" value="draft" checked> Save as drafts so I can review them</label><br>
            <label><input type="radio" name="status" value="publish"> Publish right away</label></td></tr>
        </table>
        <?php submit_button('Create projects'); ?>
      </form>
    </div>
    <script>
    (function(){
      var frame,ids=document.getElementById('alfredo-ids'),box=document.getElementById('alfredo-thumbs'),cnt=document.getElementById('alfredo-count');
      document.getElementById('alfredo-pick').addEventListener('click',function(){
        if(!frame){frame=wp.media({title:'Choose images',multiple:true,library:{type:'image'},button:{text:'Use these images'}});
          frame.on('select',function(){
            var sel=frame.state().get('selection').toJSON();
            ids.value=sel.map(function(a){return a.id}).join(',');cnt.textContent=sel.length+' selected';box.innerHTML='';
            sel.forEach(function(a){var i=document.createElement('img');i.src=(a.sizes&&a.sizes.thumbnail)?a.sizes.thumbnail.url:a.url;i.style.cssText='width:72px;height:72px;object-fit:cover;border-radius:4px';box.appendChild(i)});
          });}
        frame.open();
      });
    })();
    </script>
    <?php
}

add_action('admin_post_alfredo_bulk', function () {
    if (!current_user_can('edit_posts') || !isset($_POST['alfredo_bulk_nonce']) || !wp_verify_nonce($_POST['alfredo_bulk_nonce'], 'alfredo_bulk')) {
        wp_die('Not allowed.');
    }
    $ids = array_filter(array_map('intval', explode(',', isset($_POST['ids']) ? $_POST['ids'] : '')));
    $section = isset($_POST['section']) ? (int) $_POST['section'] : 0;
    $client = isset($_POST['client']) ? sanitize_text_field(wp_unslash($_POST['client'])) : '';
    $discs = isset($_POST['disciplines']) ? array_filter(array_map('trim', explode(',', sanitize_text_field(wp_unslash($_POST['disciplines']))))) : [];
    $status = (isset($_POST['status']) && $_POST['status'] === 'publish' && current_user_can('publish_posts')) ? 'publish' : 'draft';
    $made = 0;
    foreach ($ids as $att) {
        if (get_post_type($att) !== 'attachment') { continue; }
        $title = trim(preg_replace('/[-_]+/', ' ', get_the_title($att)));
        $pid = wp_insert_post(['post_type' => 'project', 'post_status' => $status, 'post_title' => $title ? ucwords($title) : 'Untitled']);
        if (!$pid || is_wp_error($pid)) { continue; }
        set_post_thumbnail($pid, $att);
        if ($section) { wp_set_object_terms($pid, [$section], 'section'); }
        if ($client) { wp_set_object_terms($pid, [$client], 'client'); }
        if ($discs) { wp_set_object_terms($pid, $discs, 'discipline'); }
        $made++;
    }
    wp_safe_redirect(admin_url('edit.php?post_type=project&page=alfredo-bulk&made=' . $made));
    exit;
});

// Small label for a project: its disciplines joined by <br>
function alfredo_project_labels($id) {
    $terms = get_the_terms($id, 'discipline');
    if (!$terms || is_wp_error($terms)) { return ''; }
    return implode('<br>', array_map('esc_html', wp_list_pluck($terms, 'name')));
}

// ---- Old site addresses -> new pages (301) ----
add_action('template_redirect', function () {
    if (is_admin()) { return; }
    $path = trim((string) parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH), '/');
    $map = apply_filters('alfredo_redirects', [
        'home/graphic-design' => '/graphic-design/',
        'home/animation'      => '/animation/',
        'home/work'           => '/graphic-design/',
    ]);
    if (isset($map[$path])) {
        wp_safe_redirect(home_url($map[$path]), 301);
        exit;
    }
});

// ---- Use the gallery and blog layouts even if a page with that address already exists ----
add_filter('template_include', function ($template) {
    $dir = get_template_directory();
    if (is_page(['graphic-design', 'animation']) && !get_page_template_slug()) {
        return $dir . '/template-gallery.php';
    }
    if (is_page('resume') && !get_page_template_slug()) {
        return $dir . '/template-resume.php';
    }
    if (is_page('about') && !get_page_template_slug()) {
        return $dir . '/template-about.php';
    }
    if ((is_home() || is_page('blog')) && !is_front_page() && !get_page_template_slug()) {
        return $dir . '/template-blog.php';
    }
    return $template;
});


// ---------- Contact form ----------
add_action('init', function () {
    register_post_type('ap_message', [
        'labels'       => ['name' => 'Messages', 'singular_name' => 'Message', 'all_items' => 'All Messages'],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_position'=> 26,
        'menu_icon'    => 'dashicons-email-alt',
        'supports'     => ['title', 'editor'],
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'map_meta_cap' => true,
    ]);
});

function alfredo_contact_handler() {
    $back = wp_get_referer();
    if (!$back) { $back = home_url('/'); }
    $back = remove_query_arg('sent', strtok($back, '#'));
    $go = function ($ok) use ($back) {
        wp_safe_redirect(add_query_arg('sent', $ok ? '1' : '0', $back) . '#contact');
        exit;
    };

    // Spam protection that keeps working on cached pages (a nonce would expire inside the page cache):
    // 1. Honeypot: real people never fill this hidden field. Pretend success to bots.
    if (!empty($_POST['website'])) { $go(true); }
    // 2. Speed check: the page records how long the visitor had it open. Under 3 seconds is a bot.
    $elapsed = (isset($_POST['_e']) && is_numeric($_POST['_e'])) ? (int) $_POST['_e'] : -1;   // empty = no JavaScript, so unknown
    if ($elapsed >= 0 && $elapsed < 3000) { $go(true); }
    // 3. Rate limit: at most 5 messages an hour from the same connection.
    $ip  = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    $key = 'ap_cf_' . md5($ip . wp_salt('nonce'));
    $hits = (int) get_transient($key);
    if ($hits >= 5) { $go(false); }

    $name    = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email   = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
    $needs   = isset($_POST['need']) ? array_map('sanitize_text_field', (array) wp_unslash($_POST['need'])) : [];

    if ($name === '' || !is_email($email) || $message === '') { $go(false); }

    $body = "Name: $name\nEmail: $email\n"
          . ($company !== '' ? "Company: $company\n" : '')
          . ($needs ? 'Needs: ' . implode(', ', $needs) . "\n" : '')
          . "\n$message\n";

    set_transient($key, $hits + 1, HOUR_IN_SECONDS);   // only real, valid messages count toward the hourly limit

    // Saved first, so nothing is lost even if the server can't send mail.
    wp_insert_post([
        'post_type'    => 'ap_message',
        'post_status'  => 'private',
        'post_title'   => $name . ' (' . $email . ')',
        'post_content' => $body,
    ]);

    wp_mail(
        get_option('admin_email'),
        'New message from ' . $name,
        $body,
        ['Reply-To: ' . $name . ' <' . $email . '>']
    );

    $go(true);
}
add_action('admin_post_nopriv_alfredo_contact', 'alfredo_contact_handler');
add_action('admin_post_alfredo_contact', 'alfredo_contact_handler');

// Create any menu pages added after the theme was first activated (runs once per version).
add_action('admin_init', function () {
    if ((int) get_option('alfredo_pages_v') >= 2 || !current_user_can('edit_pages')) { return; }
    if (!get_page_by_path('about')) {
        wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'About', 'post_name' => 'about']);
    }
    update_option('alfredo_pages_v', 2);
});
