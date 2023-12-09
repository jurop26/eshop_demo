<?php

if (!isset($_SESSION["admin_username"]) && empty($_SESSION["admin_username"])) {
    header("Location: admin.php");
    die();
}

try {
    require_once 'handlers/connections/dbh.php';

    $result = get_company_data($pdo);

    // if ($result) {
    $company_name = $result['company_name'] ?: " ";
    $company_ico = $result['company_ico'] ?: " ";
    $company_dic = $result['company_dic'] ?: " ";
    $company_icdph = $result['company_icdph'] ?: " ";
    $company_street = $result['company_street'] ?: " ";
    $company_house_number = $result['company_house_number'] ?: " ";
    $company_city = $result['company_city'] ?: " ";
    $company_postal_code = $result['company_postal_code'] ?: " ";
    $company_phone_number = $result['company_phone_number'] ?: " ";
    $company_mob_number = $result['company_mob_number'] ?: " ";
    $company_bank_name = $result['company_bank_name'] ?: " ";
    $company_bank_account = $result['company_bank_account'] ?: " ";

    echo company_data(
        $company_name,
        $company_ico,
        $company_dic,
        $company_icdph,
        $company_street,
        $company_house_number,
        $company_city,
        $company_postal_code,
        $company_phone_number,
        $company_mob_number,
        $company_bank_name,
        $company_bank_account
    );
    // }
} catch (PDOException $e) {
    die("Chyba pri načítaní firemných údajov" . $e->getMessage());
}

function company_data(
    $company_name,
    $company_ico,
    $company_dic,
    $company_icdph,
    $company_street,
    $company_house_number,
    $company_city,
    $company_postal_code,
    $company_phone_number,
    $company_mob_number,
    $company_bank_name,
    $company_bank_account
) {
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
                            <input type="text" name="company-postal-code" id="company-postal-code" size="5" autocomplete="off" value="' . $company_postal_code . '" disabled>
                        </li>
                    </ul>
                    <input type="submit" value="Edit" form="company-address-form">
                    <hr>
                </form>
            </div>
            <div class="company-input-container">
            <form action=" method="post" id="company-bank-account-form">
                <ul class="company-register-data">
                    <li>
                        <label for="company-bank-name">Názov bankového ústavu:</label>
                        <input type="text" name="company-bank-name" id="company-bank-name" size="26" autocomplete="off" value="' . $company_bank_name . '" disabled>
                    </li>
                    <li>
                        <label for="company-bank-account">Číslo bankového účtu v tvare IBAN:</label>
                        <input type="text" name="company-bank-account" id="company-bank-account" size="26" autocomplete="off" value="' . $company_bank_account . '" disabled>
                    </li>
                </ul>
                <input type="submit" value="Edit" form="company-bank-account-form">
                <hr>
            </form>
            </div>
            <div class="company-input-container">
                <form action=" method="post" id="company-phone-number-form">
                    <ul class="company-register-data">
                        <li>
                            <label for="company-phone-number">Tel. kontakt:</label>
                            <input type="text" name="company-phone-number" id="company-phone-number" autocomplete="off" value="' . $company_phone_number . '" disabled>
                        </li>
                        <li>
                            <label for="company-mob-number">Mob. kontakt:</label>
                            <input type="text" name="company-mob-number" id="company-mob-number" autocomplete="off" value="' . $company_mob_number . '" disabled>
                        </li>
                    </ul>
                    <input type="submit" value="Edit" form="company-bank-account-form">
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
