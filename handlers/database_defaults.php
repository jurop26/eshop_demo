<?php

create_eshop_database();

require_once 'connections/dbh.php';

create_products_table($pdo);
create_shopping_cart_table($pdo);
create_users_table($pdo);
create_admin_table($pdo);
create_company_data_table($pdo);

function create_eshop_database()
{
    try {
        require_once 'connections/dbh_default.php';
        $sql = 'CREATE DATABASE IF NOT EXISTS eshop';
        $pdo->exec($sql);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function create_products_table($pdo)
{
    try {
        $sql = 'CREATE TABLE IF NOT EXISTS products(
            product_id INT AUTO_INCREMENT,
            product_bar_code VARCHAR(10) NULL,
            product_name VARCHAR(100) NULL,
            product_category VARCHAR(100) NULL,
            product_price VARCHAR(10) NULL,
            product_description VARCHAR(1000) NULL,
            product_brand VARCHAR(40) NULL,
            product_stocked VARCHAR(10) NULL,
            product_sales VARCHAR(10) NULL,
            product_image VARCHAR(100) NULL,
            PRIMARY KEY(product_id)
        );';
        $pdo->exec($sql);
        $pdo = null;
    } catch (PDOException $e) {
        die('An error occurred: ' . $e->getMessage());
    }
}

function create_shopping_cart_table($pdo)
{
    try {
        $sql = 'CREATE TABLE IF NOT EXISTS shopping_carts(
            product_id INT AUTO_INCREMENT,
            s_cart_uniqid VARCHAR(50) NULL,
            s_cart_json_data LONGTEXT NULL,
            s_cart_timestamp TIMESTAMP NULL,
            PRIMARY KEY(product_id)
        );';
        $pdo->exec($sql);
        $pdo = null;
    } catch (PDOException $e) {
        die('An error occurred: ' . $e->getMessage());
    }
}

function create_users_table($pdo)
{
    try {
        $sql = 'CREATE TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT,
            user_first_name VARCHAR(50) NULL,
            user_last_name VARCHAR(50) NULL,
            email VARCHAR(50) NULL,
            password VARCHAR(50) NULL,
            user_phone_number INT(15) NULL,
            user_street_address VARCHAR(100) NULL,
            user_house_number VARCHAR(15) NULL,
            user_city VARCHAR(100) NULL,
            user_postal_code INT(15) NULL,
            user_sign_up TIMESTAMP NULL,
            PRIMARY KEY(id)
        );';
        $pdo->exec($sql);
        $pdo = null;
    } catch (PDOException $e) {
        die('An error occurred: ' . $e->getMessage());
    }
}

function create_admin_table($pdo)
{
    try {
        $sql = 'CREATE TABLE IF NOT EXISTS admin(
            id INT AUTO_INCREMENT,
            user_first_name VARCHAR(50) NULL,
            user_last_name VARCHAR(50) NULL,
            email VARCHAR(100) NULL,
            password VARCHAR(50) NULL,
            user_phone_number INT(15) NULL,
            user_street_address VARCHAR(100) NULL,
            user_house_number VARCHAR(15) NULL,
            user_city VARCHAR(100) NULL,
            user_postal_code INT(15) NULL,
            user_sign_up TIMESTAMP NULL,
            PRIMARY KEY(id)
        );';
        $pdo->exec($sql);
        $pdo = null;
    } catch (PDOException $e) {
        die('An error occurred: ' . $e->getMessage());
    }
}

function create_company_data_table($pdo)
{
    try {
        $sql = 'CREATE TABLE IF NOT EXISTS company_data(
            company_name VARCHAR(50) NULL,
            company_ico VARCHAR(15) NULL,
            company_dic VARCHAR(20) NULL,
            company_icdph VARCHAR(20) NULL,
            company_street VARCHAR(100) NULL,
            company_house_number VARCHAR(15) NULL,
            company_city VARCHAR(50) NULL,
            company_postal_code VARCHAR(15) NULL,
            company_phone_number VARCHAR(20) NULL,
            company_mob_number VARCHAR(20) NULL,
            company_bank_name VARCHAR(30) NULL,
            company_bank_account VARCHAR(30) NULL,
            PRIMARY KEY(company_id)
        );';
        $pdo->exec($sql);
        $pdo = null;
    } catch (PDOException $e) {
        die('An error occurred: ' . $e->getMessage());
    }
}
