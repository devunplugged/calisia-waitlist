<h1><?php echo $args['title']; ?></h1>

<?php
echo '<pre>';
foreach(_get_cron_array() as $key=>$value){
    if(isset($value['calisia_waitlist_cron_hook']))
        echo wp_date("Y-m-d H:i", $key);
}
echo '</pre>';
?>

<form method="GET" style="display:flex;flex-wrap:wrap;">
    <?php
        echo $args['templateVars']['controls']['hidden'];
        echo $args['templateVars']['controls']['sentSelect'];
        echo $args['templateVars']['controls']['customerSelect'];
        echo $args['templateVars']['controls']['submitButton'];
    ?>
</form>

<form method="POST">
    <?php
        $args['templateVars']['table']->prepare_items();
        $args['templateVars']['table']->display();
    ?>
</form>