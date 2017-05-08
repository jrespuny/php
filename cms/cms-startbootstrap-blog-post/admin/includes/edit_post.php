<?php 

if (isset($_GET['id'])) {
    $post['post_id'] = $_GET['id'];
    $post_array = getPost($link, $post);
    if ($post_array === NULL) {
        $post = NULL;
    } else {
        $post = count($post_array) ? $post_array[0] : [];

        $category_array = getCategories($link);
    }
}

if (empty($post)) {
    echo "<h1>Post not available</h1>";
} else  {

 ?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="post_id" value="<?php echo $post['post_id'] ?>">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo isset($post['post_title']) ? htmlentities($post['post_title']) : "" ?>">
    </div>

<?php 

    if (isset($category_array) && count($category_array)) {

 ?>
    <div class="form-group">
        <label for="post-category">Category</label>
        <select name="post_category_id" id="post_category_id" class="form-control">
<?php 

        foreach ($category_array as $category) {
            $selected = ($category['cat_id'] == $post['post_category_id']) ? " selected" : "";
            echo "<option value=\"${category['cat_id']}\"$selected>${category['cat_title']}</option>";
        }

 ?>
        </select>
    </div>
<?php 

    }

 ?>

    <div class="form-group">
        <label for="post_status">Post Status</label>
        <select name="post_status" id="post_status">
            <option value="draft"<?php echo ($post['post_status'] === "draft") ? " selected" : "" ?>>draft</option>
            <option value="published"<?php echo ($post['post_status'] === "published") ? " selected" : "" ?>>published</option>
        </select>
    </div>

    <div class="form-group">
        <label for="post_image">Selecte New Post Image</label>
        <input type="file" name="image">
    </div>
    <input type="hidden" name="previous_image" value="<?php echo $post['post_image'] ?>">
    <div class="form-group">
        <label for="post_remove_previous_image">No image</label>
        <input type="checkbox" name="post_remove_previous_image" id="post_remove_previous_image">
    </div>

<?php 

    if ($post['post_image']) {

 ?>
    <div class="form-group">
        <img src="<?php echo "../images/" . $post['post_image'] ?>" class="img-responsive">
    </div>

<?php 

    }

 ?>
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags" value="<?php echo isset($post['post_tags']) ? htmlentities($post['post_tags']) : "" ?>">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="" cols="30" rows="10"><?php echo isset($post['post_content']) ? htmlentities($post['post_content']) : "" ?></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_post" value="Update Post">
    </div>
</form>
<?php 

}

 ?>