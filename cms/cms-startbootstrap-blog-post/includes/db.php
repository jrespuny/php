<?php

define('MAX_PAGE', 5);
define('REGISTERED_USER_TIME_OUT_IN_SECONDS', 30);


initialize();

function initialize()
{
	$db['db_host'] = 'localhost';
	$db['db_username'] = 'root';
	$db['db_password'] = 'root';
	$db['db_name'] = 'cms';

	foreach ($db as $key => $value) {
		define(strtoupper($key), $value);
	}
}

function openDB() {
	$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if ( !$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		
		return NULL;
	}

	return $link;
}

function total($link, $table_name, $sql_where = NULL, $column_name = NULL)
{
	$total = NULL;

	$where_clause = empty($sql_where) ? "" : "WHERE $sql_where";

	$query = "SELECT COUNT(*) AS `total` FROM `$table_name` $where_clause";

	if ($result = mysqli_query($link, $query)) {
		$data = mysqli_fetch_assoc($result);
		$total = $data['total'];
	}

	return $total;
}

function sum($link, $table_name, $sql_where = NULL, $column_name)
{
	$total = NULL;

	$where_clause = empty($sql_where) ? "" : "WHERE $sql_where";

	$query = "SELECT SUM(`$column_name`) AS `total` FROM `$table_name` $where_clause";

	if ($result = mysqli_query($link, $query)) {
		$data = mysqli_fetch_assoc($result);
		$total = $data['total'];
	}

	return $total;
}

// Ex:
// $categories = get($link, "categories", ["si", ["post_status", "post_id"], [$post_status, $post_id']], "'cat_title' ASC, 'cat_id' DESC");
function get($link, $table_name, $where_statement = NULL, $order_by = NULL, $assoc_id = NULL, $page = NULL, $max_page = MAX_PAGE)
{
	$table = NULL;

	$where_query = ($where_statement === NULL) ? NULL : " WHERE " . implode(" AND ", array_map(function($s) { return $s . " = ?"; }, array_map(function($s) { return "`$s`";}, $where_statement[1])));
	$order_by_query = ($order_by === NULL) ? NULL : " ORDER BY $order_by";

	$query = "SELECT * FROM `$table_name`" . $where_query . $order_by_query . (($page === NULL) ? "" : " LIMIT " . $page * $max_page . ", " . $max_page);

	if ($stmt = mysqli_prepare($link, $query)) {
		if ($where_statement !== NULL) {
			mysqli_stmt_bind_param($stmt, $where_statement[0], ...$where_statement[2]);
		}

		if (mysqli_stmt_execute($stmt)) {
			$result = mysqli_stmt_get_result($stmt);

			$table = [];

			while ($row = mysqli_fetch_assoc($result)) {
				array_push($table, $row); // $table becomes indexec array
			}
		}
	}

	// Turn to assoc array where key is determined by $assoc_id !== NULL
	// otherwise leave indexed array
    if ($assoc_id !== NULL && !empty($table)) {
    	$items = [];

        foreach ($table as $item) {
            $items[$item[$assoc_id]] = $item;
        }

        $table = $items; // $table becomes associative array
    }

	if ($page !== NULL) {
		$total = NULL;

		$query_count = "SELECT COUNT(*) AS `total` FROM `$table_name`" . $where_query;

		if ($stmt = mysqli_prepare($link, $query_count)) {
			if ($where_statement !== NULL) {
				mysqli_stmt_bind_param($stmt, $where_statement[0], ...$where_statement[2]);
			}

			if (mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);

				$data = mysqli_fetch_assoc($result);
				$total = $data['total'];
			}
		}

		return [$total, $table];
	}

	return $table;
}

// Ex:
// insert($link, "categories", $category, "cat_id", ["s", ["cat_title"]])
function insert($link, $table_name, &$item, $id, $insert_statement)
{
	$success = FALSE;

	$query = "INSERT INTO `$table_name` (`$id`, " . implode(", ", array_map(function ($s) { return "`$s`"; }, $insert_statement[1])) . ") VALUES (NULL, " . implode(", ", array_map(function ($s) { return "?"; }, $insert_statement[1])) . ")";

	if ($stmt = mysqli_prepare($link, $query)) {
		$insert_statement['values'] = [];
		foreach ($insert_statement[1] as $column_name) {
			array_push($insert_statement['values'], $item[$column_name]);
		}

		mysqli_stmt_bind_param($stmt, $insert_statement[0], ...$insert_statement['values']);
		if (mysqli_stmt_execute($stmt)) {
			$item[$id] = mysqli_insert_id($link);
			$success = TRUE;
		}
	}

	return $success;
}

// Ex:
// delete($link, "categories", $category, "cat_id")
function delete($link, $table_name, $item, $id)
{
	$success = FALSE;

	$query = "DELETE FROM `$table_name` WHERE `$id` = ?";

	if ($stmt = mysqli_prepare($link, $query)) {
		mysqli_stmt_bind_param($stmt, 'i', $item[$id]);
		if (mysqli_stmt_execute($stmt)) {
			$success = TRUE;
		}
	}

	return $success;
}

// Ex:
// edit($link, "categories", $category, "cat_id", ["s", ["cat_title"]])
function edit($link, $table_name, $item, $id, $update_statement)
{
	$success = FALSE;

	$query = "UPDATE `$table_name` SET " . implode(", ", array_map(function ($s) { return "`$s` = ?"; }, $update_statement[1])) . " WHERE `$id` = ?";

	if ($stmt = mysqli_prepare($link, $query)) {
		$update_statement['types'] = $update_statement[0] . "i";
		$update_statement['values'] = [];
		foreach ($update_statement[1] as $column_name) {
			array_push($update_statement['values'], $item[$column_name]);
		}
		array_push($update_statement['values'], $item[$id]);

		mysqli_stmt_bind_param($stmt, $update_statement['types'], ...$update_statement['values']);
		if (mysqli_stmt_execute($stmt)) {
			$success = TRUE;
		}
	}

	return $success;
}

// Ex:
// bulkEdit($link, "posts", ["post_id", [2, 5, 7]], ["s", "post_status", "published"])
function bulkEdit($link, $table_name, $selected_id, $update_value)
{
	$success = FALSE;

	$query = "UPDATE `$table_name` SET `{$update_value[1]}` = ? WHERE `{$selected_id[0]}` IN (" . implode(", ", array_map(function ($s) { return "?"; }, $selected_id[1])) . ")";

	if ($stmt = mysqli_prepare($link, $query)) {
		$update_statement['types'] = $update_value[0] . implode("", array_map(function ($s) { return "i"; }, $selected_id[1]));
		$update_statement['values'] = array_merge([$update_value[2]], $selected_id[1]);

		mysqli_stmt_bind_param($stmt, $update_statement['types'], ...$update_statement['values']);
		if (mysqli_stmt_execute($stmt)) {
			$success = TRUE;
		}
	}

	return $success;
}

// Ex:
// $current_date = date('Y-m-d');
// $post_user_id = $_SESSION['user']['post_user_id'];
// return bulkClone($link, "posts", ["post_id", $bulk_selection], [
// 	"post_category_id"
// 	, "post_title"
// 	, "post_date"
// 	, "post_image"
// 	, "post_content"
// 	, "post_tags"
// 	, "post_status"
// 	, "post_user_id"
// ], [
// 	"post_date" => ["s", $current_date]
// 	, "post_user_id" => ["i", $post_user_id]
// ]);
function bulkClone($link, $table_name, $selected_id, $clone_columns, $overridden_values)
{
	$success = FALSE;

	$types = "";
	$values = [];
	$select_columns_query = [];
	foreach ($clone_columns as $column) {
		if (isset($overridden_values[$column])) {
			$types .= $overridden_values[$column][0];
			array_push($values, $overridden_values[$column][1]);
			array_push($select_columns_query, "?");
		} else {
			array_push($select_columns_query, "`$column`");
		}
		
	}

	$query = "INSERT INTO `$table_name` (" . implode(", ", $clone_columns) . ") ";
	$query .= "SELECT " . implode(", ", $select_columns_query) . " FROM `$table_name` WHERE `{$selected_id[0]}` IN (" . implode(", ", $selected_id[1]) . ")";

	if ($stmt = mysqli_prepare($link, $query)) {
		$update_statement['types'] = $types;
		$update_statement['values'] = $values;

		mysqli_stmt_bind_param($stmt, $update_statement['types'], ...$update_statement['values']);
		if (mysqli_stmt_execute($stmt)) {
			$success = TRUE;
		}
	}

	return $success;
}

// Ex:
// increment($link, "posts", $post, "post_id", ["views" => 1])
function increment($link, $table_name, $item, $id, $increment_statement)
{
	$success = FALSE;

	$query = "UPDATE `$table_name` SET " . implode(", ", array_map(function ($key, $value) { return "`$key` = `$key` + $value";}, array_keys($increment_statement), array_values($increment_statement))) . " WHERE `$id` = ?";

	if ($stmt = mysqli_prepare($link, $query)) {
		$update_statement['types'] = "i";
		$update_statement['values'] = [$item[$id]];

		mysqli_stmt_bind_param($stmt, $update_statement['types'], ...$update_statement['values']);
		if (mysqli_stmt_execute($stmt)) {
			$success = TRUE;
		}
	}

	return $success;
}


// -- Categories ---

// Returns indexed array, or associative array when $assoc_id !== NULL
function getCategories($link, $assoc_id = NULL)
{
	return get($link, "categories", NULL, "'cat_title' ASC", $assoc_id);
}

function getCategoriesAssocByID($link) {
    return getCategories($link, 'cat_id');
}

function insertCategory($link, &$category)
{
	return insert($link, "categories", $category, "cat_id", [
		"s", 
		[
			"cat_title"
		]
	]);
}

function deleteCategory($link, $category)
{
	return delete($link, "categories", $category, "cat_id");
}

function editCategory($link, $category)
{
	return edit($link, "categories", $category, "cat_id", [
		"s",
		[
			"cat_title"
		]
	]); 
}


// --- Posts ---

// function getPosts2($link, $search = NULL)
// {
// 	$table = NULL;

// 	if ($search != NULL) {

// 		$likeString = '%' . $search . '%';
// 		$query = "SELECT * FROM posts WHERE `post_title` LIKE ? OR `post_author` LIKE ? OR `post_content` LIKE ? OR `post_tags` LIKE ? ORDER BY `post_date` DESC";

// 		if ($stmt = mysqli_prepare($link, $query)) {
// 			mysqli_stmt_bind_param($stmt, 'ssss', $likeString, $likeString, $likeString, $likeString);
// 			mysqli_stmt_execute($stmt);
// 			$result = mysqli_stmt_get_result($stmt);

// 			$table = [];
// 			while ($row = mysqli_fetch_assoc($result)) {
// 				array_push($table, $row);
// 			}
// 		}

// 	} else {
// 		$query = "SELECT * FROM posts ORDER BY `post_date` DESC";

// 		if ($result = mysqli_query($link, $query)) {

// 			$table = [];
// 			while ($row = mysqli_fetch_assoc($result)) {
// 				array_push($table, $row);
// 			}
// 		}
// 	}

// 	return $table;
// }

function getPosts($link, $search = NULL, $onlyPublished = TRUE, $page = NULL, $max_page = MAX_PAGE)
{
// CREATE VIEW view_posts_with_post_comment_count AS 
// SELECT P.post_id, P.post_category_id, P.post_title, P.post_date, P.post_image, P.post_content, P.post_tags, IF (C.comment_post_id IS NULL, 0, COUNT(*)) AS post_comment_count, P.post_status, P.post_user_id, P.views FROM posts AS P LEFT JOIN comments AS C ON P.post_id = C.comment_post_id GROUP BY P.post_id

// CREATE VIEW view_posts_with_post_author AS 
// SELECT P.post_id, P.post_category_id, P.post_title, CONCAT(U.user_first_name, IF (U.user_last_name IS NOT NULL, " ", ""), U.user_last_name) AS post_author, P.post_date, P.post_image, P.post_content, P.post_tags, P.post_comment_count, P.post_status, P.post_user_id, P.views FROM view_posts_with_post_comment_count AS P LEFT JOIN users AS U ON P.post_user_id = U.user_id

	$table = NULL;

	$getSearchedPosts = function($link, $search, $onlyPublished, &$table, $assemblePostsIntoAssoc, $page = NULL, $max_page = MAX_PAGE, $countPosts)
	{
		$post_status = $onlyPublished ? " `post_status` LIKE 'published'" : "";

		if ($search != NULL) {

			$likeString = '%' . $search . '%';
			$post_status = $onlyPublished ? $post_status . " AND " : $post_status;
			$query_common = "`view_posts_with_post_author` WHERE " . $post_status . " (`post_title` LIKE ? OR `post_author` LIKE ? OR `post_content` LIKE ? OR `post_tags` LIKE ?)";
			$query = "SELECT * FROM " . $query_common . " ORDER BY `post_date` DESC, `post_id` DESC" . (($page === NULL) ? "" : " LIMIT " . $page * $max_page . ", " . $max_page);

			if ($stmt = mysqli_prepare($link, $query)) {
				$update_statement['types'] = "ssss";
				$update_statement['values'] = [$likeString, $likeString, $likeString, $likeString];

				mysqli_stmt_bind_param($stmt, $update_statement['types'], ...$update_statement['values']);
				if (mysqli_stmt_execute($stmt)) {
					$result = mysqli_stmt_get_result($stmt);
					$assemblePostsIntoAssoc($result, $table);

					if ($page !== NULL) {
						return $countPosts($link, $table, $query_common, $update_statement['types'], $update_statement['values']);
					}
				}
			}

		} else {
			$post_status = $onlyPublished ? "WHERE " . $post_status : $post_status;
			$query_common = "`view_posts_with_post_author` " . $post_status;
			$query = "SELECT * FROM " . $query_common . " ORDER BY `post_date` DESC, `post_id` DESC" . (($page === NULL) ? "" : " LIMIT " . $page * $max_page . ", " . $max_page);

			if ($result = mysqli_query($link, $query)) {
				$assemblePostsIntoAssoc($result, $table);

				if ($page !== NULL) {
					return $countPosts($link, $table, $query_common);
				}
			}
		}

		return $table;
	};

	return $getSearchedPosts($link, $search, $onlyPublished, $table, function ($result, &$table) {
		$table = [];

		while ($row = mysqli_fetch_assoc($result)) {
			array_push($table, $row);
		}
	}, $page, $max_page, function ($link, $table, $query_common, $types = NULL, $values = NULL) {
		$total = NULL;

		$query = "SELECT COUNT(*) AS `total` FROM " . $query_common;

		if ($stmt = mysqli_prepare($link, $query)) {

			if ( !empty($types) && !empty($values)) {
				mysqli_stmt_bind_param($stmt, $types, ...$values);
			}

			if (mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);

				$data = mysqli_fetch_assoc($result);
				$total = $data['total'];
			}
		}

		return [$total, $table];
	});
}

function insertPost($link, &$post)
{
	return insert($link, "posts", $post, "post_id", [
		"issssssi",
		[
			"post_category_id"
			, "post_title"
			, "post_date"
			, "post_image"
			, "post_content"
			, "post_tags"
			, "post_status"
			, "post_user_id"
		]
	]);
}

function deletePost($link, $post)
{
	return delete($link, "posts", $post, "post_id");
}

function getPost($link, $post)
{
	return get($link, "view_posts_with_post_author", ["i", ["post_id"], [$post['post_id']]]);
}

function getPostsForCategory($link, $category, $onlyPublished = TRUE, $page = NULL)
{
	$where_statement[0] = "i";
	$where_statement[1] = ["post_category_id"];
	$where_statement[2] = [$category['cat_id']];
	if ($onlyPublished) {
		$where_statement[0] .= "s";
		array_push($where_statement[1], "post_status");
		array_push($where_statement[2], "published");
	}
	
	return get($link, "view_posts_with_post_author", $where_statement, "`post_date` DESC", NULL, $page);
}

function getPostsFor_post_user_id($link, $post, $page = NULL)
{
	return get($link, "view_posts_with_post_author", ["i", ["post_user_id"], [$post['post_user_id']]], $page);
}

function editPost($link, $post)
{
	return edit($link, "posts", $post, "post_id", [
		"issssss",
		[
			"post_category_id"
			, "post_title"
			, "post_date"
			, "post_image"
			, "post_content"
			, "post_tags"
			, "post_status"
		]
	]); 
}

function togglePostStatus($link, $post)
{
	$post['post_status'] = (strtolower($post['post_status']) === 'draft') ? 'published' : 'draft';

	return edit($link, "posts", $post, "post_id", [
		"s",
		[
			"post_status"
		]
	]);
}

function bulkEditPosts_post_status($link, $bulk_selection, $post_status)
{
	return bulkEdit($link, "posts", ["post_id", $bulk_selection], ["s", "post_status", $post_status]);
}

function bulkDeletePosts($link, $bulk_selection)
{
	return bulkDelete($link, "posts", ["post_id", $bulk_selection]);
}

function bulkClonePosts($link, $bulk_selection, $user_id, $date = NULL)
{
	$date = ($date === NULL) ? date('Y-m-d') : $date;
	return bulkClone($link, "posts", ["post_id", $bulk_selection], [
		"post_category_id"
		, "post_title"
		, "post_date"
		, "post_image"
		, "post_content"
		, "post_tags"
		, "post_status"
		, "post_user_id"
	], [
		"post_date" => ["s", $date]
		, "post_user_id" => ["i", $user_id]
	]);
}

function logViewIncrementForPost($link, $post, $increment = 1)
{
	return increment($link, "posts", $post, "post_id", ["views" => $increment]);
}

function resetViewsForPost($link, &$post)
{
	$post['views'] = 0;
	return edit($link, "posts", $post, "post_id", ["i", ["views"]]);
}

function bulkResetViewsForPosts($link, $bulk_selection)
{
	return bulkEdit($link, "posts", ["post_id", $bulk_selection], ["i", "views", 0]);
}


// --- Comments ---

function getComments($link, $approved_only = FALSE , $post = NULL)
{
	$where_statement[0] = NULL;
	$where_statement[1] = [];
	$where_statement[2] = [];

	if (isset($post['post_id'])) {
		$where_statement[0] .= "i";
		array_push($where_statement[1], "comment_post_id");
		array_push($where_statement[2], $post['post_id']);
	}
	if ($approved_only) {
		$where_statement[0] .= "s";
		array_push($where_statement[1], "comment_status");
		array_push($where_statement[2], "approved");
	}

	if ($where_statement[0] ===  NULL) {
		$where_statement = NULL;
	}

	return get($link, "comments", $where_statement, "`comment_date` DESC");
}

function getComment($link, $comment)
{
	return get($link, "comments", ["i", ["comment_id"], [$comment['comment_id']]]);
}

function insertComment($link, &$comment)
{
	return insert($link, "comments", $comment, "comment_id", [
		"issss",
		[
			"comment_post_id"
			, "comment_author"
			, "comment_email"
			, "comment_content"
			, "comment_date"
		]
	]);
}

function deleteComment($link, $comment)
{
	return delete($link, "comments", $comment, "comment_id");
}

function editComment($link, $comment)
{	
	if (isset($comment['comment_post_id'])) {
		return edit($link, "comments", $comment, "comment_id", [
			"isssss",
			[
				"comment_post_id"
				, "comment_author"
				, "comment_email"
				, "comment_content"
				, "comment_status"
				, "comment_date"
			]
		]);

	} else {
		return edit($link, "comments", $comment, "comment_id", [
			"sssss",
			[
				"comment_author"
				, "comment_email"
				, "comment_content"
				, "comment_status"
				, "comment_date"
			]
		]);

	}
}

function addCommentToPost($link, &$comment, $post)
{
	return insert($link, "comments", $comment, "comment_id", [
		"isssss",
		[
			"comment_post_id"
			, "comment_author"
			, "comment_email"
			, "comment_content"
			, "comment_status"
			, "comment_date"
		]
	]);
}

function toggleCommentStatus($link, $comment)
{
	$comment['comment_status'] = (strtolower($comment['comment_status']) === 'unapproved') ? 'approved' : 'unapproved';

	return edit($link, "comments", $comment, "comment_id", [
		"s",
		[
			"comment_status"
		]
	]);
}

function bulkEditComments_comment_status($link, $bulk_selection, $comment_status)
{
	return bulkEdit($link, "comments", ["comment_id", $bulk_selection], ["s", "comment_status", $comment_status]);
}

function bulkDeleteComments($link, $bulk_selection)
{
	return bulkDelete($link, "comments", ["comment_id", $bulk_selection]);
}


// --- Users ---

function getUsers($link)
{
	return get($link, "users", NULL, "'user_date' DESC");
}

function getUser_user_id($link, $user)
{
	return get($link, "users", ["i", ["user_id"], [$user['user_id']]]);
}

function getUser_user_name($link, $user)
{
	return get($link, "users", ["s", ["user_name"], [$user['user_name']]]);
}

function insertUser($link, &$user)
{
	return insert($link, "users", $user, "user_id", [
		"ssssssss",
		[
			"user_name"
			, "user_password"
			, "user_first_name"
			, "user_last_name"
			, "user_email"
			, "user_image"
			, "user_role"
			, "user_date"
		]
	]);
}

function registerUser($link, &$user)
{
	return insert($link, "users", $user, "user_id", [
		"ssss",
		[
			"user_name"
			, "user_password"
			, "user_email"
			, "user_date"
		]
	]);
}

function deleteUser($link, $user)
{
	return delete($link, "users", $user, "user_id");
}

function editUser($link, $user)
{
	// Keeps previous password when NULL
	if (isset($user['user_password'])) {
		return edit($link, "users", $user, "user_id", [
			"ssssssss",
			[
				"user_name"
				, "user_password"
				, "user_first_name"
				, "user_last_name"
				, "user_email"
				, "user_image"
				, "user_role"
				, "user_date"
			]
		]);

	} else {
		return edit($link, "users", $user, "user_id", [
			"sssssss",
			[
				"user_name"
				, "user_first_name"
				, "user_last_name"
				, "user_email"
				, "user_image"
				, "user_role"
				, "user_date"
			]
		]);

	}
}

function toggleUserRole($link, $user)
{
	$user['user_role'] = (strtolower($user['user_role']) === 'admin') ? 'subscriber' : 'admin';

	return edit($link, "users", $user, "user_id", [
		"s",
		[
			"user_role"
		]
	]);
}


// --- online_users ---

function accountForOnlineUser($link, $user, $session_id)
{
	$success = FALSE;

	$table_name = "online_users";

	$online_users = get($link, $table_name, ["si", ["session", "user_id"], [$session_id, $user['user_id']]]);

	if ($online_users !== NULL) {
		if ( !empty($online_users)) {
			$online_user = current($online_users);
			$online_user['time_out'] = time() + REGISTERED_USER_TIME_OUT_IN_SECONDS;

			$success = edit($link, $table_name, $online_user, "id", ["i", ["time_out"]]);
		} else {
			$online_user['session'] = $session_id;
			$online_user['time_out'] = time() + REGISTERED_USER_TIME_OUT_IN_SECONDS;
			$online_user['user_id'] = $user['user_id'];

			$success = insert($link, $table_name, $online_user, "id", ["sii", ["session", "time_out","user_id"]]);
		}
	}

	return $success;
}

function totalOnlineUsers($link)
{
	$total = NULL;

	$query = "SELECT COUNT(*) AS `total` FROM ";
	$query .= "(SELECT COUNT(*) FROM (SELECT * FROM online_users WHERE time_out > " . time() . ") AS valid_time_out GROUP BY user_id) AS total_online_users";

	if ($result = mysqli_query($link, $query)) {
		$data = mysqli_fetch_assoc($result);
		$total = $data['total'];
	}

	return $total;
}

 ?>