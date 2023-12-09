<?php
require_once '_config_session.php';

if ($_SERVER["HTTP_REFERER"] === 'http://localhost/website/admin_page.php') {
    unset($_SESSION["admin_username"]);
    unset($_SESSION["admin_email"]);
    header("Location: ../admin.php");
} else {
    unset($_SESSION["username"]);
    unset($_SESSION["email"]);
    header("Location: ../index.php");
    die();
}
die();
