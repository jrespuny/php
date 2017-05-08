<?php include "includes/header.php" ?>
<?php 

include "../includes/db.php";

define('CREATE', 'CREATE');
define('DEL', 'DEL');
define('PREPARE_EDIT', 'PREPARE_EDIT');
define('EDIT', 'EDIT');


$link = openDB();

accountForOnlineUser($link, $_SESSION['user'], session_id());
$total_online_users = totalOnlineUsers($link);

// Create new category
if (isset($_POST['create'])) {
    $category['cat_title'] = $_POST['cat_title'];
    $category[CREATE] = insertCategory($link, $category);
}

// Prepare edit of existing category
if (isset($_GET['edit_id'])) {
    $category['cat_id'] = $_GET['edit_id'];
    $category['cat_title'] = $_GET['title'];
    $category[PREPARE_EDIT] = TRUE;
}

// Edit existing category
if (isset($_POST['edit'])) {
    $category['cat_id'] = $_POST['cat_id'];
    $category['cat_title'] = $_POST['cat_title'];
    $category[EDIT] = editCategory($link, $category);
}

// Delete existing category
if (isset($_GET['delete_id'])) {
    $category['cat_id'] = $_GET['delete_id'];
    $category['cat_title'] = $_GET['title'];
    $category[DEL] = deleteCategory($link, $category);
}

$categories = ($link != NULL) ? getCategories($link) : NULL;
$navbar = "categories";

 ?>

        <!-- Navigation -->
<?php include "includes/navigation.php" ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to admin
                            <small><?php echo htmlentities($_SESSION['user']['user_name']) ?></small>
                        </h1>

                        <div class="col-xs-6">
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
<?php 

if (isset($category[CREATE])) {
    echo "<div class=\"form-group\">";
    if ( !$category[CREATE]) {
       echo "<p class=\"bg-danger\">ERROR creating new category</p>";
    } else {
        echo "<p class=\"bg-success\">New category <strong>${category['cat_title']}</strong> successfully created.</p>";
    }
    echo "</div>";
}

if (isset($category[DEL])) {
    echo "<div class=\"form-group\">";
    if ( !$category[DEL]) {
       echo "<p class=\"bg-danger\">ERROR deleting category: <strong>" . $category['cat_title'] . "</strong></p>";
    } else {
        echo "<p class=\"bg-success\">Category <strong>${category['cat_title']}</strong> successfully deleted.</p>";
    }
    echo "</div>";
}

 ?>
                                <div class="form-group">
                                    <label for="cat_title">New Category&nbsp;</label>
                                    <input type="text" name="cat_title" id="cat_title" placeholder="Name" class="from-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="create" value="Add Category" class="btn btn-primary">
                                </div>
                            </form>
<?php

if (isset($category[PREPARE_EDIT])) {

  ?>
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
<?php 

    if (isset($category[EDIT])) {
        echo "<div class=\"form-group\">";
        if ( !$category[EDIT]) {
           echo "<p class=\"bg-danger\">ERROR updating category <strong>:&nbsp;&#35;${category['cat_id']}</strong></p>";
        } else {
            echo "<p class=\"bg-success\">Category <strong>:&nbsp;&#35;${category['cat_id']}:&nbsp;${category['cat_title']}</strong> successfully updated.</p>";
        }
        echo "</div>";
    }

 ?>
                                <div class="form-group">
                                    <label for="update_cat_title">Update Category<?php echo ":&nbsp;&#35;" . $category['cat_id'] ?></label>
                                    <input type="hidden" name="cat_id" value="<?php echo $category['cat_id'] ?>">
                                    <input type="text" name="cat_title" id="update_cat_title" placeholder="Name" class="from-control" value="<?php echo $category['cat_title'] ?>">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="edit" value="Update Category" class="btn btn-primary">
                                </div>
                            </form>
<?php 

}

 ?>
                        </div>
                        <div class="col-xs-6">
<?php 

if ($categories != NULL) {

 ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php 

    foreach ($categories as $category) {

 ?>
                                    <tr>
                                        <td><?php echo $category['cat_id'] ?></td>
                                        <td><?php echo htmlentities($category['cat_title']) ?></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?edit_id=" . $category['cat_id'] . "&title=" . htmlentities($category['cat_title']) ?>">Edit</a></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?delete_id=" . $category['cat_id'] . "&title=" . htmlentities($category['cat_title']) ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                                    </tr>
<?php

    }

 ?>
                                </tbody>
                            </table>
<?php 

}

 ?>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include "includes/footer.php" ?>
