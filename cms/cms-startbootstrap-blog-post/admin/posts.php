<?php include "includes/header.php" ?>
<?php 

include "../includes/db.php";

define('ADD_POST', 'add_post');
define('EDIT_POST', 'edit_post');
define('DEFAULT_CASE', 'show_posts');

define('INCLUDES_ADD', 'includes/add_post.php');
define('INCLUDES_EDIT', 'includes/edit_post.php');
define('INCLUDES_VIEW', 'includes/view_all_posts.php');


$link = openDB();

accountForOnlineUser($link, $_SESSION['user'], session_id());
$total_online_users = totalOnlineUsers($link);

$source = isset($_GET['source']) ? $_GET['source'] : DEFAULT_CASE;

switch ($source) {
    case ADD_POST:
        if (isset($_POST['create_post'])) {
            $post['post_category_id'] = $_POST['post_category_id'];
            $post['post_title'] = $_POST['title'];
            $post['post_author'] = $_POST['author'];
            $post['post_date'] = date('Y-m-d');

            $upload_dir = '../images/';
            $base_name = basename($_FILES['image']['tmp_name']);
            $upload_file = $upload_dir . $base_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                $post['post_image'] = $base_name;
            } else {
                $post['post_image'] = NULL;
            }

            $post['post_content'] = $_POST['post_content'];
            $post['post_tags'] = $_POST['post_tags'];
            $post['post_status'] = $_POST['post_status'];
            $post['post_user_id'] = $_SESSION['user']['user_id'];

            $successfully_inserted_post = insertPost($link, $post);
        }

        $include_file = INCLUDES_ADD;
        break;
    
    case EDIT_POST:
        $include_file = INCLUDES_EDIT;
        break;
        
    default:
        if (isset($_GET['delete'])) {
            $post['post_id'] = $_GET['delete'];
            $successfully_deleted_post = deletePost($link, $post);
        }

        if (isset($_GET['reset_views'])) {
            $post['post_id'] = $_GET['reset_views'];
            $successfully_reset_views = resetViewsForPost($link, $post);
        }

        if (isset($_POST['edit_post'])) {
            $post['post_id'] = $_POST['post_id'];
            $post['post_category_id'] = $_POST['post_category_id'];
            $post['post_title'] = $_POST['title'];
            $post['post_date'] = date('Y-m-d');

            $upload_dir = '../images/';
            $base_name = basename($_FILES['image']['tmp_name']);
            $upload_file = $upload_dir . $base_name;
            if (isset($_POST['post_remove_previous_image']) || $base_name) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                    $post['post_image'] = $base_name;
                } else {
                    $post['post_image'] = NULL;
                }

                if ($_POST['previous_image']) {
                    unlink($upload_dir . $_POST['previous_image']);
                }
            } else {
                $post['post_image'] = $_POST['previous_image'];
            }

            $post['post_content'] = $_POST['post_content'];
            $post['post_tags'] = $_POST['post_tags'];
            $post['post_status'] = $_POST['post_status'];

            $successfully_updated_post = editPost($link, $post);
        }

        if (isset($_GET['toggle']) && isset($_GET['status'])) {
            $post['post_id'] = $_GET['toggle'];
            $post['post_status'] = $_GET['status'];

            $successfully_toggled_status = togglePostStatus($link, $post);
        }

        if (isset($_POST['bulk_submit'])) {
            if (isset($_POST['marked_selection'])) {
                $update_action = $_POST['bulk'];
                switch ($update_action) {
                    case 'published': // Fallthrough
                    case 'draft':
                        $successfull_bulk_update = bulkEditPosts_post_status($link, $_POST['marked_selection'], $update_action);
                        break;
                    case 'delete':
                        $successfull_bulk_update = bulkDeletePosts($link, $_POST['marked_selection']);
                        break;
                    case 'clone':
                        $user_id = isset($_SESSION['user']) ? $_SESSION['user']['user_id'] : 0;
                        $successfull_bulk_clone = bulkClonePosts($link, $_POST['marked_selection'], $user_id);
                        break;
                    case 'reset_views':
                        $successfull_bulk_reset_views = bulkResetViewsForPosts($link, $_POST['marked_selection']);
                        break;
                }
            } else {
                $select_before_applying = TRUE;
            }
        }

        $search = NULL;
        $onlyPublished = FALSE;
        $posts = getPosts($link, $search, $onlyPublished);
        $include_file = INCLUDES_VIEW;
        break;
}

$navbar = "posts";

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
