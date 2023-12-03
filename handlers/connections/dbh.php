<?php
$host = 'localhost';
$dbname = 'eshop';
$user = 'root';
$psw = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $psw);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed:" . $e->getMessage());
}
