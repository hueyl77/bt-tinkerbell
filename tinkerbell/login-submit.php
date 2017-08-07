<?php
$no_login = true;
require_once('lib/globals.php');

$username = _getVar($_POST, 'username');
$password = _getVar($_POST, 'password');

$auth_user = getenv('AUTH_USER');
$auth_pass = getenv('AUTH_PASS');

if (strcmp($username, $auth_user) !== 0 ||
strcmp($password, $auth_pass) !== 0) {
    die("Authentication Failed.<br><a href='/tinkerbell'>Login</a>");
}

$_SESSION["auth_user"] = $username;
$_SESSION["authenticated"] = true;

header("Location: dashboard.php");