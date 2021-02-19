<?php

//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
//$_SESSION['streetName'] = $_SESSION['streetNumber'] = $_SESSION['city'] = $_SESSION['zipCode'] = "";
$email = "";
$valid_fields = 0;
$confirmation_msg = "";
$delivery_time = date("H:i:s", strtotime("+2 Hours"));
$price = 0;

//if (!isset($_SESSION['streetName'], $_SESSION['streetNumber'], $_SESSION['city'], $_SESSION['zipCode'])) {
//    $_SESSION['streetName'] = $_SESSION['streetNumber'] = $_SESSION['city'] = $_SESSION['zipCode'] = "";
//}

if (isset($_COOKIE['totalValue'])) {
    $totalValue = intval($_COOKIE['totalValue']);
} else {
    $totalValue = 0;
    setcookie('totalValue', $totalValue, time() + (86400 * 30));
}
//

// don't initialize empty session variables, otherwise the information is not contained
// $_SESSION['streetName'] = $_SESSION['streetNumber'] = $_SESSION['city'] = $_SESSION['zipCode'] = "";

//your products with their price.
$products_food = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

$products_drinks = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];

// make the food items the default when loading the page
$products = $products_food;

if (isset($_GET['food'])) {
    if ($_GET['food'] == 0) {   // when order drinks is clicked, show the drinks items. Otherwise show the food items
        $products = $products_drinks;
    } else {
        $products = $products_food;
    }
}

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
        $_SESSION['streetName'] = "Please enter your street name";
    } else {
        $_SESSION['streetName'] = $_POST['street'];
        $valid_fields++;
    }

    if (empty($_POST['streetnumber'])) {
        $_SESSION['streetNumber'] = "Please enter your street number";
    } else if (is_numeric($_POST['streetnumber'])) {
        $_SESSION['streetNumber'] = $_POST['streetnumber'];
        $valid_fields++;
    } else {
        $_SESSION['streetNumber'] = "Please enter a valid street number";
    }

    if (empty($_POST['city'])) {
        $_SESSION['city'] = "Please enter your city";
    } else {
        $_SESSION['city'] = $_POST['city'];
        $valid_fields++;
    }

    if (empty($_POST['zipcode'])) {
        $_SESSION['zipCode'] = "Please enter your Zipcode";
    } else if (is_numeric($_POST['zipcode'])) {
        $_SESSION['zipCode'] = $_POST['zipcode'];
        $valid_fields++;
    } else {
        $_SESSION['zipCode'] = "Please enter a valid Zipcode";
    }

    // retrieve ordered products via checkboxes & calculate price
    /*foreach ($products AS $i => $product) {
        if (isset($_POST['products'][$i])) {
            $value = $_POST['products'][$i];
            if ($value == 1) {
                $price += $product['price'];
            }
        }
    }*/

    // retrieve (number of) ordered products via input fields & calculate price
    foreach ($products AS $i => $product) {
        if (!empty($_POST['products'][$i])) {
            $numberOfItems = $_POST['products'][$i];
            $price += ($numberOfItems * $product['price']);
        }
    }

    if (isset($_POST['express_delivery'])) {
        $delivery_time = date("H:i:s", strtotime("+45 Minutes"));
        $price += 5;
    }

    //$totalValue = intval($_COOKIE['totalValue']);
    $totalValue += $price;
    $totalValue = strval($totalValue);
    $_COOKIE['totalValue'] = $totalValue;
    setcookie('totalValue', $totalValue, time() + (86400 * 30));


    if ($valid_fields == 5) {
        $confirmation_msg = "Thank you for your order. The delivery time is " . $delivery_time . ". The price is " . $price . "€.";
    } else {
        $confirmation_msg = "Please fill in all the fields.";
    }

}

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