<?php
/**
 * Everything that makes the site's wording editable from WordPress:
 *  - Appearance > Customize > "Site text" (home page and About page wording)
 *  - Timeline (admin menu) for the About page milestones
 *  - Editor styling so the block editor looks like the site (dark, brand fonts)
 * Every field defaults to the original wording, so nothing changes until it is edited.
 */
if (!defined('ABSPATH')) { exit; }

/* ---------------------------------------------------------------- Text defaults */
function alfredo_text_defaults() {
    return [
        'hero_line1'      => 'Motion and graphic',
        'hero_line2'      => 'design that moves.',
        'hero_sub'        => 'Alfredo Paterno. Brand, print and presentation design — and the animation that brings it to life.',
        'stmt_lines'      => "Over fifteen years\ncrafting a skill set\nused by companies and\ncorporations shaping\nour world.",
        'stmt_close'      => "Pixel by pixel.\nLayer by layer.",
        'clients_kicker'  => 'Clients',
        'clients_heading' => "Brands I’ve\nhelped move",
        'contact_line1'   => "Let's make",
        'contact_line2'   => 'something',
        'contact_word'    => 'move.',
        'about_h1'        => 'Fifteen years.',
        'about_h2'        => 'Frame by frame.',
        'stat1_num'       => '15+', 'stat1_label' => 'years in design and animation',
        'stat2_num'       => '20+', 'stat2_label' => 'animated projects explaining complex ideas',
        'stat3_num'       => '10+', 'stat3_label' => 'years of C-suite and conference presentations',
    ];
}
/** A text setting: the saved value, or the original wording if it is empty. */
function alfredo_text($key) {
    $d = alfredo_text_defaults();
    $v = get_theme_mod('alfredo_' . $key, '');
    return trim((string) $v) !== '' ? $v : (isset($d[$key]) ? $d[$key] : '');
}
/** Multi-line setting split into non-empty lines. */
function alfredo_lines($key) {
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', alfredo_text($key)))));
}

add_action('customize_register', function ($wp) {
    $wp->add_panel('alfredo_text', ['title' => 'Site text', 'priority' => 25,
        'description' => 'Change the wording on the home and About pages. Leave a box empty to go back to the original wording.']);
    $sections = [
        'alfredo_home'    => ['Home page', [
            'hero_line1'      => ['Hero: first line (white)', 'text'],
            'hero_line2'      => ['Hero: second line (grey)', 'text'],
            'hero_sub'        => ['Hero: small line under it', 'textarea'],
            'stmt_lines'      => ['Statement: one line per row (keep each row short so it fits on one line)', 'textarea'],
            'stmt_close'      => ['Statement: closing lines that rise word by word (one per row)', 'textarea'],
            'clients_kicker'  => ['Clients: small red label', 'text'],
            'clients_heading' => ['Clients: heading (one line per row)', 'textarea'],
            'contact_line1'   => ['Contact: first line', 'text'],
            'contact_line2'   => ['Contact: second line', 'text'],
            'contact_word'    => ['Contact: red word at the end', 'text'],
        ]],
        'alfredo_aboutpg' => ['About page', [
            'about_h1'    => ['Heading: first line (white)', 'text'],
            'about_h2'    => ['Heading: second line (grey)', 'text'],
            'stat1_num'   => ['Stat 1: number', 'text'], 'stat1_label' => ['Stat 1: label', 'text'],
            'stat2_num'   => ['Stat 2: number', 'text'], 'stat2_label' => ['Stat 2: label', 'text'],
            'stat3_num'   => ['Stat 3: number', 'text'], 'stat3_label' => ['Stat 3: label', 'text'],
        ]],
    ];
    $d = alfredo_text_defaults();
    foreach ($sections as $sid => $sec) {
        $wp->add_section($sid, ['title' => $sec[0], 'panel' => 'alfredo_text']);
        foreach ($sec[1] as $key => $f) {
            $wp->add_setting('alfredo_' . $key, ['default' => '', 'transport' => 'refresh',
                'sanitize_callback' => $f[1] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
            $wp->add_control('alfredo_' . $key, ['label' => $f[0], 'section' => $sid, 'type' => $f[1],
                'input_attrs' => ['placeholder' => $d[$key]]]);
        }
    }
    $wp->get_section('alfredo_aboutpg')->description = 'Your intro text and photo come from the About page itself (Pages → About: the text you type there, and its Featured image). Milestones are under Timeline in the admin menu.';
});

/* ---------------------------------------------------------------- Timeline */
add_action('init', function () {
    register_post_type('ap_milestone', [
        'labels' => ['name' => 'Timeline', 'singular_name' => 'Milestone', 'add_new_item' => 'Add New Milestone',
                     'edit_item' => 'Edit Milestone', 'all_items' => 'All Milestones', 'menu_name' => 'Timeline'],
        'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'menu_position' => 21,
        'menu_icon' => 'dashicons-backup', 'supports' => ['title', 'page-attributes'], 'hierarchical' => false,
    ]);
});

function alfredo_tl_fields() {
    return [
        '_tl_dates' => ['Dates shown in red (e.g. 2007 – 2014)', 'text'],
        '_tl_big'   => ['Big faded year behind it (e.g. 2007, or Now)', 'text'],
        '_tl_role'  => ['Role (e.g. Designer / Animator)', 'text'],
        '_tl_text'  => ['Description', 'textarea'],
        '_tl_tags'  => ['Tags, separated by commas (e.g. Motion, Presentations)', 'text'],
        '_tl_link'  => ['Project link (optional — adds a red "View project" eye button). Paste a URL, e.g. /work/food-safety/', 'text'],
    ];
}
add_action('add_meta_boxes', function () {
    add_meta_box('alfredo_tl', 'Milestone details', function ($post) {
        wp_nonce_field('alfredo_tl', 'alfredo_tl_nonce');
        echo '<p class="description">The title above is the big heading (e.g. Clarus Agency). Use <b>Order</b> in the Page Attributes box to set the position: 1 is the top of the timeline.</p>';
        foreach (alfredo_tl_fields() as $k => $f) {
            $v = get_post_meta($post->ID, $k, true);
            echo '<p><label style="display:block;font-weight:600;margin-bottom:4px">' . esc_html($f[0]) . '</label>';
            echo $f[1] === 'textarea'
                ? '<textarea name="' . esc_attr($k) . '" rows="4" style="width:100%">' . esc_textarea($v) . '</textarea>'
                : '<input type="text" name="' . esc_attr($k) . '" value="' . esc_attr($v) . '" style="width:100%">';
            echo '</p>';
        }
        $now = get_post_meta($post->ID, '_tl_now', true) === '1';
        echo '<p><label><input type="checkbox" name="_tl_now" value="1"' . checked($now, true, false) . '> This is the "Now" milestone (pulsing dot and a Let\'s talk button)</label></p>';
    }, 'ap_milestone', 'normal', 'high');
});
add_action('save_post_ap_milestone', function ($id) {
    if (!isset($_POST['alfredo_tl_nonce']) || !wp_verify_nonce($_POST['alfredo_tl_nonce'], 'alfredo_tl')) { return; }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $id)) { return; }
    foreach (alfredo_tl_fields() as $k => $f) {
        $raw = isset($_POST[$k]) ? wp_unslash($_POST[$k]) : '';
        update_post_meta($id, $k, $f[1] === 'textarea' ? sanitize_textarea_field($raw) : ($k === '_tl_link' ? esc_url_raw(trim($raw)) : sanitize_text_field($raw)));
    }
    update_post_meta($id, '_tl_now', empty($_POST['_tl_now']) ? '0' : '1');
});
add_filter('manage_ap_milestone_posts_columns', function ($c) {
    return ['cb' => $c['cb'], 'title' => 'Milestone', 'tl_order' => 'Order', 'tl_dates' => 'Dates', 'tl_role' => 'Role'];
});
add_action('manage_ap_milestone_posts_custom_column', function ($col, $id) {
    if ($col === 'tl_order') { echo (int) get_post_field('menu_order', $id); }
    if ($col === 'tl_dates') { echo esc_html(get_post_meta($id, '_tl_dates', true)); }
    if ($col === 'tl_role')  { echo esc_html(get_post_meta($id, '_tl_role', true)); }
}, 10, 2);
add_action('pre_get_posts', function ($q) {
    if (is_admin() && $q->is_main_query() && $q->get('post_type') === 'ap_milestone' && !$q->get('orderby')) {
        $q->set('orderby', 'menu_order'); $q->set('order', 'ASC');
    }
});

/** The milestones as originally written; used to fill the Timeline screen the first time. */
function alfredo_tl_seed() {
    return [
        ['The Art Institute of Houston', '1999 – 2002', '1999', 'AAS, Animation Art & Design', 'Placeholder: where it started. What drew you to animation, a teacher or project that stuck with you.', '', false],
        ['Somnio Solutions', '2005 – 2007', '2005', 'Animator', 'Placeholder: first studio job. 3D models, lighting and product visualizations for HP, Texas Instruments, 3M and Freescale.', '3D, Product viz, Motion', false],
        ['Clarus Agency', '2007 – 2014', '2007', 'Designer / Animator', 'Placeholder: seven years of motion storytelling for NetApp, executive presentations for HP, and a keynote for 100 Cisco executives.', 'Storyboards, Iconography, Presentations', false],
        ['Going independent', '2014 – Now', '2014', 'Freelance Designer', 'Placeholder: why you went out on your own. Clients include TBM Council, Jack in the Box, Forcepoint, BazaarVoice, Idera, Dell EMC and National Instruments.', 'Motion, Graphic design, Presentations', false],
        ['Forcepoint SKO, Austin', '2022', '2022', 'Creative direction', "Placeholder: picked to lead the visual storytelling for presentations by Forcepoint's President and CEO.", '', false],
        ['Vital Talent', '2022 – 2023', '2022', 'Senior Designer / Art Director', 'Placeholder: art direction for e-learning at Amgen and Lutron, a full website rebrand, and video production start to finish.', 'Art direction, Video, Brand', false],
        ['Forcepoint SKO, Las Vegas', '2024', '2024', 'Creative direction, AI-assisted design', "Placeholder: reimagined the CFO's keynote with bespoke Midjourney imagery, with hands-on animation and onsite leadership.", 'AI imagery, Keynote', false],
        ['LM Restaurants', '2025', '2025', 'Designer', 'Placeholder: campaigns and promotional design for 10+ restaurant brands, including Carolina Ale House.', 'Campaigns, Print, Email', false],
        ['Motion and graphic design that moves.', 'Today', 'Now', '', "Placeholder: what you're looking for next, and the kind of projects you'd love to take on.", '', true],
    ];
}
/** Fill the Timeline screen once, so it starts with the current milestones instead of empty. */
add_action('admin_init', function () {
    if (get_option('alfredo_tl_seeded') || !current_user_can('edit_posts')) { return; }
    $existing = get_posts(['post_type' => 'ap_milestone', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids']);
    if (!$existing) {
        foreach (alfredo_tl_seed() as $i => $m) {
            $id = wp_insert_post(['post_type' => 'ap_milestone', 'post_status' => 'publish', 'post_title' => $m[0], 'menu_order' => $i + 1]);
            if (!$id || is_wp_error($id)) { continue; }
            update_post_meta($id, '_tl_dates', $m[1]); update_post_meta($id, '_tl_big', $m[2]);
            update_post_meta($id, '_tl_role', $m[3]);  update_post_meta($id, '_tl_text', $m[4]);
            update_post_meta($id, '_tl_tags', $m[5]);  update_post_meta($id, '_tl_now', $m[6] ? '1' : '0');
        }
    }
    update_option('alfredo_tl_seeded', 1);
});

/** Milestones for the About page, top to bottom. Falls back to the original list if the Timeline is empty. */
function alfredo_timeline_items() {
    $posts = get_posts(['post_type' => 'ap_milestone', 'post_status' => 'publish', 'numberposts' => -1,
                        'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC']]);
    $out = [];
    if ($posts) {
        foreach ($posts as $p) {
            $out[] = ['title' => get_the_title($p), 'dates' => get_post_meta($p->ID, '_tl_dates', true),
                'big' => get_post_meta($p->ID, '_tl_big', true), 'role' => get_post_meta($p->ID, '_tl_role', true),
                'text' => get_post_meta($p->ID, '_tl_text', true), 'tags' => get_post_meta($p->ID, '_tl_tags', true), 'link' => get_post_meta($p->ID, '_tl_link', true),
                'now' => get_post_meta($p->ID, '_tl_now', true) === '1'];
        }
        return $out;
    }
    foreach (alfredo_tl_seed() as $m) {
        $out[] = ['title' => $m[0], 'dates' => $m[1], 'big' => $m[2], 'role' => $m[3], 'text' => $m[4], 'tags' => $m[5], 'now' => $m[6]];
    }
    return $out;
}

/* ---------------------------------------------------------------- Editor looks like the site */
add_action('after_setup_theme', function () {
    add_theme_support('editor-styles');
    add_editor_style(['https://use.typekit.net/djd6onk.css', 'assets/css/editor.css']);
});

/* ---------------------------------------------------------------- Project page image size */
add_action('customize_register', function ($wp) {
    $wp->add_section('alfredo_projects', ['title' => 'Project pages', 'panel' => 'alfredo_text',
        'description' => 'How large images appear on each project page. Tall images are also limited to the height of the screen, so a whole image is always visible at once.']);
    $wp->add_setting('alfredo_proj_width', ['default' => '1100', 'transport' => 'refresh',
        'sanitize_callback' => function ($v) { return in_array($v, ['800', '1000', '1100', '1300', '1500'], true) ? $v : '1100'; }]);
    $wp->add_control('alfredo_proj_width', ['label' => 'Image width', 'section' => 'alfredo_projects', 'type' => 'radio',
        'choices' => ['800' => 'Small (800px)', '1000' => 'Medium (1000px)', '1100' => 'Medium-large (1100px, default)', '1300' => 'Large (1300px)', '1500' => 'Full width']]);
    $wp->add_setting('alfredo_proj_long', ['default' => '640', 'transport' => 'refresh',
        'sanitize_callback' => function ($v) { return in_array($v, ['500', '640', '800', '1000'], true) ? $v : '640'; }]);
    $wp->add_control('alfredo_proj_long', ['label' => 'Width of long images (emails, infographics)', 'section' => 'alfredo_projects', 'type' => 'radio',
        'choices' => ['500' => '500px', '640' => '640px (default, about a real email)', '800' => '800px', '1000' => '1000px']]);
    $wp->add_setting('alfredo_proj_height', ['default' => '88', 'transport' => 'refresh',
        'sanitize_callback' => function ($v) { return in_array($v, ['70', '80', '88', '100'], true) ? $v : '88'; }]);
    $wp->add_control('alfredo_proj_height', ['label' => 'Tallest an image can be', 'section' => 'alfredo_projects', 'type' => 'radio',
        'choices' => ['70' => '70% of the screen', '80' => '80% of the screen', '88' => '88% of the screen (default)', '100' => 'Full screen height']]);
});
add_action('wp_head', function () {
    $w = get_theme_mod('alfredo_proj_width', '1100'); $h = get_theme_mod('alfredo_proj_height', '88');
    $w = in_array($w, ['800', '1000', '1100', '1300', '1500'], true) ? $w : '1100';
    $h = in_array($h, ['70', '80', '88', '100'], true) ? $h : '88';
    $l = get_theme_mod('alfredo_proj_long', '640'); $l = in_array($l, ['500', '640', '800', '1000'], true) ? $l : '640';
    echo '<style id="alfredo-proj-size">:root{--proj-w:' . (int) $w . 'px;--proj-h:' . (int) $h . 'vh;--proj-long-w:' . (int) $l . "px}</style>\n";
});
