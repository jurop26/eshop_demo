<?php
require_once 'handlers/_config_session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="handlers/scripts/script.js" defer></script>
    <title>My Eshop Shopping Cart</title>
</head>

<body>
    <div class="main-container">
        <?php include('components/header.php') ?>
        <div>
            <hr>
        </div>
        <div class='scart-progress-container'>
            <ol class='progress-list'>
                <li><a href="scart_list.php">NÁKUPNÝ KOŠÍK</a></li>
                <li>DOPRAVA A PLATBA</li>
                <li>FAKTURAČNÉ A DORUČOVACIE ÚDAJE</li>
                <li>ZHRNUTIE OBJEDNÁVKY</li>
            </ol>
        </div>
        <div class="shopping-cart-list-container">
            <?php
            include('handlers/scart_list_rows.php');
            ?>
        </div>
        <div class="scart-total-price">
            <?php if (isset($_SESSION["total-price"]) && $_SESSION["total-price"] > 0) {
                echo "<span>CENA SPOLU:</span><span class='scart-total-price-span'>€" . $_SESSION['total-price'] . "</span>";
            }
            ?>
        </div>
        <div class="scart-list-bottom-buttons">
            <button>
                <a href="home">Späť do obchodu</a>
            </button>
            <?php
            if ($_SESSION["totalPieces"] < 1) {
                echo "<button disabled>Pokračovať</button>";
            } else {
                echo "<button><a href='scart_ship_payment.php'>Pokračovať</a></button>";
            }
            ?>
        </div>
        <?php include('components/footer.php'); ?>
    </div>
</body>

</html>