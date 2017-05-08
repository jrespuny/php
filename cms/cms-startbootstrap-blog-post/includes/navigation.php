<?php

$categories = isset($link)? getCategories($link) : NULL;

?>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">CMS Front</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<?php

if ($categories != NULL) {
    echo "<ul class=\"nav navbar-nav\">";

    foreach ($categories as $category) {
        echo "<li>" . PHP_EOL;
        echo "<a href=\"" . $category['cat_id'] . "\">" . htmlentities($category['cat_title']) . "</a>";
        echo "</li>" . PHP_EOL;
    }

    if (isset($_SESSION['user'])) {
        echo "<li>" . PHP_EOL;
        echo "<a href=\"admin\">[Admin]</a>";
        echo "</li>" . PHP_EOL;        
    }

    echo "</ul>" . PHP_EOL;
}

  ?>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>