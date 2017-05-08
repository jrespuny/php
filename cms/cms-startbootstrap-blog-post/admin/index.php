<?php include "includes/header.php" ?>
<?php 

include "../includes/db.php";

define('NAME', 'name');
define('TABLE', 'table');
define('WHERE', 'where');
define('COLUMN_NAME', 'column_name');
define('CALL_FUNCTION', 'call_function');
define('ICON', 'icon');
define('BG_COLOR', 'bg_color'); 
define('HREF', 'href');


$link = openDB();

accountForOnlineUser($link, $_SESSION['user'], session_id());
$total_online_users = totalOnlineUsers($link);

// Data for View Details widget
$view_details = [
    [
        NAME => htmlentities('Posts'),
        TABLE => 'posts',
        ICON => 'fa-file-text',
        BG_COLOR => 'panel-primary',
        HREF => 'posts.php'
    ]
    ,[
        NAME => htmlentities('Categories'),
        TABLE => 'categories',
        ICON => 'fa-list',
        BG_COLOR => 'panel-red',
        HREF => 'categories.php'
    ]
    ,[
        NAME => htmlentities('Comments'),
        TABLE => 'comments',
        ICON => 'fa-comments',
        BG_COLOR => 'panel-green',
        HREF => 'comments.php'
    ]
    ,[
        NAME => htmlentities('Users'),
        TABLE => 'users',
        ICON => 'fa-user',
        BG_COLOR => 'panel-yellow',
        HREF => 'users.php'
    ]
];
// Total count per view
foreach ($view_details as $view_detail) {
    $view_total[$view_detail[NAME]] = total($link, $view_detail[TABLE], NULL, NULL);
}

// Data for Google graph widget
$columns = [
    [
        NAME => htmlentities('Published Posts'),
        TABLE => 'posts',
        WHERE => "`post_status` = 'published'",
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
    ,[
        NAME => htmlentities('Draft Posts'),
        TABLE => 'posts',
        WHERE => "`post_status` = 'draft'",
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
    ,[
        NAME => htmlentities('Viewed Posts'),
        TABLE => 'posts',
        WHERE => "`views` > 0",
        COLUMN_NAME => "views",
        CALL_FUNCTION => 'sum'
    ]
    ,[
        NAME => htmlentities('Categories'),
        TABLE => 'categories',
        WHERE => NULL,
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
    ,[
        NAME => htmlentities('Approved Comments'),
        TABLE => 'comments',
        WHERE => "`comment_status` = 'approved'",
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
    ,[
        NAME => htmlentities('Unpproved Comments'),
        TABLE => 'comments',
        WHERE => "`comment_status` <> 'approved'",
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
    ,[
        NAME => htmlentities('Admin Users'),
        TABLE => 'users',
        WHERE => "`user_role` = 'admin'",
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
    ,[
        NAME => htmlentities('Other Users'),
        TABLE => 'users',
        WHERE => "`user_role` <> 'admin'",
        COLUMN_NAME => NULL,
        CALL_FUNCTION => 'total'
    ]
];
// Total count per column
foreach ($columns as $column_data) {
    $column_total[$column_data[NAME]] = $column_data[CALL_FUNCTION]($link, $column_data[TABLE], $column_data[WHERE], $column_data[COLUMN_NAME]);
}

// Gets called from javascript
$google_visualization_arrayToDataTable = function($columns, $column_total) {
// Ex:
//   ['Year', 'Sales', 'Expenses', 'Profit'],
//   ['2014', 1000, 400, 200],
//   ['2015', 1170, 460, 250],
//   ['2016', 660, 1120, 300],
//   ['2017', 1030, 540, 350]
// ]);
    $data_count = ["['Data', 'Count']"];
    foreach ($columns as $column_data) {
        array_push($data_count, "['" . $column_data[NAME] . "', ${column_total[$column_data[NAME]]}]"); // Ex: ['Posts', 1000]
    }

    return implode(", ", $data_count) . PHP_EOL;
};


$navbar['dashboard'] = TRUE;

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
<!--                         <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Blank Page
                            </li>
                        </ol> -->
                    </div>
                </div>
<?php include "includes/admin_widgets.php" ?>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include "includes/footer.php" ?>
