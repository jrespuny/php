<?php 

if (isset($successfully_inserted_post)) {
	if ($successfully_inserted_post) {
		echo "<p class=\"bg-success\">Post created successfully: <a href=\"../post.php?id=${post['post_id']}\">View Post</a></p>";
	} else {
		echo "<p class=\"bg-danger\">Post could not be created</p>";
	}
}

$category_array = getCategories($link);

 ?>
<form action="" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label for="title">Post Title</label>
		<input type="text" class="form-control" name="title" id="title">
	</div>

<?php 

if (isset($category_array) && count($category_array)) {

 ?>
	<div class="form-group">
		<label for="post_category_id">Post Category Id</label>
		<select name="post_category_id" id="post_category_id" class="form-control">
<?php 

	foreach ($category_array as $category) {
		echo "<option value=\"${category['cat_id']}\">${category['cat_title']}</option>";
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
            <option value="draft">draft</option>
            <option value="published">published</option>
        </select>
	</div>

	<div class="form-group">
		<label for="image">Post Image</label>
		<input type="file" name="image" id="image">
	</div>

	<div class="form-group">
		<label for="post_tags">Post Tags</label>
		<input type="text" class="form-control" name="post_tags" id="post_tags">
	</div>

	<div class="form-group">
		<label for="post_content">Post Content</label>
		<textarea class="form-control" name="post_content" id="post_content" cols="30" rows="10"></textarea>
	</div>

	<div class="form-group">
		<input class="btn btn-primary" type="submit" name="create_post" value="Create Post">
	</div>
</form>