<?php

//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

// define variables and initialize them with empty values
$email = $streetName = $streetNumber = $city = $zipCode = "";
$valid_fields = 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['email'])) {
        $email = "Please enter your email address";
    } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];
        $valid_fields++;
    } else {
        $email = "Please enter a valid email address";
    }

    if (empty($_POST['street'])) {
        $streetName = "Please enter your street name";
    } else {
        $streetName = $_POST['street'];
        $valid_fields++;
    }

    if (empty($_POST['streetnumber'])) {
        $streetNumber = "Please enter your street number";
    } else if (is_numeric($_POST['streetnumber'])) {
        $streetNumber = $_POST['streetnumber'];
        $valid_fields++;
    } else {
        $streetNumber = "Please enter a valid street number";
    }

    if (empty($_POST['city'])) {
        $city = "Please enter your city";
    } else {
        $city = $_POST['city'];
        $valid_fields++;
    }

    if (empty($_POST['zipcode'])) {
        $zipCode = "Please enter your Zipcode";
    } else if (is_numeric($_POST['zipcode'])) {
        $zipCode = $_POST['zipcode'];
        $valid_fields++;
    } else {
        $zipCode = "Please enter a valid Zipcode";
    }

    if ($valid_fields == 5) {
        $confirmation_msg = "Thank you. Your order has been sent.";
    };
}


    //your products with their price.
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];

    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];

    $totalValue = 0;

    require 'form-view.php';








/* function getEmailAddress() {
    $email = $_POST['email'];
    if (isset($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        } else {
            $email = 'Please enter a valid email address';
            return $email;
        }
    } else {
        $email = "";
        return $email;
    }

} */