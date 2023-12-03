<?php
require_once '_config_session.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_first_name = filter_input(INPUT_POST, 'user_first_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_last_name = filter_input(INPUT_POST, 'user_last_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $user_phone_number = filter_input(INPUT_POST, 'user_phone_number', FILTER_SANITIZE_NUMBER_INT);
    $user_password = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_confirmed_password = filter_input(INPUT_POST, 'user_confirmed_password', FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        require_once 'connections/dbh.php';

        // ERROR HANDLER
        $errors = [];

        if (is_input_empty($user_first_name, $user_last_name, $user_email, $user_phone_number, $user_password, $user_confirmed_password)) {
            $errors["input_empty"] = "Prosím, vyplnte všetky polia.";
        }

        if (is_email_invalid($user_email)) {
            $errors["email_invalid"] = "Nesprávny format emailovej adresy!";
        }

        if (email_exists($pdo, $user_email)) {
            $errors["email_exists"] = "Emailová adresa už bola zaregistrovaná iným uživateľom!";
        }

        if (passwords_dont_match($user_password, $user_confirmed_password)) {
            $errors["password_dont_match"] = "Heslá sa nestotožňujú";
        }

        if ($errors) {

            $_SESSION['errors'] = $errors;
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            die();
        }


        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
        store_user_into_database($pdo, $user_first_name, $user_last_name, $user_email, $user_phone_number, $hashed_password);

        header("Location: ../index.php");
        $pdo = null;
        $stmt = null;
        die();
    } catch (PDOException $e) {
        echo "Chyba pri registracii:" . $e->getMessage();
    }
}

function store_user_into_database($pdo, $user_first_name, $user_last_name, $user_email, $user_phone_number, $hashed_password)
{
    $sql = "INSERT INTO users (user_first_name, user_last_name, email, password, user_phone_number) 
            VALUES (:user_first_name, :user_last_name, :user_email, :hashed_password, :user_phone_number)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindparam(":user_first_name", $user_first_name);
    $stmt->bindparam(":user_last_name", $user_last_name);
    $stmt->bindparam(":user_email", $user_email);
    $stmt->bindparam(":hashed_password", $hashed_password);
    $stmt->bindparam(":user_phone_number", $user_phone_number);
    $stmt->execute();
}

function email_exists($pdo, $user_email)
{
    $sql = "SELECT email FROM users WHERE email = :user_email;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindparam(":user_email", $user_email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // $pdo = null;
    // $stmt = null;

    return $result;
}

function is_email_invalid($user_email)
{
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}


function passwords_dont_match($user_password, $user_confirmed_password)
{
    if ($user_password !== $user_confirmed_password) {
        return true;
    } else {
        return false;
    }
}

function is_input_empty($user_first_name, $user_last_name, $user_email, $user_phone_number, $user_password, $user_confirmed_password)
{
    if (empty($user_first_name) || empty($user_last_name) || empty($user_email) || empty($user_phone_number) || empty($user_password) || empty($user_confirmed_password)) {
        return true;
    } else {
        false;
    }
}
