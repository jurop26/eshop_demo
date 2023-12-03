<?php
$product_detail_view = false;

if (isset($_POST['category-button'])) {
    $current_category = $_POST['category-button'];
    $sql = "SELECT * FROM products WHERE product_category = '$current_category'";
} else {
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        $product_detail_view = true;
        $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
    } else {
        $current_category = 'Hriadele, cinky, zavazia';
        $sql = "SELECT * FROM products WHERE product_category = '$current_category'";
    }
}
unset($_GET['product_id']);

try {
    require_once 'connections/dbh.php';

    $result = get_products_details($pdo, $sql);

    if ($result) {
        foreach ($result as $row) {
            $product_id = $row['product_id'];
            $product_bar_code = $row['product_bar_code'];
            $product_name = $row['product_name'];
            $product_price = $row['product_price'];
            $product_description = $row['product_description'];
            $product_stocked = $row['product_stocked'] > 0 ? "Skladom" : "Nedostupne";
            $product_image = base64_encode($row['product_image']);

            if (!$product_detail_view) {
                echo product_container($product_id, $product_name, $product_price, $product_image, $product_bar_code, $product_stocked);
            } else {
                echo product_detail_container($product_id, $product_name, $product_description, $product_price, $product_image, $product_bar_code, $product_stocked);
            }
        }
    }
} catch (PDOException $e) {
    die("Chyba pri nacitani produktov: ") . $e->getMessage();
}

// Product container - square structure
function product_container($product_id, $product_name, $product_price, $product_image, $product_bar_code, $product_stocked)
{
    $container = "<div class='item-container'>
                    <a href='index.php?product_id=$product_id' class='product-image'>
                        <img src='data:image/jpg;charset=utf8;base64,$product_image'>
                    </a>
                    <a href='index.php?product_id=$product_id' class''>
                        <div class='item-name'>$product_name</div>
                    </a>
                    <div class='item-stock stocked'>$product_stocked</div>
                    <div class='item-price-basket-container'>
                        <div class='item-price'>
                            €$product_price
                        </div>
                            <form class='action-cart'>
                                <input type='hidden' name='product-id' value='$product_id'>
                                <input type='hidden' name='product-name' value='$product_name'>
                                <input type='hidden' name='product-price' value='$product_price'>
                                <input type='hidden' name='product-bar-code' value='$product_bar_code'>
                                <input type='hidden' name='product-amount' value='1'>
                                <input type='submit' name='submit' class='item-basket' value='Do kosika'>
                            </form>
                    </div>
                </div>";
    return $container;
}

function product_detail_container($product_id, $product_name, $product_description, $product_price, $product_image, $product_bar_code, $product_stocked)
{
    $container = "<div class='detail-container'>
                    <div class='item-description-container'>
                        <h1>$product_name</h1>
                        <img src='data:image/jpg;charset=utf8;base64,$product_image'>
                        <div class='product-description'>$product_description</div>
                        </div>
                        <div class='cart-container'>
                            <div class='detail-item-price'>€$product_price</div>
                            <div class='detail-item-stock stocked'>$product_stocked</div>
                            <form class='action-cart'>
                                <input type='hidden' name='product-id' value='$product_id'>
                                <input type='hidden' name='product-name' value='$product_name'>
                                <input type='hidden' name='product-price' value='$product_price'>
                                <input type='hidden' name='product-bar-code' value='$product_bar_code'>
                                <input type='number' name='product-amount' class='cart-input' min='1' max='999' value='1'>
                                <div class='cart-arrows-container'>
                                    <div class='cart-arrow-button increment' role='button'>+</div>
                                    <div class='cart-arrow-button decrement' role='button'>-</div>
                                </div>
                                <input type='submit' name='submit' class='cart-submit-button' value='Pridat do kosika'>
                            </form>
                        </div>
                    </div>
                </div>";
    return $container;
}

function get_products_details($pdo, $sql)
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $result;
}
