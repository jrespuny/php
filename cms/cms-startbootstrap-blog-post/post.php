<?php 

include "includes/db.php";

$link = openDB();

if (isset($_GET['id'])) {
    $post['post_id'] = $_GET['id'];
    $post_array = getPost($link, $post);
    if ($post_array === NULL) {
        $post = NULL;
    } else {
        $post = empty($post_array) ? [] : $post_array[0];

        if (isset($_POST['create_comment'])) {
            if (empty($_POST['author']) || empty($_POST['email']) || empty($_POST['comment'])) {
                $no_empty_fields_allowed = TRUE;
            } else {
                $comment['comment_post_id'] = $post['post_id'];
                $comment['comment_author'] = $_POST['author'];
                $comment['comment_email'] = $_POST['email'];
                $comment['comment_content'] = $_POST['comment'];
                $comment['comment_date'] = date("Y-m-d");

                $successfully_inserted_comment = insertComment($link, $comment);
            }
        }

        if (isset($post['post_id'])) {
            logViewIncrementForPost($link, $post);
            $approved = TRUE;
            $comments = getComments($link, $approved , $post);
        }
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

if (empty($post)) {
    echo "<h1>Post Unavailable</h1>";
} else {

 ?>
            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->

                <!-- Title -->
                <h1><?php echo htmlentities($post['post_title'], ENT_QUOTES) ?><?php echo isset($_SESSION['user']) ? " - <a href=\"admin/posts.php?source=edit_post&id=${post['post_id']}\">[Edit]</a>" : "" ?></h1>

                <!-- Author -->
                <p class="lead">
                    by <a href="#"><?php echo htmlentities($post['post_author'], ENT_QUOTES) ?></a>
                </p>

                <hr>

                <!-- Date/Time -->
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo htmlentities(date('F j, Y', strtotime($post['post_date'])), ENT_QUOTES) ?></p>

                <hr>
<?php 

    if (isset($post['post_image'])) {

 ?>
                <!-- Preview Image -->
                <img class="img-responsive" src="images/<?php echo htmlentities($post['post_image']) ?>" alt="">

                <hr>
<?php 

    }

 ?>
                <!-- Post Content -->
                <p class="lead">
                <?php echo $post['post_content'] ?>
                <hr>

                <!-- Blog Comments -->

                <!-- Comments Form -->
                <div class="well">
<?php 

    if (isset($successfully_inserted_comment)) {
        if ($successfully_inserted_comment) {
            echo "<p class=\"bg-success\">Comment sent successfully. Pending to be approved</p>";
        } else {
            echo "<p class=\"bg-danger\">Comment could not be sent</p>";
        }
    }

    if (isset($no_empty_fields_allowed)) {
        if ($no_empty_fields_allowed) {
            echo "<p class=\"bg-danger\">All fields need to be filled</p>";
        }
    }

 ?>
                    <h4>Leave a Comment:</h4>
                    <form role="form" action="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?id=' . $post['post_id'] ?>" method="POST">
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input type="text" class="form-control" name="author" id="author">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                        </div>
                        <button name="create_comment" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
<?php 

    if (isset($comments)) {
        foreach ($comments as $comment) {

 ?>

                <!-- Comment -->
                 <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo htmlentities($comment['comment_author']) ?>
                            <small><?php echo htmlentities(date('F j, Y', strtotime($comment['comment_date']))) ?></small>
                        </h4>
<?php echo htmlentities($comment['comment_content']) ?>
                    </div>
                </div>

<?php 

        }
    }

 ?>
            </div>
<?php 

}

 ?>
<?php include "includes/sidebar.php" ?>

        </div>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php" ?>