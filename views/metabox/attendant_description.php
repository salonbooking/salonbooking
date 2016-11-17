<?php
/**
 * @var stdClass $post
 */
?>
<label class="screen-reader-text" for="excerpt">
    <?php _e('Assistant Description', 'salon-booking-system') ?>
</label>
<textarea rows="1" cols="40" name="excerpt"
          id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
<p><?php _e('A very short description of this assistant. It is optional', 'salon-booking-system'); ?></p>