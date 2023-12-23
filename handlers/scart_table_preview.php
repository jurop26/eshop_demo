<?php
require_once 'connections/dbh.php';

$price_total = [];
$uniqid = json_decode($_COOKIE['orderNo']);
$result = get_shopping_cart($pdo, $uniqid);

if ($result) {

    $products_list = json_decode($result, true);

    if (!empty($products_list)) {
        $products_list_ids = getProductIds($products_list);
        $result = get_products_data_based_on_ids($pdo, $products_list_ids);

        if ($result) {
            foreach ($result as $key => $row) {
                $key += 1;
                $product_id = $row['product_id'];
                $product_bar_code = $row['product_bar_code'];
                $product_name = $row['product_name'];
                $product_price = $row['product_price'];
                $product_description = $row['product_description'];
                $product_stocked = $row['product_stocked'] > 0 ? "Skladom" : "Nedostupne";
                $product_image = $row['product_image'];
                $product_amount = getProductAmount($product_id, $products_list);
                $product_price_total = number_format(($product_price * $product_amount), 2, '.');

                $price_total[] = $product_price_total;
                $final_order[$product_id] = $row['product_stocked'];

                $table_rows .= '<tr align="center"><td>' . $key . '</td><td>' . $product_bar_code . '</><td class="product-name" >' . $product_name . '</td><td class="align-right">€' . $product_price . ' /ks</td><td>' . $product_amount . '</td><td class="align-right" >€' . $product_price_total . '</td></tr>';
            }
            $shipment = $_SESSION["shipment"];
            $shipment_price = $_SESSION["shipment_price"];
            $payment_type = $_SESSION["payment"];
            $payment_type_price = $_SESSION["payment_price"];

            $shipment_price = number_format($shipment_price, 2, '.');
            $payment_type_price = number_format($payment_type_price, 2, '.');
            $price_total = number_format(array_sum($price_total) + $shipment_price + $payment_type_price, 2, '.');
            $price_DPH = number_format($price_total * 0.2, 2, '.');
            $price_total_without_DPH = number_format(($price_total - $price_DPH), 2, '.');

            echo '<table>
                        <tbody>
                            <caption>OBJEDNÁVKA</caption>
                            <tr><th>P.č.</th><th>barcode</><th>Názov produktu</th><th class="align-right">cena/ks</th><th>kusy</th><th class="align-right">cena s DPH</th</tr>
                            <tr><td colspan="6"><hr></td></tr>
                            ' . $table_rows . '
                            <tr align="center"><td></td><td></><td class="product-name">' . $shipment . '</td><td></td><td></td><td class="align-right" >€' . $shipment_price . '</td></tr>
                            <tr align="center"><td></td><td></><td class="product-name">' . $payment_type . '</td><td></td><td></td><td class="align-right" >€' . $payment_type_price . '</td></tr>
                            <tr><td colspan="6"><hr></td></tr>
                            <tr>
                            <th align="left" colspan="2">Doručovacia adresa:</th>
                            <th></th>
                            <td colspan="2" rowspan="1">Suma bez DPH</td><th class="align-right">€' . $price_total_without_DPH . '</td>
                            </tr>
                            <tr><td colspan="2">' . $fullname . '</td><td><td colspan="2" rowspan="1">DPH 20%</td><th class="align-right"> €' . $price_DPH . '</td></td></tr>
                            <tr><td colspan="2">' . $street_number . '</td><td></td><td colspan="2" rowspan="3"><b>Suma spolu s DPH</b></td><th class="price-total" rowspan="3"> €' . $price_total . ',-</td></tr>
                            <tr><td colspan="2">' . $city . '</td><td><b>Email:</b> ' . $order_email . '</td></tr>
                            <tr><td colspan="2">' . $postal_code . '</td><td><b>Tel. č.:</b> ' . $phone_number . '</td></tr>
                        </tbody>
                    </table>';
        }
    }
}

function get_products_data_based_on_ids($pdo, $products_list_ids)
{
    try {
        $bind_params = '(' . implode(',', array_fill(0, count($products_list_ids), '?')) . ')';
        $sql = "SELECT * FROM products WHERE product_id IN " . $bind_params;
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
        $stmt->bindParam(":uniqid", $uniqid);
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
