<form id="waitlist-form">
    <div id="waitlist-form-content" style="display:flex;">
        <?php if(!is_user_logged_in()){ ?>
            <input type="email" id="user-email" placeholder="E-mail" required>
        <?php } ?>
        <button type="submit" class="waitlist-button" data-subscribed="<?php echo $args['isSubscribed'];?>" data-product-id="<?php echo $args['productId'];?>"><?php echo $args['text'];?></button>
    </div>
</form>