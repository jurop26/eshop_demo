<?php
require_once '_config_session.php';

$json_product = file_get_contents("php://input");
$product = json_decode($json_product, true);

$product_id = $product['product_id'];
$amount = $product['amount'];
$product_total_amount = $product['product_total_amount'];

$total_pieces = $amount;

if (!isset($_COOKIE["orderNo"])) {
    // generate a unique id for shopping cart
    $uniqid = uniqid();
    storeOrderNumberToCookies("orderNo", $uniqid);

    // created default json object for first save product to database with added [] symbols
    // MariaDb doesn't add [] symbols for json objects, why ?????
    $product = "[$json_product]";
    $_SESSION["totalPieces"] = $total_pieces;

    // create new shopping cart with added unique id
    require_once 'connections/dbh.php';
    create_shopping_cart($pdo, $uniqid, $product);

    $echo_response = array("message" => "Produkt bol pridany do kosika", "totalPieces" => $total_pieces);
} else {
    // get unique id from cookies
    $uniqid = getOrderNumberFromCookies("orderNo");

    // find and get existing shopping cart by unique id from database
    require_once 'connections/dbh.php';
    $result = get_shopping_cart($pdo, $uniqid);

    if ($result) {
        $json_existing_cart = $result;

        // converting json to array, then update existing shopping cart array and convert back to array
        $existing_cart = json_decode($json_existing_cart, true);

        // check if product already exists in shopping cart
        $key = findProductCartIndex($product_id, $existing_cart);
        if ($key !== false) {
            if (isset($amount)) {
                $prev_amount = $existing_cart[$key]["amount"];
                $amount += $prev_amount;
                $existing_cart[$key]["amount"] = "$amount";
            } else {
                $existing_cart[$key]["amount"] = "$product_total_amount";
            }
        } else {
            $existing_cart[] = $product;
        }

        $total_pieces = totalPiecesInCart($existing_cart);
        $_SESSION["totalPieces"] = $total_pieces;

        storeOrderNumberToCookies("orderNo", $uniqid);
        $current_timestamp = date('Y-m-d H:i:s');
        $json_updated_cart = json_encode($existing_cart);

        update_shopping_cart($pdo, $uniqid, $json_updated_cart, $current_timestamp);

        $echo_response = array("message" => "Produkt bol pridany do kosika", "totalPieces" => $total_pieces);
        die(json_encode($echo_response));
    }
}

function getOrderNumberFromCookies($cookies_name)
{
    return json_decode($_COOKIE[$cookies_name]);
}

function storeOrderNumberToCookies($cookies_name, $uniqid)
{
    setcookie($cookies_name, json_encode($uniqid), time() + 86400, '/');
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

function create_shopping_cart($pdo, $uniqid, $product)
{
    try {
        $sql = "INSERT INTO shopping_carts (s_cart_uniqid, s_cart_json_data) VALUES (:uniqid, :product)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uniqid', $uniqid);
        $stmt->bindParam(':product', $product);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Chyba pri vytvorení košíka") . $e->getMessage();
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

function findProductCartIndex($product_id, $existing_cart)
{
    $column = array_column($existing_cart, 'product_id');
    $key = array_search($product_id, $column, true);
    if ($key === false) return false; // 0 is false too
    return $key;
}

function totalPiecesInCart($existing_cart)
{
    return array_sum(array_column($existing_cart, 'amount'));
}
