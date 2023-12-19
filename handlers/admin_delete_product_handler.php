<?php

require_once '_config_session.php';

// CHECKING IF ADMIN IS LOGGED IN, IF NOT REDIRECTED
if (!isset($_SESSION["admin_username"]) && empty($_SESSION["admin_username"])) {
    header("Location: ../admin.php");
    die();
}

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {

    require_once 'connections/dbh.php';

    $product_id = $_POST["product-id"];
    $product_image_url = $_POST["product-image"];

    if (!is_id_empty($product_id)) {
        delete_product($product_id, $pdo);
        delete_product_image($product_image_url);
    }

    header("Location:" . $_SERVER["HTTP_REFERER"]);
    die();
}

function is_id_empty($product_id)
{
    if (empty($product_id)) {
        return true;
    } else {
        return false;
    }
}

function delete_product($product_id, $pdo)
{
    $sql = "DELETE FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $pdo = null;
    $stmt = null;
}

function delete_product_image($product_image_url)
{
    if ($product_image_url === 'uploads/no-image-icon.png') return;

    unlink('../' . $product_image_url);
}
