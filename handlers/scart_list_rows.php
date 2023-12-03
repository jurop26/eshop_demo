<?php

if (!isset($_COOKIE['orderNo'])) {
    unset($_SESSION['totalPieces']);
    echo emptyCart();
} else {

    $price_total = [];
    $uniqid = json_decode($_COOKIE["orderNo"]);

    try {
        require_once 'connections/dbh.php';

        $result = get_shopping_cart_data($pdo, $uniqid);

        if ($result) {
            $products_list = json_decode($result, true);

            if (!empty($products_list)) {
                $products_list_ids = getProductIds($products_list);
                $result = get_products_ids_from_products_list($pdo, $products_list_ids);

                if ($result) {
                    foreach ($result as $row) {
                        $product_id = $row['product_id'];
                        $product_bar_code = $row['product_bar_code'];
                        $product_name = $row['product_name'];
                        $product_price = $row['product_price'];
                        $product_description = $row['product_description'];
                        $product_stocked = $row['product_stocked'] > 0 ? "Skladom" : "Nedostupne";
                        $product_image = base64_encode($row['product_image']);
                        $product_amount = getProductAmount($product_id, $products_list);
                        $product_price_total = $product_price * $product_amount;
                        $price_total[] = $product_price_total;

                        echo scart_row($product_id, $product_image, $product_stocked, $product_name, $product_amount, $product_price, $product_price_total);
                    }
                    $_SESSION['total-price'] = array_sum($price_total);
                }
            } else {
                $_SESSION['total-price'] = array_sum($price_total);
                echo emptyCart();
            }
        } else {
            echo emptyCart();
        }
    } catch (PDOException $e) {
        echo "Chyba pri nacitani produktov: " . $e;
    }
}

function scart_row($product_id, $product_image, $product_stocked, $product_name, $product_amount, $product_price, $product_price_total)
{
    $row = '<div class="scart-row-container">
                <div class="scart-row-product-img"><img src="data:image/jpg;charset=utf8;base64,' . $product_image . '"></div>
                <div class="scart-row-product-name">' . $product_name . '</div>
                <div class="scart-row-product-stocked stocked">' . $product_stocked . '</div>
                <div class="scart-row-product-price">€' . $product_price . '/ks</div>
                <div class="scart-row-product-amount-container"> 
                    <form class="action-cart">
                        <input type="hidden" name="product-id" value="' . $product_id . '">
                        <input type="number" name="product-total-amount" class="cart-input " min="0" max="999" value="' . $product_amount . '">
                        <div class="cart-arrows-container">
                            <div class="cart-arrow-button increment increase" role="button">+</div>
                            <div class="cart-arrow-button decrement decrease" role="button">-</div>
                        </div>
                    </form>                  
                </div>
                <div class="scart-row-product-price-total">€' . $product_price_total . '</div>
                <form class="scart-product-delete">
                    <input type="hidden" name="product-id" value="' . $product_id . '">
                    <button type="submit" class="scart-row-delete-cross"><img src="img/cross.png"></button>
                </form>
            </div>';
    return $row;
}

function get_products_ids_from_products_list($pdo, $products_list_ids)
{
    $bind_params = '(' . implode(',', array_fill(0, count($products_list_ids), '?')) . ')';
    $sql = "SELECT * FROM products WHERE product_id IN " . $bind_params;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($products_list_ids);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $result;
}

function get_shopping_cart_data($pdo, $uniqid)
{
    $sql = "SELECT s_cart_json_data FROM shopping_carts WHERE s_cart_uniqid = :uniqid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindparam(':uniqid', $uniqid);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_COLUMN);
    $pdo = null;
    $stmt = null;

    return $result;
}

function emptyCart()
{
    return "<div class='scart-list-empty'>Váš košík je prázdny</div>";
}

function getProductAmount($product_id, $products_list)
{
    $key = array_search($product_id, array_column($products_list, 'product_id'));
    return $products_list[$key]['amount'];
}

function getProductIds($products_list)
{
    $ids = array_column($products_list, 'product_id');

    return $ids;
}
