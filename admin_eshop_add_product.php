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
    <title>Admin Add product</title>
</head>

<body>
    <?php include 'components/admin_header.php'; ?>
    <?php include 'components/admin_navbar.php'; ?>

    <hr>
    <div class="add-product-container">
        <form action="handlers/admin_add_product_handler.php" method="post" enctype="multipart/form-data" id="add-product-form">
            <ul>
                <li>
                    <label for="product-barcode">Bar code: </label>
                    <input type="text" name="product-barcode" id="product-barcode">
                </li>
                <li>
                    <label for="product-name">Názov produktu: </label>
                    <input type="text" name="product-name" id="product-name">
                </li>
                <li>
                    <label for="product-category">Kategória produktu: </label>
                    <input type="text" name="product-category" id="product-category">
                </li>
                <li>
                    <label for="product-price">Cena produktu:</label>
                    <input type="text" name="product-price" id="product-price">
                </li>
                <li>
                    <label for="product-brand">Značka produktu: </label>
                    <input type="text" name="product-brand" id="product-brand">
                </li>
                <li>
                    <label for="product-stocked">Počet kusov skladom: </label>
                    <input type="text" name="product-stocked" id="product-stocked">
                </li>
                <li>
                    <label for="product-description">Popis produktu: </label>
                    <div class="product-description-container">
                        <textarea type="text" name="product-description" id="product-description" maxlength="1000"></textarea>
                        <div id="textarea-letter-counter">0/1000</div>
                    </div>
                </li>
                <li>
                    <label for="product-image-file" id="product-image-label">Upload image:
                        <input type="hidden" name="MAX_FILE_SIZE" value="400000">
                        <input type="file" name="product-image-file" id="product-image-file" accept="image/png, image/jpeg, image/jpg">
                    </label>
                    <div id="product-image-preview-container"><img src="uploads/no-image-icon.png" id="product-image-preview">
                        <div class="times">&#10060;</div>
                    </div>
                    <input type="submit" form="add-product-form" id="add-product-submit-button" value="Pridať nový produkt">
                </li>
            </ul>
        </form>
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