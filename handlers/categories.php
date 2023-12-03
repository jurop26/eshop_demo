<?php

try {
    require_once 'connections/dbh.php';

    // ERROR HANDLER
    $errors = [];
    $result = get_all_categories($pdo);

    if (is_no_category($result)) {
        $errors["error_category"] = "Databáza neobsahuje žiadne kategórie produktov";
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        header("Location:" . $_SERVER["HTTP_REFERER"]);
        die();
    }

    $categories = [];
    $category_buttons = null;

    foreach ($result as $category) {
        array_push($categories, $category);
    }

    $categories = array_unique($categories);

    foreach ($categories as $category) {
        $category_buttons .= '<input type="submit" name="category-button" value="' . $category . '">';
    }

    echo '<form method="post" action="home">' . $category_buttons . '</form>';
} catch (PDOException $e) {
    die($e->getMessage());
}

function is_no_category($result)
{
    if (!$result) {
        return true;
    } else {
        false;
    }
}

function get_all_categories($pdo)
{
    $sql = "SELECT product_category FROM products";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $pdo = null;
    $stmt = null;

    return $result;
}
