<div style="display:flex;flex-wrap:wrap;align-items:stretch ;text-align:center;margin-bottom:2em;">
    <div style="flex-grow:1;border:solid whitesmoke 1px;display:flex;justify-content:center;align-items:center;">
        <?php echo $args['product']->get_image('thumbnail');?>
    </div>
    <div style="flex-grow:99;border:solid whitesmoke 1px;display:flex;justify-content:center;align-items:center;">
        <h3 style="margin-top:20px;">
            <a href="<?php echo get_permalink( $args['product']->get_id() );?>"><?php echo $args['product']->get_data()['name'];?></a>
        </h3>
    </div>
</div>
