<?php
require_once '_config_session.php';

$json_product = file_get_contents('php://input');
$product = json_decode($json_product, true);

$product_id = $product["product_id"];

if (isset($_COOKIE["orderNo"])) {

    $uniqid = getOrderNumberFromCookies("orderNo");

    require_once 'connections/dbh.php';

    $result = get_shopping_cart($pdo, $uniqid);

    if ($result) {
        $json_existing_cart = $result;
        $existing_cart = json_decode($json_existing_cart, true);

        $key = findProductCartIndex($product_id, $existing_cart);
        if ($key !== false) {
            array_splice($existing_cart, $key, 1);
        }

        $total_pieces = totalPiecesInCart($existing_cart);
        $_SESSION["totalPieces"] = $total_pieces;

        storeOrderNumberToCookies("orderNo", $uniqid);
        $current_timestamp = date('Y-m-d H:i:s');

        $json_updated_cart = json_encode($existing_cart);

        update_shopping_cart($pdo, $uniqid, $json_updated_cart, $current_timestamp);

        $echo_response = array("message" => "Produkt bol vymazaný z košika", "totalPieces" => $total_pieces);
        die(json_encode($echo_response));
    }
}

function get_shopping_cart($pdo, $uniqid)
{
    try {
        $sql = "SELECT s_cart_json_data FROM shopping_carts WHERE s_cart_uniqid = :uniqid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uniqid', $uniqid);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        $pdo = null;
        $stmt = null;
        return $result;
    } catch (PDOException $e) {
        die("Nepodarilo sa načítať nákupný košík: ") . $e->getMessage();
    }
}

function update_shopping_cart($pdo, $uniqid, $json_updated_cart, $current_timestamp)
{
    try {
        $sql = "UPDATE shopping_carts SET s_cart_json_data = :json_updated_cart, s_cart_timestamp = :current_timestamp WHERE s_cart_uniqid = :uniqid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':json_updated_cart', $json_updated_cart, PDO::PARAM_STR);
        $stmt->bindParam(':current_timestamp', $current_timestamp);
        $stmt->bindParam(':uniqid', $uniqid);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Chyba pri updatovani košíka") . $e->getMessage();
    }
}

function getOrderNumberFromCookies($cookies_name)
{
    return json_decode($_COOKIE[$cookies_name]);
}

function storeOrderNumberToCookies($cookies_name, $uniqid)
{
    setcookie($cookies_name, json_encode($uniqid), time() + 86400);
}

function findProductCartIndex($product_id, $existing_cart)
{
    $column = array_column($existing_cart, 'product_id');
    $key = array_search($product_id, $column);
    if ($key === false) return false;
    return $key;
}

function totalPiecesInCart($existing_cart)
{
    return array_sum(array_column($existing_cart, 'amount'));
}
