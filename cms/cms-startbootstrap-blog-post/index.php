<?php 

define('MAX_POST_CONTENT_LENGTH', 100);

include "includes/db.php";

$link = openDB();

$uri['page'] = isset($_GET['page']) ? ($_GET['page'] - 1) : 0;
$uri['total'] = total($link, "posts");
$uri['query'] = [];

if (isset($_GET['category_id'])) {
    $category['cat_id'] = $_GET['category_id'];
    list($uri['total'], $posts) = getPostsForCategory($link, $category, TRUE, $uri['page']);

    array_push($uri['query'], "category_id=" . $_GET['category_id']);

} else if (isset($_GET['user_id'])) {
    $post['post_user_id'] = $_GET['user_id'];
    list($uri['total'], $posts) = getPostsFor_post_user_id($link, $post, $uri['page']);

    array_push($uri['query'], "user_id=" . $_GET['user_id']);

} else {
    $search = isset($_POST['search']) ? $_POST['search'] : (isset($_GET['search']) ? $_GET['search'] : NULL);
    list($uri['total'], $posts) = getPosts($link, $search, TRUE, $uri['page']);

    if ($search !== NULL) {
        array_push($uri['query'], "search=" . $search);
    }
}

 ?>
<?php include "includes/header.php" ?>

    <!-- Navigation -->
<?php include "includes/navigation.php" ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
<?php

if ($posts !== NULL) {
    if (empty($posts)) {
        echo "<h1 class=\"text-center\">NO POSTS, SORRY</h1>";
    }
    foreach ($posts as $post) {

 ?>
            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->

                <!-- Title -->
<?php 


 ?>
                <h1><a href="post.php?id=<?php echo $post['post_id'] ?>"><?php echo htmlentities($post['post_title'], ENT_QUOTES) ?></a><?php echo isset($_SESSION['user']) ? " - <a href=\"admin/posts.php?source=edit_post&id=${post['post_id']}\">[Edit]</a>" : "" ?></h1>

                <!-- Author -->
                <p class="lead">
                    by <a href="?user_id=<?php echo $post['post_user_id'] ?>"><?php echo htmlentities($post['post_author'], ENT_QUOTES) ?></a>
                </p>

                <hr>

                <!-- Date/Time -->
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo htmlentities(date('F j, Y \a\t g:i A', strtotime($post['post_date'])), ENT_QUOTES) ?></p>

                <hr>
<?php

        if ( !empty($post['post_image'])) {

 ?>
                <!-- Preview Image -->
                <a href="post.php?id=<?php echo $post['post_id'] ?>">
                    <img class="img-responsive" src="images/<?php echo htmlentities($post['post_image']) ?>" alt="">

                    <hr>
                </a>
<?php 

}

 ?>
                <!-- Post Content -->
                <p class="lead">
                <?php echo !empty(strip_tags($post['post_content'])) ? substr(strip_tags($post['post_content']), 0, MAX_POST_CONTENT_LENGTH) . " <a href=\"post.php?id=${post['post_id']}\">...</a>" : $post['post_content'] ?>

                </p>
                <hr>

                <!-- Blog Comments -->

                <!-- Comments Form -->
<!--                 <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form">
                        <div class="form-group">
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr> -->

                <!-- Posted Comments -->

                <!-- Comment -->
<!--                 <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">Start Bootstrap
                            <small>August 25, 2014 at 9:30 PM</small>
                        </h4>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                    </div>
                </div>

                <!-- Comment -->
<!--                 <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">Start Bootstrap
                            <small>August 25, 2014 at 9:30 PM</small>
                        </h4>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus. -->
                        <!-- Nested Comment -->
<!--                         <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="http://placehold.it/64x64" alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">Nested Start Bootstrap
                                    <small>August 25, 2014 at 9:30 PM</small>
                                </h4>
                                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                            </div>
                        </div> -->
                        <!-- End Nested Comment -->
<!--                     </div>
                </div>
 -->
            </div>
<?php 

    }
}

 ?>
<?php include "includes/sidebar.php" ?>

        </div>
        <!-- /.row -->

        <hr>
        <ul class="pager">
            <li>
<?php 

for ($i = 1; $i <= ceil($uri['total'] / MAX_PAGE); $i++) {
    $class = (($i - 1) == $uri['page']) ? "class=\"active\" " : "";
    echo "<a " . $class .  "href=\"" . htmlentities($_SERVER['PHP_SELF'] . "?page={$i}" . (empty($uri['query']) ? "" : "&") . implode("&", $uri['query'])) . "\">{$i}</a>";
}

 ?>
            </li>
        </ul>
<?php include "includes/footer.php" ?>