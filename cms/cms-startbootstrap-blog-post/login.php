<?php 

ob_start();

// Restart session
session_start();
$_SESSION['user'] = NULL;
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
session_start();

if (isset($_GET['logout'])) {
	header("Location: index.php"); exit;
}

include "includes/db.php";

$link = openDB();

if (isset($_POST['login'])) {
	$user['user_name'] = $_POST['user_name'];
	$user_array = getUser_user_name($link, $user);
	if ($user_array === NULL) {
		$user = NULL;
	} else {
		$user = ( !empty($user_array)) ? $user_array[0] : [];
	}
}

if (empty($user)) {
    header("Location: index.php?invalid_login=" . htmlentities($_POST['user_name'])); exit;
} else  {
	$successfully_validated_user = (password_verify($_POST['password'], $user['user_password']));

	if ($successfully_validated_user) {
		$_SESSION['user'] = $user;
		
		header("Location: admin/"); exit;
	} else {
		header("Location: index.php?invalid_login=" . htmlentities($user['user_name'])); exit;
	}
}

 ?>