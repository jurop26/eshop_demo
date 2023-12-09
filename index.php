<?php
require_once 'handlers/_config_session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="handlers/scripts/script.js" defer></script>
    <title>My Eshop</title>
</head>

<body>
    <div class="main-container">
        <?php include_once('components/header.php') ?>
        <div>
            <hr>
        </div>
        <div class="content-container">
            <div class="category-container">
                <?php
                if (isset($_SESSION["errors"])) {
                    foreach ($_SESSION["errors"] as $error) {
                        echo $error;
                    }
                    unset($_SESSION["errors"]);
                }
                include_once('handlers/categories.php')
                ?>
            </div>
            <div class="products-container">
                <?php
                include_once('handlers/products.php');
                ?>
            </div>
        </div>
        <?php include_once('components/footer.php'); ?>
    </div>
</body>

</html>
<?php
