<?php

require_once 'connections/dbh.php';

$result = get_all_categories($pdo);

if (is_no_category($result)) {
    echo "Databáza neobsahuje žiadne kategórie produktov";
} else {

    $categories = [];
    $category_buttons = null;

    foreach ($result as $category) {
        array_push($categories, $category);
    }

    $categories = array_unique($categories);

    foreach ($categories as $category) {
        if (isset($_POST["category-button"]) && $_POST["category-button"] === $category) {
            $category_buttons .= '<input type="submit" class="category-selected" name="category-button" value="' . $category . '">';
        } else {
            $category_buttons .= '<input type="submit" name="category-button" value="' . $category . '">';
        }
    }

    echo '<form method="post" action="home">' . $category_buttons . '</form>';
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
    try {
        $sql = "SELECT product_category FROM products";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $pdo = null;
        $stmt = null;

        return $result;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
