<?php if(!empty($args['label'])){ ?>
<div><label><?php echo $args['label'];?></label></div>
<?php } ?>
<select id="<?php echo $args['id'];?>" name="<?php echo $args['name'];?>" class="<?php echo $args['class'];?>">
    <?php
        foreach($args['options'] as $key => $value){
            echo "<option value='$value'";
            if($args['value'] === $value)
                echo 'selected';
            echo ">$key</option>";
        }
    ?>
</select>
