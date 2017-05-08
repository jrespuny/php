<?php 

// Ready for include:
// -> $comments: array


if (isset($successfully_deleted_comment)) {
    if ($successfully_deleted_comment) {
        echo "<p class=\"bg-success\">Comment deleted successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Comment could not be deleted</p>";
    }
}

if (isset($successfully_updated_comment)) {
    if ($successfully_updated_comment) {
        echo "<p class=\"bg-success\">Comment updated successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Comment could not be updated</p>";
    }
}

if (isset($successfully_toggled_status)) {
    if ($successfully_toggled_status) {
        echo "<p class=\"bg-success\">Comment status updated successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">Comment status could not be updated</p>";
    }
}

if (isset($successfull_bulk_update)) {
    if ($successfull_bulk_update) {
        echo "<p class=\"bg-success\">Bulk update successfull</p>";
    } else {
        echo "<p class=\"bg-danger\">Bulk update unsuccessfull</p>";
    }
}

if (isset($select_before_applying)) {
    if ($select_before_applying) {
        echo "<p class=\"bg-danger\">Mark before applying bulk selection.</p>";
    }
}


if ($comments != NULL) {

 ?>
                         <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
                            <div class="form-group">
                                <label for="bulk">Bulk Selection</label>
                                <select name="bulk" id="bulk">
                                    <option value="approved">approve</option>
                                    <option value="unapproved">unapprove</option>
                                    <option value="delete">delete</option>
                                </select>
                                <input class="btn btn-success" type="submit" name="bulk_submit" value="Apply">
                            </div>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="bulk_selection" id="bulk_selection"></th>
                                        <th>ID</th>
                                        <th>Author</th>
                                        <th>Email</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>In Response to</th>
                                        <th>Date</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php 

    foreach ($comments as $comment) {

 ?>
                                    <tr>
                                        <td><input class="checkbox" type="checkbox" name="marked_selection[]" id="marked_selection[]" value="<?php echo $comment['comment_id'] ?>"></td>
                                        <td><?php echo $comment['comment_id'] ?></td>
                                        <td><?php echo htmlentities($comment['comment_author']) ?></td>
                                        <td><?php echo htmlentities($comment['comment_email']) ?></td>
                                        <td><?php echo htmlentities($comment['comment_content']) ?></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?toggle=' . $comment['comment_id'] . '&status=' . htmlentities($comment['comment_status']) . (empty($uri['query']) ? "" : "&") . implode("&", $uri['query']) ?>"><?php echo htmlentities($comment['comment_status']) ?></a></td>
                                        <td>
<?php 
        $post['post_id'] = $comment['comment_post_id'];
        $post_array = getPost($link, $post);
        $post = count($post_array) ? $post_array[0] : NULL;
        echo "<a href=\"../post.php?id=" . $post['post_id'] . "\">" . htmlentities($post['post_title']) . "</a>";
 ?>
                                        </td>
                                        <td><?php echo htmlentities($comment['comment_date']) ?></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?source=edit_comment&id=" .  $comment['comment_id'] . (empty($uri['query']) ? "" : "&") . implode("&", $uri['query']) ?>">Edit</a></td>
                                        <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?delete=" .  $comment['comment_id'] . (empty($uri['query']) ? "" : "&") . implode("&", $uri['query']) ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
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