<?php
require_once 'handlers/_config_session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <script src="handlers/scripts/admin.js" defer></script>
    <title>Admin Page</title>
</head>

<body>
    <?php include 'components/admin_header.php'; ?>
    <?php include 'components/admin_navbar.php'; ?>

    <hr>
    <div class="main-container">
        <?php
        $layouts = ["Firemné údaje" => "company_default", "Eshop" => "eshop_default", "Pridať nový produkt" => "eshop_add_product"];
        if (isset($_POST["layout"])) {
            $layout = $layouts[$_POST["layout"]];
            include('handlers/' . $layout . '.php');
        } else {
            $layout = "eshop_default";
            include('handlers/' . $layout . '.php');
        }
        ?>
    </div>

</body>

</html>