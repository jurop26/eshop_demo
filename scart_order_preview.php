<?php
require_once 'handlers/_config_session.php';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['email-order'])
    && isset($_POST['fullname'])
    && isset($_POST['street-number'])
    && isset($_POST['city'])
    && isset($_POST['postal-code'])
    && isset($_POST['phone-code'])
    && isset($_POST['phone'])
    && isset($_SESSION['shipment'])
    && isset($_SESSION['payment'])
) {
    $order_email = htmlspecialchars($_POST['email-order']);
    $fullname = htmlspecialchars($_POST['fullname']);
    $street_number = htmlspecialchars($_POST['street-number']);
    $city = htmlspecialchars($_POST['city']);
    $postal_code = htmlspecialchars($_POST['postal-code']);
    $phone_code = htmlspecialchars($_POST['phone-code']);
    $phone = htmlspecialchars($_POST['phone']);
    $phone_number = $phone_code . $phone;
    $shipment = $_SESSION['shipment'];
    $payment = $_SESSION['payment'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="handlers/scripts/script.js" defer></script>
    <title>My Eshop | Order preview</title>
</head>

<body>
    <div class="main-container">
        <?php include('components/header.php') ?>
        <div class='scart-progress-container'>
            <ol class='progress-list'>
                <li><a href="scart_list.php">NÁKUPNÝ KOŠÍK</a></li>
                <li><a href="scart_ship_payment.php">DOPRAVA A PLATBA</a></li>
                <li><a href="scart_user_address.php">FAKTURAČNÉ A DORUČOVACIE ÚDAJE</a></li>
                <li><a href="scart_order_preview.php">ZHRNUTIE OBJEDNÁVKY</a></li>
            </ol>
        </div>
        <?php
        include('handlers/scart_table_preview.php');
        ?>
        <div class="scart-list-bottom-buttons">
            <button>
                <a href="scart_user_address.php">Späť na FAKTURAČNÉ A DORUČOVACIE ÚDAJE</a>
            </button>
            <?php
            if ($_SESSION["totalPieces"] < 1) {
                echo "<button disabled>OBJDENAŤ S POVINNOSŤOU PLATBY</button>";
            } else {
                echo '<form action="handlers/final_order_handler.php" method="post" id="final-order"><input type="submit" form="final-order" value="OBJDENAŤ S POVINNOSŤOU PLATBY"></form>';
            }
            ?>
        </div>
        <?php include('components/footer.php') ?>
    </div>
</body>

</html>