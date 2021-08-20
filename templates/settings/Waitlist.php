<h1><?php echo $args['title']; ?></h1>

<?php echo $args['templateVars']['info']; ?>

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