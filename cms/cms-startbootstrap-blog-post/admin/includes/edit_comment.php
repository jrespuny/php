<?php 

if (isset($_GET['id'])) {
    $comment['comment_id'] = $_GET['id'];
    $comment_array = getComment($link, $comment);
    if ($comment_array != NULL) {
        $comment = count($comment_array) ? $comment_array[0] : [];
    }
}

 ?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) . (empty($uri['query']) ? "" : "?") . implode("&", $uri['query']) ?>" method="POST">
    <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id'] ?>">
    <div class="form-group">
        <label for="title">Comment Author</label>
        <input type="text" class="form-control" name="author" value="<?php echo isset($comment['comment_author']) ? htmlentities($comment['comment_author']) : "" ?>">
    </div>

    <div class="form-group">
        <label for="title">Comment Email</label>
        <input type="text" class="form-control" name="email" value="<?php echo isset($comment['comment_email']) ? htmlentities($comment['comment_email']) : "" ?>">
    </div>

    <div class="form-group">
        <label for="title">Comment Content</label>
        <input type="text" class="form-control" name="content" value="<?php echo isset($comment['comment_content']) ? htmlentities($comment['comment_content']) : "" ?>">
    </div>

    <div class="form-group">
        <label for="status">Comment Status</label>
        <select name="status" id="status">
            <option value="approved"<?php echo ($comment['comment_status'] === "approved") ? " selected" : "" ?>>approved</option>
            <option value="unapproved"<?php echo ($comment['comment_status'] === "unapproved") ? " selected" : "" ?>>unapproved</option>
        </select>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_comment" value="Update Comment">
    </div>
</form>