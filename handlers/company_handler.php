<?php

// CHECKING IF ADMIN IS LOGGED IN, IF NOT REDIRECTED
if (!isset($_SESSION["admin_username"]) && empty($_SESSION["admin_username"])) {
    header("Location: admin.php");
    die();
}

$json_company_data = file_get_contents('php://input');
$company_data = json_decode($json_company_data, true);

include('connections/dbh.php');

$company_exists = get_company_data($pdo, $sql);

if (!$company_exists) {
    insert_company_data($pdo, $company_data);
} else {
    update_company_data($pdo, $company_data);
}
$message = array('message' => 'ok');
die(json_encode($message));

// FUNCTIONS TO HANDLE DATABASE DATA
function insert_company_data($pdo, $company_data)
{
    $insert = '(' . implode(', ', array_map(function ($key) {
        return $key;
    }, array_keys($company_data))) . ')';
    $bind_params = '(' . implode(',', array_fill(0, count($company_data), '?')) . ')';

    try {
        $sql = "INSERT INTO company_data " . $insert . " VALUES " . $bind_params;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($company_data));
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Chyba pri zapisovaní do INSERT company data: " . $e->getMessage());
    }
}

function update_company_data($pdo, $company_data)
{
    $set = implode(', ', array_map(function ($key) {
        return $key . ' = :' . $key;
    }, array_keys($company_data)));

    try {
        $sql = "UPDATE company_data SET " . $set;
        $stmt = $pdo->prepare($sql);
        foreach ($company_data as $key => $value) {
            $stmt->bindValue(":" . $key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Chyba pri zapisovaní do UPDATE company data: " . $e->getMessage());
    }
}

function get_company_data($pdo, $sql)
{
    try {
        $sql = "SELECT * FROM company_data";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        die("Chyba pri načítavaní firemných údajov: " . $e->getMessage());
    }
}
