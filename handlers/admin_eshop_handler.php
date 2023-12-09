<?php

$order_by_option = ["ABC" => "product_name", "category" => "product_category", "sales" => "product_sales", "stock" => "product_stocked", "price" => "product_price", "brand" => "product_brand"];

if (
    isset($_POST['rows-in-list-option']) && !empty($_POST['rows-in-list-option'])
    && isset($_POST["sort-by-direction"]) && !empty($_POST["sort-by-direction"])
    && isset($_POST["sort-by-option"]) && !empty($_POST["sort-by-option"])
) {
    $order_by = $order_by_option[$_POST["sort-by-option"]];
    $rows = $_POST['rows-in-list-option'];
    $direction = $_POST['sort-by-direction'];
} else {
    $order_by = $order_by_option['stock'];
    $rows = 20;
    $direction = "DESC";
}

$result = get_products($order_by, $direction);
echo get_products_list($result, $rows);

function get_products_list($result, $rows)
{
    $list = "";
    $index = 1;

    // IF PRODUCTS LIST IS EMPTY THEN return empty list
    if (!$result) return;
    // IF PRODUCTS LIST IS FILLED THEN return list of products
    foreach ($result as $product) {
        $edit_button = '<td><form action="' . $_SERVER["PHP_SELF"] . '" method="post"><input type="hidden" name="product-id" value="' . $product["product_id"] . '" /><input type="submit" value="Edit" /></form></td>';
        $delete_button = '<td><form action=""><input type="hidden" value="' . $product["product_id"] . '"><input type="submit" value="Delete" /></form></td>';
        if ($index > $rows) break;
        $list .= '<tr><td>' . $product["product_bar_code"] . '</td><td>' . $product["product_name"] . '</td><td> €' . $product["product_price"] . '</td><td>' . $product["product_category"] . '</td><td>' . $product["product_brand"] . '</td><td>' . $product["product_stocked"] . '</td><td>' . $product["product_sales"] . '</td>' . $edit_button . $delete_button . '</tr>';
        $index++;
    }
    return '<table class="products-list-table"><tr><th>BAR-CODE</th><th>NÁZOV PRODUKTU</th><th>CENA</th><th>Kategória</th><th>ZNAČKA</th><th>SKLADOM</th><th>Predaj</th><th colspan="2"></th></tr>' . $list . '</table>';
}

function get_products($order_by, $direction)
{
    try {
        require_once 'connections/dbh.php';
        $sql = "SELECT * FROM products ORDER BY " . $order_by . " " . $direction;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;

        return $result;
    } catch (PDOException $e) {
        die("Chyba pri načítaní produktov podľa požiadaviek") . $e->getMessage();
    }
}
