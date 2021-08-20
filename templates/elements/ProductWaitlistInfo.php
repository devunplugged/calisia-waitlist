<p class="form-field">
    <label><?php _e('Waitlist','calisia-waitlist'); ?></label>
    <span>
        <?php printf( __( 'Currently waiting for that product: %d.', 'calisia-waitlist' ), $args['count'] ); ?></br>
        <a href="<?php echo $args['waitlistUrl'];?>"><?php _e( 'Show waitlist', 'calisia-waitlist' ) ?></a>
    </span>
</p>