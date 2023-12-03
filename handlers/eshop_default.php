<form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="sort-by-form">
    <div>
        <label for="sort-by-option">Zoradiť podľa: </label>
        <select name="sort-by-option" id="sort-by-option">
            <?php
            $sort_by_values = ["ABC" => "Abecedy", "category" => "Kategórie", "sales" => "Predaja", "stock" => "Stavu na sklade", "price" => "Ceny", "brand" => "Značky"];
            $sort_by_option = "";
            $sort_by_default_value = isset($_POST["sort-by-option"]) ? $_POST["sort-by-option"] : "ABC";

            foreach ($sort_by_values as $key => $sort_by_value) {
                if ($key === $sort_by_default_value) {
                    $sort_by_option .= '<option value="' . $key . '" selected>' . $sort_by_value . '</option>';
                } else {
                    $sort_by_option .= '<option value="' . $key . '">' . $sort_by_value . '</option>';
                }
            }
            echo $sort_by_option;
            ?>
        </select>
    </div>
    <div>
        <label for="sort-by-direction">&uarr;&darr;</label>
        <select name="sort-by-direction" id="sort-by-direction">
            <?php
            $direction_values = ["DESC" => "Zostupne", "ASC" => "Vzostupne"];
            $direction_option = "";
            $direction_default_value = isset($_POST["sort-by-direction"]) ? $_POST["sort-by-direction"] : "DESC";

            foreach ($direction_values as $key => $direction_value) {
                if ($key === $direction_default_value) {
                    $direction_option .= '<option value="' . $key . '" selected>' . $direction_value . '</option>';
                } else {
                    $direction_option .= '<option value="' . $key . '">' . $direction_value . '</option>';
                }
            }
            echo $direction_option;
            ?>
            <option value="DESC">Vzostupne</option>
            <option value="ASC">Zostupne</option>
        </select>
    </div>
    <div>
        <label for="rows-in-list-option">Počet produktov: </label>
        <select name="rows-in-list-option" id="rows-in-list-option">
            <?php
            $row_values = ["5", "10", "15", "20", "25", "30"];
            $row_option = "";
            $row_default_value = isset($_POST["rows-in-list-option"]) ? $_POST["rows-in-list-option"] : "20";

            foreach ($row_values as $row_value) {
                if ($row_value === $row_default_value) {
                    $row_option .= '<option value="' . $row_value . '" selected>' . $row_value . '</option>';
                } else {
                    $row_option .= '<option value="' . $row_value . '">' . $row_value . '</option>';
                }
            }
            echo $row_option;
            ?>
        </select>
    </div>
    <input type="hidden" name="layout" value="Eshop">
    <input type="submit" name="submit" value="Filtruj">
</form>
<div class="products-list-container">
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
    ?>
</div>
<div class="add-product-container">
    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
        <input type="submit" name="layout" value="Pridať nový produkt">
    </form>
</div>

<?php

function get_products_list($result, $rows)
{
    $list = "";
    $index = 1;

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
    require_once 'connections/dbh.php';
    $sql = "SELECT * FROM products ORDER BY " . $order_by . " " . $direction;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $result;
}
