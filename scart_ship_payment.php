<?php

require_once 'handlers/_config_session.php';

$shipment = ['123 Kuriér' => 1.99, 'ParcelForce' => 1.99, 'UPS' => 2.30, 'Slovenská pošta - kuriér' => 2.40];
$payment = ['Dobierka' => 1.00, 'VISA' => 0, 'Apple Pay' => 0, 'Google Pay' => 0];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="handlers/scripts/script.js" defer></script>
    <title>My Eshop | Payment</title>
</head>

<body>
    <div class="main-container">
        <?php include('components/header.php') ?>
        <div>
            <hr>
        </div>
        <div class='scart-progress-container'>
            <ol class='progress-list' id='progress-list'>
                <li><a href="scart_list.php">NÁKUPNÝ KOŠÍK</a></li>
                <li><a href="scart_list_payment.php">DOPRAVA A PLATBA</a></li>
                <li>FAKTURAČNÉ A DORUČOVACIE ÚDAJE</li>
                <li>ZHRNUTIE OBJEDNÁVKY</li>
            </ol>
        </div>
        <div class='radio-content-container'>
            <div class="left-container">
                <form class='scart-form-payment' id='scart-form-ship-payment' action='scart_user_address.php' method='post'>
                    <h4 class="form-content-description">ZVOĽTE SPÔSOB DOPRAVY</h4>
                    <?php
                    foreach ($shipment as $currier => $shipment_price) {
                        echo '<span>
                            <input type="radio" name="shipment" value="' . $currier . '">' . $currier . '</input>
                            <input type="hidden" name="shipment-price" value="' . $shipment_price . '" />
                            </span>';
                    }
                    echo "<h4 class='form-content-description'>ZVOĽTE SPÔSOB DOPRAVY</h4>";
                    foreach ($payment as $payment_method => $payment_price) {
                        echo '<span>
                            <input type="radio" name="payment" value="' . $payment_method . '">' . $payment_method . '</input>
                            <input type="hidden" name="payment-price" value="' . $payment_price . '" />
                            </span>';
                    }
                    ?>
                </form>
            </div>
            <div class="right-container">
                <?php
                echo "<h4 class='form-content-description'>DOPLATOK</h4>";
                foreach ($shipment as $currier => $shipment_price) {
                    $shipment_price = number_format($shipment_price, 2);
                    echo "<span>€$shipment_price</span>";
                }
                echo "<h4 class='form-content-description'>DOPLATOK</h4>";
                foreach ($payment as $payment_method => $payment_price) {
                    $payment_price = number_format($payment_price, 2);
                    echo "<span>€$payment_price</span>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="scart-list-bottom-buttons">
        <button>
            <a href="scart_list.php">Späť na nákupný košík</a>
        </button>
        <?php
        if ($_SESSION["totalPieces"] < 1) {
            echo "<button disabled>Pokračovať</button>";
        } else {
            echo "<button type='submit' form='scart-form-ship-payment'>Pokračovať</button>";
        }
        ?>
    </div>
    <?php include('components/footer.php'); ?>
</body>

</html>

<?php
