<?php

require_once '_config_session.php';

// CHECKING IF ADMIN IS LOGGED IN, IF NOT REDIRECTED
if (!isset($_SESSION["username"]) && empty($_SESSION["username"])) {
    header("Location: ../index.php");
    die();
}

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["password"]) && !empty($_POST["password"]) &&
    isset($_POST["new-password"]) && !empty($_POST["new-password"])
) {
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_password = filter_input(INPUT_POST, "new-password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_SESSION["email"];
    require_once 'connections/dbh.php';

    //ERROR HANDLER
    $errors = [];

    if (is_password_incorrect($pdo, $email, $password)) {
        $errors["password_incorrect"] = "Nesprávne prihlasovacie údaje!";
    }

    if ($errors) {
        $_SESSION["errors"] = $errors;
        header("Location: ../profile.php");
        die();
    }

    update_user_password($pdo, $email, $new_password);

    $message["success"] = "Vaše údaje v profile boli zmenené";
    $_SESSION["message"] = $message;

    header("Location: ../profile.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["password"])) {

    $email = $_SESSION["email"];
    $new_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $user_fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_street_and_number = filter_input(INPUT_POST, 'street-number', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_postal_code = filter_input(INPUT_POST, 'postal-code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_phone_country = filter_input(INPUT_POST, 'phone-code', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_phone_number = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);

    $user_first_name = explode(' ', $user_fullname)[0];
    $user_last_name = explode(' ', $user_fullname)[1];
    $user_street_address = explode(' ', $user_street_and_number)[0];
    $user_house_number = explode(' ', $user_street_and_number)[1];

    require_once 'connections/dbh.php';

    //ERROR HANDLER OR MESSAGE
    $errors = [];
    $message = [];

    if (email_exists($pdo, $email, $new_email)) {
        $errors["email_exists"] = "Email už používa iný profil";
    }

    if ($errors) {
        $_SESSION["errors"] = $errors;
        header("Location: ../profile.php");
        die();
    }

    update_user($pdo, $email, $new_email, $user_first_name, $user_last_name, $user_street_address, $user_house_number, $user_city, $user_postal_code, $user_phone_country, $user_phone_number);
    $_SESSION["email"] = $new_email;

    $message["success"] = "Vaše údaje v profile boli zmenené";
    $_SESSION["message"] = $message;
    header("Location: ../profile.php");
    die();
} else {
    header("Location: ../profile.php");
    die();
}

function is_password_incorrect($pdo, $email, $password)
{
    try {
        $sql = "SELECT password FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $hashed_password = $stmt->fetch(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        die("Nepodarilo sa načítať uživateľa: " . $e->getMessage());
    }
    return !password_verify($password, $hashed_password);
}

function update_user_password($pdo, $email, $new_password)
{
    try {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :new_password WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':new_password', $new_password);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Nepodarilo sa uložiť nové heslo: " . $e->getMessage());
    }
}

function update_user($pdo, $email, $new_email, $user_first_name, $user_last_name, $user_street_address, $user_house_number, $user_city, $user_postal_code, $user_phone_country, $user_phone_number)
{
    try {
        $sql = "UPDATE users SET 
            user_first_name = :first_name, 
            user_last_name = :last_name, 
            email = :new_email, 
            user_street_address = :street_address, 
            user_house_number = :house_number, 
            user_city = :city, 
            user_postal_code = :postal, 
            user_phone_country = :phone_country, 
            user_phone_number = :phone_number 
            WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $user_first_name);
        $stmt->bindParam(':last_name', $user_last_name);
        $stmt->bindParam(':new_email', $new_email);
        $stmt->bindParam(':street_address', $user_street_address);
        $stmt->bindParam(':house_number', $user_house_number);
        $stmt->bindParam(':city', $user_city);
        $stmt->bindParam(':postal', $user_postal_code);
        $stmt->bindParam(':phone_country', $user_phone_country);
        $stmt->bindParam(':phone_number', $user_phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Nepodarilo sa upgradovať profil: " . $e->getMessage());
    }
}

function email_exists($pdo, $email, $new_email)
{
    if ($email === $new_email) {
        return false;
    }
    try {
        $sql = "SELECT email FROM users WHERE email = :new_email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':new_email', $new_email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        $pdo = null;
        $stmt = null;
        if ($result) return true;
        else return false;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
