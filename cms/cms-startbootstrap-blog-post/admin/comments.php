<?php include "includes/header.php" ?>
<?php 

include "../includes/db.php";

define('ADD_COMMENT', 'add_comment');
define('EDIT_COMMENT', 'edit_comment');
define('DEFAULT_CASE', 'show_comments');

define('INCLUDES_EDIT', 'includes/edit_comment.php');
define('INCLUDES_VIEW', 'includes/view_all_comments.php');


$link = openDB();

$uri['query'] = [];

accountForOnlineUser($link, $_SESSION['user'], session_id());
$total_online_users = totalOnlineUsers($link);

$source = isset($_GET['source']) ? $_GET['source'] : DEFAULT_CASE;

switch ($source) {    
    case EDIT_COMMENT:
        if (isset($_GET['post_id'])) {
            array_push($uri['query'], "post_id=" . $_GET['post_id']);
        }

        $include_file = INCLUDES_EDIT;
        break;
        
    default:
        if (isset($_GET['delete'])) {
            $comment['comment_id'] = $_GET['delete'];
            
            $successfully_deleted_comment = deleteComment($link, $comment);
        }

        if (isset($_GET['toggle']) && isset($_GET['status'])) {
            $comment['comment_id'] = $_GET['toggle'];
            $comment['comment_status'] = $_GET['status'];

            $successfully_toggled_status = toggleCommentStatus($link, $comment);
        }

        if (isset($_POST['edit_comment'])) {
            $comment['comment_id'] = $_POST['comment_id'];
            $comment['comment_author'] = $_POST['author'];
            $comment['comment_email'] = $_POST['email'];
            $comment['comment_content'] = $_POST['content'];
            $comment['comment_status'] = $_POST['status'];
            $comment['comment_date'] = date('Y-m-d');

            $successfully_updated_comment = editComment($link, $comment);
        }

        if (isset($_POST['bulk_submit'])) {
            if (isset($_POST['marked_selection'])) {
                $update_action = $_POST['bulk'];
                switch ($update_action) {
                    case 'approved': // Fallthrough
                    case 'unapproved':
                        $successfull_bulk_update = bulkEditComments_comment_status($link, $_POST['marked_selection'], $update_action);
                        break;
                    case 'delete':
                        $successfull_bulk_update = bulkDeleteComments($link, $_POST['marked_selection']);
                        break;
                }
            } else {
                $select_before_applying = TRUE;
            }
        }

        if (isset($_GET['post_id'])) {
            $post['post_id'] = $_GET['post_id'];
            $comments = getComments($link, FALSE, $post);

            array_push($uri['query'], "post_id=" . $_GET['post_id']);
        } else {
            $comments = getComments($link);
        }

        $include_file = INCLUDES_VIEW;
        break;
}

$navbar = "comments";

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
