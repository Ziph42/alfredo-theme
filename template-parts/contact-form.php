<?php
/**
 * Contact form. Submissions post to admin-post.php (see functions.php),
 * are saved under Messages in the dashboard, and emailed to the site admin.
 */
if (!defined('ABSPATH')) { exit; }
$sent = isset($_GET['sent']) ? sanitize_key(wp_unslash($_GET['sent'])) : '';
$note = '';
$note_class = '';
if ($sent === '1') { $note = "Thanks, got it. I'll get back to you within a day or two."; $note_class = ' ok'; }
elseif ($sent === '0') { $note = 'Something went wrong. Please check the fields, or email me directly.'; $note_class = ' err'; }
?>
    <form class="cform" id="cform" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>
      <input type="hidden" name="action" value="alfredo_contact">
      <input type="hidden" name="_e" value="">
      <div class="cf-two">
        <label class="fld"><span>Name</span><input name="name" required autocomplete="name" placeholder="Your name"></label>
        <label class="fld"><span>Email</span><input type="email" name="email" required autocomplete="email" placeholder="you@company.com"></label>
      </div>
      <label class="fld"><span>Company <i>(optional)</i></span><input name="company" autocomplete="organization" placeholder="Where you work"></label>
      <fieldset class="chips"><legend>What do you need?</legend>
        <div class="opts">
          <label><input type="checkbox" name="need[]" value="Motion design"><span>Motion design</span></label>
          <label><input type="checkbox" name="need[]" value="Graphic design"><span>Graphic design</span></label>
          <label><input type="checkbox" name="need[]" value="Presentation"><span>Presentation</span></label>
          <label><input type="checkbox" name="need[]" value="Branding"><span>Branding</span></label>
          <label><input type="checkbox" name="need[]" value="Something else"><span>Something else</span></label>
        </div>
      </fieldset>
      <label class="fld"><span>Tell me about it</span><textarea name="message" rows="4" required placeholder="The project, the timeline, anything useful"></textarea></label>
      <div class="hp" aria-hidden="true"><label>Leave this empty<input name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="cf-foot">
        <button class="cta send" type="submit">Send it</button>
        <span class="cf-alt">or email <a href="mailto:freddypaterno@gmail.com">freddypaterno@gmail.com</a></span>
      </div>
      <p class="cf-note<?php echo $note_class; ?>" id="cfNote" role="status"><?php echo esc_html($note); ?></p>
    </form>
<script>(function(){var f=document.getElementById('cform');if(!f)return;var t0=Date.now();f.addEventListener('submit',function(){var e=f.querySelector('input[name="_e"]');if(e)e.value=String(Date.now()-t0);});})();</script>
