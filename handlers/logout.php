<?php
session_start();

unset($_SESSION["username"]);
unset($_SESSION["email"]);

if ($_SERVER["HTTP_REFERER"] === 'http://localhost/website/admin_page.php') {
    header("Location: ../admin.php");
} else {
    header("Location: ../index.php");
}
die();
