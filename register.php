<?php
require_once 'handlers/_config_session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>My Ehop | Register</title>
</head>

<body>
    <header>
        <h1 style="text-align: center;">Registrácia</h1>
    </header>
    <div class="form-container">
        <form action="handlers/register_handler.php" method="post" class="form">
            <input type="text" name="user_first_name" placeholder="Vase meno" required>
            <input type="text" name="user_last_name" placeholder="Vase priezvisko" required>
            <input type="email" name="user_email" placeholder="Zadajte email" required>
            <span>
                <select>
                    <option name="user_phone_number_code" value="00421" selected>00421</option>
                    <option name="user_phone_number_code" value="00420" selected>00420</option>
                </select>

                <input type="number" name="user_phone_number" placeholder="Zadajte telefonne cislo" required>
            </span>
            <input type="password" name="user_password" placeholder="Zvolte si heslo" required>
            <input type="password" name="user_confirmed_password" placeholder="Potvrdenie heslo" required>
            <input type="submit" name="register" value="Registrovat">
        </form>
        <div class="errors">
            <?php
            if (isset($_SESSION["errors"])) {
                foreach ($_SESSION["errors"] as $error) {
                    echo $error;
                }
                unset($_SESSION["errors"]);
            }
            ?>
        </div>
        <div>
            <button>
                <a href="index.php">Home</a>
            </button>
            <button>
                <a href="login.php">Prihlásenie</a>
            </button>
        </div>
    </div>
</body>

</html>