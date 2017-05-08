<?php 

// Ready for include:
// -> $posts: array


if (isset($successfully_deleted_user)) {
    if ($successfully_deleted_user) {
        echo "<p class=\"bg-success\">User deleted successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">User could not be deleted</p>";
    }
}

if (isset($successfully_updated_user)) {
    if ($successfully_updated_user) {
        echo "<p class=\"bg-success\">User updated successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">User could not be updated</p>";
    }
}

if (isset($successfully_toggled_role)) {
    if ($successfully_toggled_role) {
        echo "<p class=\"bg-success\">User role updated successfully</p>";
    } else {
        echo "<p class=\"bg-danger\">User role could not be updated</p>";
    }
}


if ($users != NULL) {

 ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Image</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
<?php 

    foreach ($users as $user) {

 ?>
                                <tr>
                                    <td><?php echo $user['user_id'] ?></td>
                                    <td><?php echo htmlentities($user['user_name']) ?></td>
                                    <td>
<?php

        if ( !empty($user['user_image'])) {

 ?>
                                        <img src="<?php echo "../images/" . htmlentities($user['user_image']) ?>" alt="<?php echo htmlentities($user['user_image']) ?>" class="img-responsive">
<?php 

        }

 ?>
                                    </td>
                                    <td><?php echo htmlentities($user['user_first_name']) ?></td>
                                    <td><?php echo htmlentities($user['user_last_name']) ?></td>
                                    <td><?php echo htmlentities($user['user_email']) ?></td>
                                    <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?toggle=' . $user['user_id'] . '&role=' . htmlentities($user['user_role']) ?>"><?php echo htmlentities($user['user_role']) ?></a></td>
                                    <td><?php echo htmlentities($user['user_date']) ?></td>
                                    <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?source=edit_user&id=" .  $user['user_id'] ?>">Edit</a></td>
                                    <td><a href="<?php echo htmlentities($_SERVER['PHP_SELF']) . "?delete=" .  $user['user_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                                </tr>
<?php 

    }

 ?>
                            </tbody>
                        </table>
<?php 

}

 ?>