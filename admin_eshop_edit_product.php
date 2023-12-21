<?php
require_once 'handlers/_config_session.php';

// CHECKING IF ADMIN IS LOGGED IN, IF NOT REDIRECTED
if (!isset($_SESSION["admin_username"]) && empty($_SESSION["admin_username"])) {
    header("Location: admin.php");
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <script src="handlers/scripts/admin_eshop_image_handler.js" defer></script>
    <title>Admin Edit product</title>
</head>

<body>
    <?php include 'components/admin_header.php'; ?>
    <?php include 'components/admin_navbar.php'; ?>

    <hr>
    <div class="add-product-container">
        <?php include_once 'handlers/admin_eshop_edit_includes.php'; ?>
        <div class="errors">
            <?php
            if (isset($_SESSION["errors"])) {
                foreach ($_SESSION["errors"] as $error) {
                    echo $error;
                }
                unset($_SESSION["errors"]);
            }
            ?>
        </div>
    </div>
</body>

</html>