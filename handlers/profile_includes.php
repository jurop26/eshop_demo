<?php

require_once 'handlers/connections/dbh.php';
$email = $_SESSION["email"];
$user = get_user($pdo, $email);

if ($user) {
    foreach ($user as $person) {
        $user_street_address = $person["user_street_address"];
        $user_house_number = $person["user_house_number"];
        $user_city = $person["user_city"];
        $user_postal_code = $person["user_postal_code"];
        $user_phone_country = $person["user_phone_country"];
        $user_phone_number = $person["user_phone_number"];
    }
    // print_r($person);
    echo get_profile_form($user_street_address, $user_house_number, $user_city, $user_postal_code, $user_phone_country, $user_phone_number);
}


function get_profile_form($user_street_address, $user_house_number, $user_city, $user_postal_code, $user_phone_country, $user_phone_number)
{
    $option = $user_phone_country === "+421" ? '<option value="+421" selected>+421</option><option value="+420">+420</option>' :  '<option value="+421">+421</option><option value="+420" selected>+420</option>';

    $container = '
        <div>
            <h1 style="text-align: center;">Váš profil</h1>
        </div>
        <div class="preview-content-container">
            <form action="" id="form_order_information" method="post">
                <div class="form-row-container">
                    <div class="label-container">
                        <label for="email-order">Email</label>
                    </div>
                    <div class="input-container">
                        <input type="email" class="single-input" name="email-order" id="email-order" value="' . $_SESSION["email"] . '" autocomplete="email" required>
                    </div>
                </div>
                <div class="form-row-container">
                    <div class="label-container">
                        <label for="fullname">Meno a priezvisko</label>
                    </div>
                    <div class="input-container">
                        <input type="text" class="single-input" name="fullname" id="fullname" autocomplete="name" value="' . $_SESSION["username"] . '" required>
                    </div>
                </div>
                <div class="form-row-container">
                    <div class="label-container">
                        <label for="street-number">Ulica číslo</label>
                    </div>
                    <div class="input-container">
                        <input type="text" class="single-input" name="street-number" id="street-number" autocomplete="street-address" value="' . $user_street_address . ' ' . $user_house_number . '" required>
                    </div>
                </div>
                <div class="form-row-container">
                    <div class="label-container">
                        <label for="city">Mesto</label>
                    </div>
                    <div class="input-container">
                        <input type="text" class="single-input" name="city" id="city" autocomplete="address-level2" value="' . $user_city . '" required>
                    </div>
                </div>
                <div class="form-row-container">
                    <div class="label-container">
                        <label for="postal-code">PSČ</label>
                    </div>
                    <div class="input-container">
                        <input type="text" class="single-input" name="postal-code" id="postal-code" maxlength="6" size="6" autocomplete="postal-code" value="' . $user_postal_code . '" required>
                    </div>
                </div>
                <div class="form-row-container">
                    <div class="label-container">
                        <label for="phone">Telefón</label>
                    </div>
                    <div class="input-container phone-box">
                        <select name="phone-code">
                            ' . $option . '
                        </select>
                        <input type="tel" name="phone" id="phone" maxlength="9" autocomplete="phone" value="' . $user_phone_number . '" required>
                    </div>
                    <div class="form-row-container">
                        <div class="label-container">

                        </div>
                        <div class="submit-container">
                            <input type="submit" name="submit" value="Uložiť zmeny">
                        </div>
                    </div>
                </div>
            </form>
        </div>';
    return $container;
}

function get_user($pdo, $email)
{
    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;

        return $result;
    } catch (PDOException $e) {
        die("Nepodarilo sa načítať profil:" . $e->getMessage());
    }
}
