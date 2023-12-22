<?php
require_once 'handlers/_config_session.php';

if (!isset($_SESSION["email"]) && !empty($_SESSION["email"])) {
    header("Location: index.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src='handlers/scripts/script.js' defer></script>
    <title>My Eshop | Login</title>
</head>

<body>
    <div class="main-container">
        <?php include('./components/header.php'); ?>
        <div>
            <hr>
        </div>
        <?php include_once 'handlers/profile_includes.php'; ?>
        <?php
        if (isset($_SESSION["errors"])) {
            foreach ($_SESSION["errors"] as $error) {
                echo $error;
            }
            unset($_SESSION["errors"]);
        }
        ?>
        <?php include_once('components/footer.php'); ?>
    </div>

</body>

</html>