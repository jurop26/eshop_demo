<?php

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    echo '<form class="form-row-signin" id="form-row-signin" action="handlers/login_handler.php" method="post">
    <h4 class="form-content-description">PRIHLÁSIŤ SA</h4>
        <div class="form-row-container">
            <div class="label-container">
                <label for="email" class="asterix-required">Email</label>
            </div>
            <div class="input-container">
                <input type="email" class="single-input" name="email" id="email" autocomplete="email" required>
            </div>
        </div>
        <div class="form-row-container">
            <div class="label-container">
                <label for="password" class="asterix-required">Password</label>
            </div>
            <div class="input-container">
                <input type="password" class="single-input" name="password" id="password" autocomplete="off" required>
            </div>
        </div>
        <div class="submit-container">
            <input type="submit" form="form-row-signin" value="Prihlásiť">
        </div>
        </form>';
}

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    echo '<form action="scart_order_preview.php" id="form_order_information" method="post">
        <h4 class="form-content-description">FAKTURAČNÉ A DORUČOVACIE ÚDAJE:</h4>
        <div class="form-row-container">
            <div class="label-container">
                <label for="email-order" class="asterix-required">Email</label>
            </div>
            <div class="input-container">
                <input type="email" class="single-input" name="email-order" id="email-order" autocomplete="email" required>
            </div>
        </div>
        <div class="form-row-container">
            <div class="label-container">
                <label for="fullname" class="asterix-required">Meno a priezvisko</label>
            </div>
            <div class="input-container">
                <input type="text" class="single-input" name="fullname" id="fullname" autocomplete="name" value="" required>
            </div>
        </div>
        <div class="form-row-container">
            <div class="label-container">
                <label for="street-number" class="asterix-required">Ulica číslo</label>
            </div>
            <div class="input-container">
                <input type="text" class="single-input" name="street-number" id="street-number" autocomplete="street-address" value="" required>
            </div>
        </div>
        <div class="form-row-container">
            <div class="label-container">
                <label for="city" class="asterix-required">Mesto</label>
            </div>
            <div class="input-container">
                <input type="text" class="single-input" name="city" id="city" autocomplete="address-level2" value="" required>
            </div>
        </div>
        <div class="form-row-container">
            <div class="label-container">
                <label for="postal-code" class="asterix-required">PSČ</label>
            </div>
            <div class="input-container">
                <input type="text" class="single-input" name="postal-code" id="postal-code" maxlength="6" size="6" autocomplete="postal-code" value="" required>
            </div>
        </div>
        <div class="form-row-container">
            <div class="label-container">
                <label for="phone" class="asterix-required">Telefón</label>
            </div>
            <div class="input-container phone-box">
                <select name="phone-code">
                    <option value="+421">+421</option>
                    <option value="+420">+420</option>
                </select>
                <input type="tel" name="phone" id="phone" maxlength="9" autocomplete="phone" value="" required>
            </div>
        </div>
        </form>';
} else {
    $user_email = $_SESSION["email"];
    $username = explode(" ", $_SESSION["username"]);
    $first_name = $username[0];
    $last_name = $username[1];

    try {
        require_once 'connections/dbh.php';

        $result = get_user($pdo, $user_email);

        if ($result) {
            $email_order = $user_email;
            $fullname = $result['user_first_name'] . ' ' . $result['user_last_name'];
            $street_address = $result['user_street_address'] . ' ' . $result['user_house_number'];
            $city = $result['user_city'];
            $postal_code = $result['user_postal_code'];
            $phone_number = $result['user_phone_number'];
            echo getFormular($email_order, $fullname, $street_address, $city, $postal_code, $phone_number);
        }
    } catch (PDOException $e) {
        die("Chyba pri načítaní uživateľa: ") . $e->getMessage();
    }
}

?>
</form>
</div>
<div class="scart-list-bottom-buttons">
    <button>
        <a href="scart_ship_payment.php">Späť na DOPRAVA A PLATBA</a>
    </button>
    <?php
    if ($_SESSION["totalPieces"] < 1) {
        echo '<button disabled>OBJDENAŤ S POVINNOSŤOU PLATBY</button>';
    } else {
        echo '<input type="submit" form="form_order_information" value="Pokračovať">';
    }
    ?>
</div>
<?php include('components/footer.php'); ?>
</body>

</html>

<?php

function getFormular($email_order, $fullname, $street_address, $city, $postal_code, $phone_number)
{
    return '<form action="scart_order_preview.php" id="form_order_information" method="post">
        <h4 class="form-content-description">ADRESA DORUČENIA:</h4>
    <div class="form-row-container">
        <div class="label-container">
            <label for="email-order" class="asterix-required">Email</label>
        </div>
        <div class="input-container">
            <input type="email" class="single-input" name="email-order" id="email-order" autocomplete="email" value="' . $email_order . '" required>
        </div>
    </div>
    <div class="form-row-container">
        <div class="label-container">
            <label for="fullname" class="asterix-required">Meno a priezvisko</label>
        </div>
        <div class="input-container">
            <input type="text" class="single-input" name="fullname" id="fullname" autocomplete="name" value="' . $fullname . '" required>
        </div>
    </div>
    <div class="form-row-container">
        <div class="label-container">
            <label for="street-number" class="asterix-required">Ulica číslo</label>
        </div>
        <div class="input-container">
            <input type="text" class="single-input" name="street-number" id="street-number" autocomplete="street-address" value="' . $street_address . '" required>
        </div>
    </div>
    <div class="form-row-container">
        <div class="label-container">
            <label for="city" class="asterix-required">Mesto</label>
        </div>
        <div class="input-container">
            <input type="text" class="single-input" name="city" id="city" autocomplete="address-level2" value="' . $city . '" required>
        </div>
    </div>
    <div class="form-row-container">
        <div class="label-container">
            <label for="postal-code" class="asterix-required">PSČ</label>
        </div>
        <div class="input-container">
            <input type="text" class="single-input" name="postal-code" id="postal-code" maxlength="6" size="6" autocomplete="postal-code" value="' . $postal_code . '" required>
        </div>
    </div>
    <div class="form-row-container">
        <div class="label-container">
            <label for="phone" class="asterix-required">Telefón</label>
        </div>
        <div class="input-container phone-box">
            <select name="phone-code">
                <option value="+421">+421</option>
                <option value="+420">+420</option>
            </select>
            <input type="tel" name="phone" id="phone" maxlength="9" autocomplete="phone" value="' . $phone_number . '" required>
        </div>
    </div>
    </form>';
}

function get_user($pdo, $user_email)
{
    $sql = "SELECT * FROM users WHERE email = :user_email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindparam(':user_email', $user_email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $result;
}
