<?php include "includes/header.php" ?>
<?php 

include "../includes/db.php";

define('ADD_USER', 'add_user');
define('EDIT_USER', 'edit_user');
define('DEFAULT_CASE', 'show_users');

define('INCLUDES_ADD', 'includes/add_user.php');
define('INCLUDES_EDIT', 'includes/edit_user.php');
define('INCLUDES_VIEW', 'includes/view_all_users.php');


$link = openDB();

accountForOnlineUser($link, $_SESSION['user'], session_id());
$total_online_users = totalOnlineUsers($link);

$source = isset($_GET['source']) ? $_GET['source'] : DEFAULT_CASE;

switch ($source) {
    case ADD_USER:
        if (isset($_POST['create_user'])) {
            $user['user_name'] = $_POST['user_name'];
            $user['user_password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $user['user_first_name'] = $_POST['first_name'];
            $user['user_last_name'] = $_POST['last_name'];
            $user['user_email'] = $_POST['email'];

            $upload_dir = '../images/';
            $base_name = basename($_FILES['image']['tmp_name']);
            $upload_file = $upload_dir . $base_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                $user['user_image'] = $base_name;
            } else {
                $user['user_image'] = NULL;
            }

            $user['user_role'] = $_POST['role'];
            $user['user_date'] = date('Y-m-d');

            $successfully_inserted_user = insertUser($link, $user);
        }

        $include_file = INCLUDES_ADD;
        break;
    
    case EDIT_USER:
        $include_file = INCLUDES_EDIT;
        break;
        
    default:
        if (isset($_GET['delete'])) {
            $user['user_id'] = $_GET['delete'];
            $successfully_deleted_user = deleteUser($link, $user);
        }

        if (isset($_POST['edit_user'])) {
            $user['user_id'] = $_POST['user_id'];
            $user['user_name'] = $_POST['user_name'];

            if (strlen($_POST['password'])) {
                $user['user_password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } else {
                $user['user_password'] = NULL;
            }
            
            $user['user_first_name'] = $_POST['first_name'];
            $user['user_last_name'] = $_POST['last_name'];
            $user['user_email'] = $_POST['email'];

            $upload_dir = '../images/';
            $base_name = basename($_FILES['image']['tmp_name']);
            $upload_file = $upload_dir . $base_name;
            if (isset($_POST['user_remove_previous_image']) || strlen($base_name)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                    $user['user_image'] = $base_name;
                } else {
                    $user['user_image'] = NULL;
                }

                if ($_POST['previous_image']) {
                    unlink($upload_dir . $_POST['previous_image']);
                }
            } else {
                $user['user_image'] = $_POST['previous_image'];
            }

            $user['user_role'] = $_POST['role'];
            $user['user_date'] = date('Y-m-d');

            $successfully_updated_user = editUser($link, $user);
        }

        if (isset($_GET['toggle']) && isset($_GET['role'])) {
            $user['user_id'] = $_GET['toggle'];
            $user['user_role'] = $_GET['role'];

            $successfully_toggled_role = toggleUserRole($link, $user);
        }

        $users = getUsers($link);
        $include_file = INCLUDES_VIEW;
        break;
}

$navbar = "users";

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
<?php include $include_file; ?>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include "includes/footer.php" ?>
