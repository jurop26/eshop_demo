<?php
require_once 'handlers/_config_session.php';
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
        <div class="errors">
            <?php
            if (isset($_SESSION["errors"])) {
                foreach ($_SESSION["errors"] as $error) {
                    echo $error;
                }
                unset($_SESSION["errors"]);
            }
            if (isset($_SESSION["message"])) {
                foreach ($_SESSION["message"] as $error) {
                    echo $error;
                }
                unset($_SESSION["message"]);
            }
            ?>
        </div>
        <?php include_once('components/footer.php'); ?>
    </div>

</body>

</html>