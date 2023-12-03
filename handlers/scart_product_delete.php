<?php
session_start();

$json_product = file_get_contents('php://input');
$product = json_decode($json_product, true);

$product_id = $product["product_id"];

if (isset($_COOKIE["orderNo"])) {

    $uniqid = getOrderNumberFromCookies("orderNo");
    try {
        $sql = "SELECT * FROM shopping_carts WHERE `s_cart_uniqid` = '$uniqid'";
        include('connections/database.php');
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $json_existing_cart = $row['s_cart_json_data'];
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
            $sql = "UPDATE shopping_carts SET s_cart_json_data = '$json_updated_cart', s_cart_timestamp = '$current_timestamp' 
                    WHERE s_cart_uniqid = '$uniqid'";
            try {
                include('connections/database.php');
                mysqli_query($conn, $sql);
                mysqli_close($conn);

                $echo_response = array("message" => "Produkt bol vymazaný z košika", "totalPieces" => $total_pieces);
                echo json_encode($echo_response);
                exit();
            } catch (Exception $e) {
                echo $e;
            }
        }
    } catch (Exception $e) {
        echo $e;
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
