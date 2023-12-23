<?php
require_once 'handlers/_config_session.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["shipment"]) && isset($_POST["payment"])) {
    $_SESSION["shipment"] = $_POST["shipment"];
    $_SESSION["payment"] = $_POST["payment"];
    $_SESSION["shipment_price"] = $_POST["shipment-price"];
    $_SESSION["payment_price"] = $_POST["payment-price"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="handlers/scripts/script.js" defer></script>
    <title>My Eshop | Zhrnutie</title>
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
                <li><a href="scart_ship_payment.php">DOPRAVA A PLATBA</a></li>
                <li><a href="scart_user_address.php">FAKTURAČNÉ A DORUČOVACIE ÚDAJE</a></li>
                <li>ZHRNUTIE OBJEDNÁVKY</li>
            </ol>
        </div>
        <div class="preview-content-container">
            <?php
            include('handlers/scart_address_handler.php');
