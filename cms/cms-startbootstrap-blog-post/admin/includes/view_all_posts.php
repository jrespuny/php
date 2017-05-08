<?php 

// Ready for include:
// -> $posts: array


if (isset($successfully_deleted_post)) {
    if ($successfully_deleted_post) {
        echo "<p class=\"bg-success\">Post deleted successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Post could not be deleted</p>";
    }
}

if (isset($successfully_reset_views)) {
    if ($successfully_reset_views) {
        echo "<p class=\"bg-success\">Views reset to 0 successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Unable to reset views to 0</p>";
    }
}

if (isset($successfully_updated_post)) {
    if ($successfully_updated_post) {
        echo "<p class=\"bg-success\">Post updated successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Post could not be updated</p>";
    }
}

if (isset($successfully_toggled_status)) {
    if ($successfully_toggled_status) {
        echo "<p class=\"bg-success\">Post status updated successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Post status could not be updated</p>";
    }
}

if (isset($successfull_bulk_update)) {
    if ($successfull_bulk_update) {
        echo "<p class=\"bg-success\">Bulk update successfull</p>";
    } else {
        echo "<p class=\"bg-danger\">Bulk update unsuccessfull</p>";
    }
}

if (isset($successfull_bulk_reset_views)) {
    if ($successfull_bulk_reset_views) {
        echo "<p class=\"bg-success\">Bulk view reset successfull</p>";
    } else {
        echo "<p class=\"bg-danger\">Bulk view reset unsuccessfull</p>";
    }
}

if (isset($successfull_bulk_clone)) {
    if ($successfull_bulk_clone) {
        echo "<p class=\"bg-success\">Bulk clone successfull</p>";
    } else {
        echo "<p class=\"bg-danger\">Bulk clone unsuccessfull</p>";
    }
}

if (isset($select_before_applying)) {
    if ($select_before_applying) {
        echo "<p class=\"bg-danger\">Mark before applying bulk selection.</p>";
    }
}

if ($posts != NULL) {
    $category_assoc = getCategoriesAssocByID($link);

 ?>
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
                            <div class="form-group">
                                <label for="bulk">Bulk Selection</label>
                                <select name="bulk" id="bulk">
                                    <option value="clone">clone</option>
                                    <option value="reset_views">reset views</option>
                                    <option value="published">publish</option>
                                    <option value="draft">draft</option>
                                    <option value="delete">delete</option>
                                </select>
                                <input class="btn btn-success" type="submit" name="bulk_submit" value="Apply">
                                <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
                            </div>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="bulk_selection" id="bulk_selection"></th>
                                        <th>ID</th>
                                        <th>Author</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Image</th>
                                        <th>Tags</th>
                                        <th>Comments</th>
                                        <th>Date</th>
                                        <th>Views</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php 

    foreach ($posts as $post) {

 ?>
                                    <tr>
                                        <td><input class="checkbox" type="checkbox" name="marked_selection[]" id="marked_selection[]" value="<?php echo $post['post_id'] ?>"></td>
                                        <td><a href="../post.php?id=<?php echo $post['post_id'] ?>"><?php echo $post['post_id'] ?></a></td>
                                        <td><?php echo htmlentities($post['post_author']) ?></td>
                                        <td><?php echo htmlentities($post['post_title']) ?></td>
                                        <td><?php echo $category_assoc[$post['post_category_id']]['cat_title'] ?></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?toggle=' . $post['post_id'] . '&status=' . htmlentities($post['post_status']) ?>"><?php echo htmlentities($post['post_status']) ?></a></td>
                                        <td>
<?php

        if ( !empty($post['post_image'])) {

 ?>
                                            <img src="<?php echo "../images/" . htmlentities($post['post_image']) ?>" alt="<?php echo htmlentities($post['post_image']) ?>" class="img-responsive">
<?php 

        }

 ?>
                                        </td>
                                        <td><?php echo htmlentities($post['post_tags']) ?></td>
                                        <td>
<?php 

        if ( !empty($post['post_comment_count'])) {

 ?>
                                        <a href="comments.php?post_id=<?php echo $post['post_id'] ?>">
<?php 

        }

 ?>
                                        <?php echo htmlentities($post['post_comment_count']) ?>
<?php 

        if ( !empty($post['post_comment_count'])) {

 ?>
                                        </a>
<?php 

        }

 ?>
                                        </td>
                                        <td><?php echo htmlentities($post['post_date']) ?></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?source=show_posts&reset_views=" . $post['post_id']); ?>"><?php echo $post['views'] ?></a></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?source=edit_post&id=" .  $post['post_id'] ?>">Edit</a></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?delete=" .  $post['post_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                                    </tr>
<?php 

    }

 ?>
                                </tbody>
                            </table>
                        </form>
<?php 

}

 ?>