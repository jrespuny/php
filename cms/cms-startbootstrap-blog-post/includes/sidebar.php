<?php

$categories = isset($link)? getCategories($link) : NULL;

?>
            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">
                <!-- Registration -->
                <div class="well">
                    <h4><a href="registration.php">New subscriber</a></h4>
                </div>
                <!-- Login -->
                <div class="well">
                    <h4>Login</h4>
<?php 

if (isset($_GET['invalid_login'])) {
    echo "<strong>Invalid login for: ${_GET['invalid_login']}</strong>";
}

 ?>
                    <form action="login.php" method="POST">
                        <div class="form-group">
                            <input type="text" name="user_name" placeholder="User Name" class="form-control">
                        </div>
                        <div class="input-group">
                            <input type="password" name="password" placeholder="Password" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" name="login" type="submit">Submit</button>
                            </span>
                        </div>
                    </form>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Blog Search</h4>
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                            </button>
                            </span>
                        </div>
                    </form>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
<?php

if ($categories != NULL) {
    echo "<ul class=\"list-unstyled\">" . PHP_EOL;

    foreach ($categories as $category) {
        echo "<li><a href=\"?category_id=" . $category['cat_id'] . "\">" . htmlentities($category['cat_title']) . "</a>" . PHP_EOL;
        echo "</li>" . PHP_EOL;
    }

    echo "</ul>" . PHP_EOL;
}

 ?>
                        </div>
<!--                         <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                    <!-- /.row -->
                </div>

                <!-- Side Widget Well -->
                <div class="well">
                    <h4>Side Widget Well</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
                </div>

            </div>