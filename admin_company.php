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
    <title>Admin Company</title>
</head>

<body>
    <?php include_once 'components/admin_header.php'; ?>
    <?php include_once 'components/admin_navbar.php'; ?>

    <hr>
    <div class="main-container">
        <?php
        include_once 'handlers/admin_company_data.php'
        ?>
    </div>

</body>

</html>