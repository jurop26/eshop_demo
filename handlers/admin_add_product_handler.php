<?php
require_once '_config_session.php';

// CHECKING IF ADMIN IS LOGGED IN, IF NOT REDIRECTED
if (!isset($_SESSION["admin_username"]) && empty($_SESSION["admin_username"])) {
    header("Location: admin.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $product_id = filter_input(INPUT_POST, 'product-id', FILTER_SANITIZE_NUMBER_INT);
    $product_barcode = filter_input(INPUT_POST, 'product-barcode', FILTER_SANITIZE_SPECIAL_CHARS);
    $product_name = filter_input(INPUT_POST, 'product-name', FILTER_SANITIZE_SPECIAL_CHARS);
    $product_category = filter_input(INPUT_POST, 'product-category', FILTER_SANITIZE_SPECIAL_CHARS);
    $product_price = filter_input(INPUT_POST, 'product-price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $product_brand = filter_input(INPUT_POST, 'product-brand', FILTER_SANITIZE_SPECIAL_CHARS);
    $product_stocked = filter_input(INPUT_POST, 'product-stocked', FILTER_SANITIZE_SPECIAL_CHARS);
    $product_description = filter_input(INPUT_POST, 'product-description', FILTER_SANITIZE_SPECIAL_CHARS);
    $product_image_old = filter_input(INPUT_POST, 'product-image-old', FILTER_SANITIZE_URL); //URL for product image
    $product_image = $_FILES["product-image-file"]; //Image file for product image

    $product_price = floatval(str_replace(',', '.', $product_price));

    // ERROR HANDLERS

    $errors = [];
    if (!isset($_FILES["product-image-file"])) {
        $errors["file_upload"] = "Obrázok sa nenačítal správne";
    }

    if (is_input_empty($product_barcode, $product_name, $product_category, $product_price, $product_brand, $product_description, $product_stocked)) {
        $errors["input_empty"] = "Nevyplnili ste všetky polia";
    }

    if ($errors) {
        $_SESSION["errors"] = $errors;
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        die();
    }

    $product_image_url = upload_image($product_image, $product_image_old);

    if (!empty($product_id) || !is_null($product_id)) {
        edit_product_in_database($product_id, $product_barcode, $product_name, $product_category, $product_price, $product_brand, $product_description, $product_stocked, $product_image_url);
        header("Location: ../admin_eshop.php");
    } else {
        add_product_to_database($product_barcode, $product_name, $product_category, $product_price, $product_brand, $product_description, $product_stocked, $product_image_url);
        header("Location: ../admin_eshop.php");
    }
} else {
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    die();
}

function edit_product_in_database($product_id, $product_barcode, $product_name, $product_category, $product_price, $product_brand, $product_description, $product_stocked, $product_image_url)
{
    require_once 'connections/dbh.php';
    try {
        $sql = "UPDATE products SET product_bar_code = :product_barcode, product_name = :product_name, product_price = :product_price, product_category = :product_category, product_brand = :product_brand, product_description = :product_description, product_stocked = :product_stocked, product_image = :product_image WHERE product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $stmt->bindParam(":product_barcode", $product_barcode, PDO::PARAM_STR);
        $stmt->bindParam(":product_name", $product_name, PDO::PARAM_STR);
        $stmt->bindParam(":product_category", $product_category, PDO::PARAM_STR);
        $stmt->bindParam(":product_price", $product_price, PDO::PARAM_STR);
        $stmt->bindParam(":product_brand", $product_brand, PDO::PARAM_STR);
        $stmt->bindParam(":product_description", $product_description, PDO::PARAM_STR);
        $stmt->bindParam(":product_stocked", $product_stocked, PDO::PARAM_STR);
        $stmt->bindParam(":product_image", $product_image_url, PDO::PARAM_STR);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Chyba, nepodarilo sa upraviť produkt v databáze: " . $e->getMessage());
    }
}

function add_product_to_database($product_barcode, $product_name, $product_category, $product_price, $product_brand, $product_description, $product_stocked, $product_image_url)
{
    require_once 'connections/dbh.php';
    try {
        $sql = "INSERT INTO products (product_bar_code, product_name, product_price, product_category, product_brand, product_description, product_stocked, product_image) VALUES (:product_barcode, :product_name, :product_price, :product_category, :product_brand, :product_description, :product_stocked, :product_image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":product_barcode", $product_barcode, PDO::PARAM_STR);
        $stmt->bindParam(":product_name", $product_name, PDO::PARAM_STR);
        $stmt->bindParam(":product_category", $product_category, PDO::PARAM_STR);
        $stmt->bindParam(":product_price", $product_price, PDO::PARAM_STR);
        $stmt->bindParam(":product_brand", $product_brand, PDO::PARAM_STR);
        $stmt->bindParam(":product_description", $product_description, PDO::PARAM_STR);
        $stmt->bindParam(":product_stocked", $product_stocked, PDO::PARAM_STR);
        $stmt->bindParam(":product_image", $product_image_url, PDO::PARAM_STR);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Chyba, nepodarilo sa pridať produkt do databázy: " . $e->getMessage());
    }
}

function is_input_empty($product_barcode, $product_name, $product_category, $product_price, $product_brand, $product_description, $product_stocked)
{
    if (empty($product_barcode || empty($product_name)) || empty($product_category) || empty($product_price) || empty($product_brand) || empty($product_description) || (empty($product_stocked) && $product_stocked != 0)) {
        return true;
    } else {
        return false;
    }
}

function upload_image($product_image, $product_image_old)
{
    // NO IMAGE ADDED TO THE PRODUCT, DEFAULT IMAGE OR OLD IMAGE ADDED AUTOMATICALLY
    if (empty($product_image["tmp_name"]) &&  empty($product_image_old)) return 'uploads/no-image-icon.png';
    if (empty($product_image["tmp_name"]) && !empty($product_image_old)) return $product_image_old;

    // DELETE OLD IMAGE FILE OF FROM SERVER WHEN NEW IMAGE IS SET
    if (!empty($product_image["tmp_name"]) && !empty($product_image_old)) delete_product_image($product_image_old);

    // UPLOAD ERROR HANDLER
    $errors = [];

    $uploads_dir = '../uploads/';
    $targetFile = $uploads_dir . basename($product_image["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0775);
    }

    if ($product_image["error"]) {
        $errors["errors"] = ["upload errors:" . $product_image["error"]];
    }

    if (is_wrong_type($fileType)) {
        $errors["file_type"] = "Súbor má zlý formát!";
    }

    if (file_exists($targetFile)) {
        $errors["file_exists"] = "Súbor s týmto názvom už existuje!";
    }

    if (is_file_too_big($product_image)) {
        $errors["file_size"] = "Súbor je príliš veľký!";
    }

    if (is_fake_image($product_image)) {
        $errors["check_image"] = "File is not a proper image!";
    }

    if ($errors) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        $_SESSION["errors"] = $errors;
        die();
    }

    $sucess = move_uploaded_file($product_image["tmp_name"], $targetFile);

    return ltrim($targetFile, "../");
}

function is_fake_image($product_image)
{
    $check = getimagesize($product_image["tmp_name"]);
    if ($check["mime"] === false) return true;
    else return false;
}

function is_wrong_type($fileType)
{
    if ($fileType === "jpg" || $fileType === 'jpeg' || $fileType === 'png' || $fileType === "gif") return false;
    else return true;
}

function is_file_too_big($product_image)
{
    if ($product_image["size"] > 400000) return true;
    else return false;
}

function delete_product_image($product_image_url)
{
    if ($product_image_url === 'uploads/no-image-icon.png') return;

    unlink('../' . $product_image_url);
}
