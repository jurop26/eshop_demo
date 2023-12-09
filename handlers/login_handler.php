<?php
require_once '_config_session.php';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    // user already logged in, redirect back to home page
    if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
        header("Location: ../index.php");
        die();
    }

    // user is not logged in
    $user_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $user_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $username = null;

    if ($_SERVER["HTTP_REFERER"] === 'http://localhost/website/admin.php') {
        $sql = "SELECT * FROM admin WHERE email = :user_email;";
    } else {
        $sql = "SELECT * FROM users WHERE email = :user_email;";
    }

    try {
        require_once 'connections/dbh.php';

        // ERRORS HANDLERS
        $errors = [];
        if (is_input_empty($user_email, $user_password)) {
            $errors["inputs_empty"] = "Nezadali ste prihlasovacie údaje!!!";
        }

        if (is_email_invalid($user_email)) {
            $errors["email_invalid"] = "Nesprávny format emailovej adresy!";
        }

        $result = get_user($pdo, $sql, $user_email);
        if (is_email_incorrect($result)) {
            $errors["login_incorrect"] = "Nesprávne prihlasovacie údaje!";
        }
        if (is_password_incorrect($user_password, $result)) {
            $errors["login_incorrect"] = "Nesprávne prihlasovacie údaje!";
        }

        if ($errors) {
            $_SESSION["errors"] = $errors;
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            die();
        }

        $username = $result['user_first_name'] . " " . $result['user_last_name'];

        if ($_SERVER["HTTP_REFERER"] === 'http://localhost/website/login.php') {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $user_email;
            header("Location: ../index.php");
            die();
        }
        if ($_SERVER["HTTP_REFERER"] === 'http://localhost/website/scart_user_address.php') {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $user_email;
            header("Location: ../scart_user_address.php");
            die();
        }
        if ($_SERVER["HTTP_REFERER"] === 'http://localhost/website/admin.php') {
            header("Location: ../admin_eshop.php");
            $_SESSION['admin_username'] = $username;
            $_SESSION['email'] = $user_email;
            die();
        }
    } catch (PDOException $e) {
        echo "No user found:" . $e->getMessage();
    }
} else {
    header("Location: home");
    die();
};


function is_email_incorrect($result)
{
    if (!$result) {
        return true;
    } else {
        return false;
    }
}

function is_email_invalid($user_email)
{
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_input_empty($user_email, $user_password)
{
    if (empty($user_email) || empty($user_password)) {
        return true;
    } else {
        return false;
    }
}

function get_user($pdo, $sql, $user_email)
{
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_email', $user_email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $result;
}

function is_password_incorrect($user_password, $result)
{
    $hashed_password = $result['password'];
    $user_password = password_verify($user_password, $hashed_password);
    if (!$user_password) {
        return true;
    } else {
        return false;
    }
}
