<?php 

include "includes/db.php";

$link = openDB();

if (isset($_POST['submit'])) {
    if (empty($_POST['user_name']) || empty($_POST['email']) || empty($_POST['password'])) {
        $no_empty_fields_allowed = TRUE;
    } else {
        $user['user_name'] = $_POST['user_name'];
        $user['user_password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user['user_email'] = $_POST['email'];
        $user['user_date'] = date("Y-m-d");

        $successfully_registered_user = registerUser($link, $user);
    }
}

 ?>
<?php include "includes/header.php" ?>

    <!-- Navigation -->
<?php include "includes/navigation.php" ?>

    <!-- Page Content -->
    <div class="container">

        <section id="login">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <div class="form-wrap">
                            <h1>Register</h1>
<?php 

    if (isset($successfully_registered_user)) {
        if ($successfully_registered_user) {
            echo "<p class=\"bg-success\">User registered successfully</p>";
        } else {
            echo "<p class=\"bg-danger\">Unable to register user successfully. Try another user name</p>";
        }
    }

    if (isset($no_empty_fields_allowed)) {
        if ($no_empty_fields_allowed) {
            echo "<p class=\"bg-danger\">All fields need to be filled</p>";
        }
    }

 ?>
                                <form role="form" action="" method="POST" id="login-form" autocomplete="off">
                                    <div class="form-group">
                                        <label for="user_name" class="sr-only">Username</label>
                                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Desired Username">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="sr-only">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="sr-only">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                    </div>

                                    <input type="submit" name="submit" id="submit" class="btn btn-custom btn-lg btn-block" value="Register">
                                </form>
                        </div>
                    </div>
                </div>
            </div>        
        </section>

        <!-- /.row -->

        <hr>

<?php include "includes/footer.php" ?>