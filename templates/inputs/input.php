<?php if(!empty($args['label'])){ ?>
<div><label><?php echo $args['label'];?></label></div>
<?php } ?>
<input type="<?php echo $args['type'];?>" id="<?php echo $args['id'];?>" name="<?php echo $args['name'];?>" class="<?php echo $args['class'];?>" value="<?php echo $args['value'];?>">
