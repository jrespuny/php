<?php 

if (isset($successfully_inserted_user)) {
	if ($successfully_inserted_user) {
		echo "<p class=\"bg-success\">User added successfully</p>";
	} else {
		echo "<p class=\"bg-danger\">User could not be added. Try with another: User Name</p>";
	}
}

 ?>
<form action="" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label for="user_name">User Name</label>
		<input type="text" class="form-control" name="user_name" id="user_name">
	</div>

	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" class="form-control" name="password" id="password">
	</div>

	<div class="form-group">
		<label for="image">User Image</label>
		<input type="file" name="image" id="image">
	</div>

	<div class="form-group">
		<label for="first_name">First Name</label>
		<input type="text" class="form-control" name="first_name" id="first_name">
	</div>

	<div class="form-group">
		<label for="last_name">Last Name</label>
		<input type="text" class="form-control" name="last_name" id="last_name">
	</div>

	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" class="form-control" name="email" id="email">
	</div>

	<div class="form-group">
		<label for="post_tags">Role</label>
		<select name="role" id="role">
			<option value="admin">Admin</option>
			<option value="subscriber">Subscriber</option>
		</select>
	</div>

	<div class="form-group">
		<input class="btn btn-primary" type="submit" name="create_user" value="Add User">
	</div>
</form>