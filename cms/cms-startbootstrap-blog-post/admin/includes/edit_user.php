<?php 

if (isset($_GET['id'])) {
    $user['user_id'] = $_GET['id'];
    $user_array = getUser_user_id($link, $user);
    if ($user_array === NULL) {
        $user = NULL;
    } else {
        $user = ( !empty($user_array)) ? $user_array[0] : [];
    }
}

if (empty($user)) {
    echo "<h1>User not available</h1>";
} else  {

 ?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo $user['user_id'] ?>">
    <div class="form-group">
        <label for="user_name">User Name</label>
        <input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo isset($user['user_name']) ? $user['user_name'] : "" ?>">
    </div>

    <div class="form-group">
        <label for="password">Password (Blank to leave unchanged)</label>
        <input type="password" class="form-control" name="password" id="password">
    </div>

    <div class="form-group">
        <label for="image">Selecte New User Image</label>
        <input type="file" name="image" id="image">
    </div>
    <input type="hidden" name="previous_image" value="<?php echo $user['user_image'] ?>">
    <div class="form-group">
        <label for="remove_previous_image">No image</label>
        <input type="checkbox" name="remove_previous_image" id="remove_previous_image">
    </div>

<?php 

    if ($user['user_image']) {

 ?>
    <div class="form-group">
        <img src="<?php echo "../images/" . $user['user_image'] ?>" class="img-responsive">
    </div>

<?php 

    }

 ?>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo isset($user['user_first_name']) ? $user['user_first_name'] : "" ?>">
    </div>

    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo isset($user['user_last_name']) ? $user['user_last_name'] : "" ?>">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($user['user_email']) ? $user['user_email'] : "" ?>">
    </div>

    <div class="form-group">
        <label for="user_role">Role</label>
        <select name="role" id="role">
            <option value="admin"<?php echo ($user['user_role'] === "admin") ? " selected" : "" ?>>admin</option>
            <option value="subscriber"<?php echo ($user['user_role'] !== "admin") ? " selected" : "" ?>>subscriber</option>
        </select>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Update User">
    </div>
</form>
<?php 

}

 ?>