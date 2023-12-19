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
    <title>Admin Eshop</title>
</head>

<body>
    <?php include 'components/admin_header.php'; ?>
    <?php include 'components/admin_navbar.php'; ?>

    <hr>

    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="sort-by-form">
        <div>
            <label for="sort-by-option">Zoradiť podľa: </label>
            <select name="sort-by-option" id="sort-by-option">
                <?php
                $sort_by_values = ["ABC" => "Abecedy", "category" => "Kategórie", "sales" => "Predaja", "stock" => "Stavu na sklade", "price" => "Ceny", "brand" => "Značky"];
                $sort_by_option = "";
                $sort_by_default_value = isset($_POST["sort-by-option"]) ? $_POST["sort-by-option"] : "ABC";

                foreach ($sort_by_values as $key => $sort_by_value) {
                    if ($key === $sort_by_default_value) {
                        $sort_by_option .= '<option value="' . $key . '" selected>' . $sort_by_value . '</option>';
                    } else {
                        $sort_by_option .= '<option value="' . $key . '">' . $sort_by_value . '</option>';
                    }
                }
                echo $sort_by_option;
                ?>
            </select>
        </div>
        <div>
            <label for="sort-by-direction">&uarr;&darr;</label>
            <select name="sort-by-direction" id="sort-by-direction">
                <?php
                $direction_values = ["DESC" => "Zostupne", "ASC" => "Vzostupne"];
                $direction_option = "";
                $direction_default_value = isset($_POST["sort-by-direction"]) ? $_POST["sort-by-direction"] : "DESC";

                foreach ($direction_values as $key => $direction_value) {
                    if ($key === $direction_default_value) {
                        $direction_option .= '<option value="' . $key . '" selected>' . $direction_value . '</option>';
                    } else {
                        $direction_option .= '<option value="' . $key . '">' . $direction_value . '</option>';
                    }
                }
                echo $direction_option;
                ?>
            </select>
        </div>
        <div>
            <label for="rows-in-list-option">Počet produktov: </label>
            <select name="rows-in-list-option" id="rows-in-list-option">
                <?php
                $row_values = ["5", "10", "15", "20", "25", "30"];
                $row_option = "";
                $row_default_value = isset($_POST["rows-in-list-option"]) ? $_POST["rows-in-list-option"] : "20";

                foreach ($row_values as $row_value) {
                    if ($row_value === $row_default_value) {
                        $row_option .= '<option value="' . $row_value . '" selected>' . $row_value . '</option>';
                    } else {
                        $row_option .= '<option value="' . $row_value . '">' . $row_value . '</option>';
                    }
                }
                echo $row_option;
                ?>
            </select>
        </div>
        <input type="hidden" name="layout" value="Eshop">
        <input type="submit" name="submit" value="Filtruj">
    </form>
    <div class="products-list-container">
        <?php
        include_once 'handlers/admin_eshop_handler.php'
        ?>
    </div>
    <div class="add-button-container">
        <button><a href="admin_eshop_add_product.php">Pridať nový produkt</a></button>
    </div>
</body>

</html>