<?php
require_once '_config_session.php';

if (!isset($_SERVER["REQUEST_METHOD"]) === "POST") {
    header("Location: ../index.php");
    die();
}

$uniqid = json_decode($_COOKIE["orderNo"]);

require_once 'connections/dbh.php';

$shopping_cart = get_shopping_cart($pdo, $uniqid);

if ($shopping_cart) {
    $products_list = json_decode($shopping_cart, true);
    $products_list_ids = getProductIds($products_list);

    $products = get_products_data_based_on_ids($pdo, $products_list_ids);

    if ($products) {
        foreach ($products as $product) {
            $updated_products[$product["product_id"]] = [
                "product_stocked" => $product["product_stocked"] - getProductAmount($product["product_id"], $products_list),
                "product_sales" => getProductAmount($product["product_id"], $products_list)
            ];
        }

        update_products($pdo, $updated_products);
        delete_shopping_cart($pdo, $uniqid);
        $_SESSION['totalPieces'] = 0;
        setcookie("orderNo", '', time() - 3600, '/');
        header("Location: ../scart_final_order.php");
        die();
    }
}


function update_products($pdo, $updated_products)
{
    try {
        $sql = "UPDATE products SET product_stocked = :updated_stock, product_sales = :product_sales WHERE product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        foreach ($updated_products as $key => $value) {
            $stmt->bindParam(':updated_stock', $value["product_stocked"]);
            $stmt->bindParam(':product_sales', $value["product_sales"]);
            $stmt->bindParam(':product_id', $key);
            $stmt->execute();
        }
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Could not update products" . $e->getMessage());
    }
}

function delete_shopping_cart($pdo, $uniqid)
{
    try {
        $sql = "DELETE FROM shopping_carts WHERE s_cart_uniqid = :uniqid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uniqid', $uniqid);
        $stmt->execute();
        $pso = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Could not delete shopping_carts" . $e->getMessage());
    }
}

function get_products_data_based_on_ids($pdo, $products_list_ids)
{
    try {
        $bind_params = '(' . implode(',', array_fill(0, count($products_list_ids), '?')) . ')';
        $sql = "SELECT product_id, product_stocked FROM products WHERE product_id IN " . $bind_params;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($products_list_ids);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;

        return $result;
    } catch (PDOException $e) {
        die("Chyba načítania produktov") . $e->getMessage();
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
        die("Chyba pri načítaní nákupného košíka: ") . $e->getMessage();
    }
}

function getProductIds($products_list)
{
    $ids = array_column($products_list, 'product_id');
    return $ids;
}

function getProductAmount($product_id, $products_list)
{
    $key = array_search($product_id, array_column($products_list, 'product_id'));
    return $products_list[$key]['amount'];
}
