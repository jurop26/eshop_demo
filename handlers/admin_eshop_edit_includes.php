<?php

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["product-id"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    // IF POST PRODUCT IS POSTED OR ERROR OCCURED WHEN EDITING AND STORING TO DB, ERRORS REDIRECT BACK TO HERE
    $product_id = $_POST["product-id"];

    require_once 'handlers/connections/dbh.php';

    $result = get_products_details($pdo, $product_id);

    if ($result) {
        foreach ($result as $row) {
            $product_bar_code = $row['product_bar_code'];
            $product_name = $row['product_name'];
            $product_category = $row['product_category'];
            $product_price = $row['product_price'];
            $product_brand = $row['product_brand'];
            $product_stocked = $row['product_stocked'];
            $product_description = $row['product_description'];
            $product_image = $row['product_image'];

            echo edit_product_form($product_id, $product_bar_code, $product_name, $product_category, $product_price, $product_brand, $product_stocked, $product_description, $product_image,);
        }
    }
}

function edit_product_form($product_id, $product_bar_code, $product_name, $product_category, $product_price, $product_brand, $product_stocked, $product_description, $product_image)
{
    $container = '
                <form action="handlers/admin_add_product_handler.php" method="post" enctype="multipart/form-data" id="edit-product-form">
                    <ul>
                        <li>
                            <label for="product-barcode">Bar code: </label>
                            <input type="text" name="product-barcode" id="product-barcode" value="' . $product_bar_code . '">
                        </li>
                        <li>
                            <label for="product-name">Názov produktu: </label>
                            <input type="text" name="product-name" id="product-name" value="' . $product_name . '">
                        </li>
                        <li>
                            <label for="product-category">Kategória produktu: </label>
                            <input type="text" name="product-category" id="product-category" value="' . $product_category . '">
                        </li>
                        <li>
                            <label for="product-price">Cena produktu:</label>
                            <input type="text" name="product-price" id="product-price" value="' . $product_price . '">
                        </li>
                        <li>
                            <label for="product-brand">Značka produktu: </label>
                            <input type="text" name="product-brand" id="product-brand" value="' . $product_brand . '">
                        </li>
                        <li>
                            <label for="product-stocked">Počet kusov skladom: </label>
                            <input type="text" name="product-stocked" id="product-stocked" value="' . $product_stocked . '">
                        </li>
                        <li>
                            <label for="product-description">Popis produktu: </label>
                            <textarea type="text" name="product-description" id="product-description">' . $product_description . '</textarea>
                        </li>
                        <li>
                            <label for="product-image-file" id="product-image-label">Upload image:
                                <input type="hidden" name="product-image-old" value="' . $product_image . '">
                                <input type="hidden" name="MAX_FILE_SIZE" value="400000">
                                <input type="file" name="product-image-file" id="product-image-file" accept="image/png, image/jpeg, image/jpg">
                                </label>
                                <img src="' . $product_image . '" id="product-image-preview">
                                <input type="hidden" name="product-id" value="' . $product_id . '">
                                <input type="submit" form="edit-product-form"  id="add-product-submit-button" value="Uložiť zmeny produktu">
                        </li>
                    </ul>
                </form>';

    return $container;
}

function get_products_details($pdo, $product_id)
{
    try {
        $sql = "SELECT * FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;

        return $result;
    } catch (PDOException $e) {
        die("Chyba pri nacitani produktov: ") . $e->getMessage();
    }
}
