<?php

$json_company_data = file_get_contents('php://input');
$company_data = json_decode($json_company_data, true);

$insert = '(' . implode(', ', array_map(function ($key) {
    return $key;
}, array_keys($company_data))) . ')';

$values = '(' . implode(', ', array_map(function ($value) {
    return '\'' . $value . '\'';
}, array_values($company_data))) . ')';

$set = implode(', ', array_map(function ($key, $value) {
    return $key . '= \'' . $value . '\'';
}, array_keys($company_data), array_values($company_data)));

try {
    include('connections/database.php');
    $sql = "CREATE TABLE IF NOT EXISTS  company_data (
        company_name varchar(30) NULL,
        company_ico varchar(10) NULL,
        company_dic varchar(10) NULL,
        company_icdph varchar(10) NULL,
        company_street varchar(40) NULL,
        company_house_number varchar(10) NULL,
        company_city varchar(40) NULL,
        company_postal_code varchar(10) NULL
    )";
    $result = mysqli_query($conn, $sql);
    // mysqli_close($conn);


    // include('connections/database.php');
    $sql = "SELECT * FROM company_data";
    $result = mysqli_query($conn, $sql);
    // mysqli_close($conn);

    if (!mysqli_num_rows($result)) {
        $sql = "INSERT INTO company_data " . $insert . " VALUES " . $values;
        $sql = "INSERT INTO company_data (company_dic VALUES ('gfjhf')";
    } else {
        $sql = "UPDATE company_data SET " . $set;
    }
    // include('connections/database.php');
    mysqli_query($conn, $sql);
    mysqli_close($conn);


    $message = array('message' => 'ok');
    echo json_encode($message);
} catch (Exception $e) {
    echo $e;
}
