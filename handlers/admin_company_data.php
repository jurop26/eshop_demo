<?php

if (!isset($_SESSION["username"]) && empty($_SESSION["username"])) {
    header("Location: admin.php");
    die();
}

try {
    require_once 'handlers/connections/dbh.php';

    $result = get_company_data($pdo);

    if ($result) {
        $company_name = $result['company_name'];
        $company_ico = $result['company_ico'];
        $company_dic = $result['company_dic'];
        $company_icdph = $result['company_icdph'];
        $company_street = $result['company_street'];
        $company_house_number = $result['company_house_number'];
        $company_city = $result['company_city'];
        $company_postal_code = $result['company_postal_code'];

        echo company_data($company_name, $company_ico, $company_dic, $company_icdph, $company_street, $company_house_number, $company_city, $company_postal_code);
    }
} catch (Exception $e) {
    echo $e;
}

function company_data($company_name, $company_ico, $company_dic, $company_icdph, $company_street,  $company_house_number, $company_city, $company_postal_code)
{
    return '<div class="company-input-container">
                <form action="" method="post" id="company-register-form">
                    <ul class="company-register-data">
                        <li>
                            <label for="company-name">Názov spoločnosti:</label>
                            <input type="text" name="company-name" id="company-name" autocomplete="off" value="' . $company_name . '" disabled>
                        </li>
                        <li>
                            <label for="company-ico">IČO:</label>
                            <input type="text" name="company-ico" id="company-ico" autocomplete="off" value="' . $company_ico . '" disabled>
                        </li>
                        <li>
                            <label for="company-dic">DIČ:</label>
                            <input type="text" name="company-dic" id="company-dic" autocomplete="off" value="' . $company_dic . '" disabled>
                        </li>
                        <li>
                            <label for="company-icdph">IČ-DPH:</label>
                            <input type="text" name="company-icdph" id="company-icdph" autocomplete="off" value="' . $company_icdph . '" disabled>
                        </li>
                    </ul>
                    <input type="submit" value="Edit" form="company-register-form">
                    <hr>
                </form>
            </div>
            <div class="company-input-container">
                <form action=" method="post" id="company-address-form">
                    <ul class="company-register-data">
                        <li>
                            <label for="company-street">Ulica:</label>
                            <input type="text" name="company-street" id="company-street" autocomplete="off" value="' . $company_street . '" disabled>
                        </li>
                        <li>
                            <label for="company-house-number">Popisné číslo:</label>
                            <input type="text" name="company-house-number" id="company-house-number" autocomplete="off" value="' . $company_house_number . '" disabled>
                        </li>
                        <li>
                            <label for="company-city">Mesto:</label>
                            <input type="text" name="company-city" id="company-city" autocomplete="off" value="' . $company_city . '" disabled>
                        </li>
                        <li>
                            <label for="company-postal-code">PSČ:</label>
                            <input type="text" name="company-postal-code" id="company-postal-code" autocomplete="off" value="' . $company_postal_code . '" disabled>
                        </li>
                    </ul>
                    <input type="submit" value="Edit" form="company-address-form">
                    <hr>
                </form>
            </div>';
}

function get_company_data($pdo)
{
    $sql = 'SELECT * FROM company_data';
    $stmt =  $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $result;
}
