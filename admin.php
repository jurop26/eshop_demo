<?php
require_once 'handlers/_config_session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">

    <title>Admin | Login</title>
</head>

<body>
    <div class="form-container">
        <form class="form" action="handlers/login_handler.php" method="post">
            <h1 style="text-align: center;">ADMINISTRÁTOR - PRIHLÁSENIE</h1>
            <input type="email" name="email" placeholder="Zadajte emailovú adresu" required>
            <input type="password" name="password" placeholder="Zadajte heslo" required>
            <span>
                <input type="submit" name="submit" value="Prihlásiť">
                <a href="index.php">Home page</a>
            </span>
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
    </div>
    </div>
</body>

</html>