<?php
require_once 'handlers/_config_session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src='handlers/scripts/script.js' defer></script>
    <title>My Eshop | Login</title>
</head>

<body>
    <?php include('./components/header.php'); ?>
    <div>
        <hr>
    </div>
    <div>
        <h1 style="text-align: center;">PRIHLÁSENIE</h1>
    </div>
    <div class="form-container">
        <form class="form" action="handlers/login_handler.php" method="post">
            <input type="email" name="email" placeholder="Zadajte emailovú adresu" required>
            <input type="password" name="password" placeholder="Zadajte heslo" required>
            <input type="submit" name="submit" value="Prihlásiť">
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
        </form>
        <div>
            <button>
                <a href="index.php">Home</a>
            </button>
            <button>
                <a href="register.php">Registrácia</a>
            </button>
        </div>
    </div>
</body>

</html>