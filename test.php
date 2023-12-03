<?php
$company_data = ["company_dic" => "gfjhf", "company_icdph" => "hgjgh", "company_ico" => "sdfgsdg", "company_name" => "aaaa"];

// $set = implode(', ', array_map(function ($key, $value) {
//     return $key . '= \'' . $value . '\'';
// }, array_keys($company_data), array_values($company_data)));

// $insert = '(' . implode(', ', array_map(function ($key) {
//     return $key;
// }, array_keys($company_data))) . ')';

// $values = '(' . implode(', ', array_map(function ($value) {
//     return '\'' . $value . '\'';
// }, array_values($company_data))) . ')';

include('connections/database.php');
$sql = "INSERT INTO company_data (company_name) VALUES ('pica')";
mysqli_query($conn, $sql);
mysqli_close($conn);
