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
$email = "";
$confirmation_msg = "";
$delivery_time = date("H:i:s", strtotime("+2 Hours"));
$price = 0;
$invalidEmail = $invalidStreetName = $invalidStreetNumber = $invalidCity = $invalidZipCode = "";
$alert_class = "";

if (!isset($_SESSION['streetName']) && !isset($_SESSION['streetNumber']) && !isset($_SESSION['city']) &&
            !isset($_SESSION['zipCode']) && !isset($_SESSION['email'])) {
    $_SESSION['streetName'] = $_SESSION['streetNumber'] = $_SESSION['city'] = $_SESSION['zipCode'] = $_SESSION['email'] = null;
}

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
if (isset($_GET['food'])) {
    if ($_GET['food'] == 0) {   // when order drinks is clicked, show the drinks items. Otherwise show the food items
        $products = $products_drinks;
    } else {
        $products = $products_food;
    }
} else {
    $products = $products_food;
}

function validateFields(): bool {
    if ( (!empty($_POST['email'])) && (isset($_SESSION['streetName'])) && (isset($_SESSION['streetNumber'])) && (isset($_SESSION['city'])) &&
        (isset($_SESSION['zipCode']))) {
            return true;
    } else {
        return false;
    }
}

//if ($_SERVER["REQUEST_METHOD"] == "POST") {

function validateOrder() {
    if (empty($_POST['email'])) {
        $invalidEmail = "Please enter your email address";
    } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email'] = $_POST['email'];
    } else {
        $_POST['email'] = null;
        $invalidEmail = "Please enter a valid email address";
    }

    if (empty($_POST['street'])) {
        $invalidStreetName = "Please enter your street name";
    } else {
        $_SESSION['streetName'] = $_POST['street'];
    }

    if (empty($_POST['streetnumber'])) {
        $invalidStreetNumber = "Please enter your street number";
    } else if (is_numeric($_POST['streetnumber'])) {
        $_SESSION['streetNumber'] = $_POST['streetnumber'];
    } else {
        $invalidStreetNumber = "Please enter a valid street number";
    }

    if (empty($_POST['city'])) {
        $invalidCity = "Please enter your city";
    } else {
        $_SESSION['city'] = $_POST['city'];
    }

    if (empty($_POST['zipcode'])) {
        $invalidZipCode = "Please enter your Zipcode";
    } else if (is_numeric($_POST['zipcode'])) {
        $_SESSION['zipCode'] = $_POST['zipcode'];
    } else {
        $invalidZipCode = "Please enter a valid Zipcode";
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
            $_SESSION['price'] += ($numberOfItems * $product['price']);
        }
    }

    if (isset($_POST['express_delivery'])) {
        $delivery_time = date("H:i:s", strtotime("+45 Minutes"));
        $_SESSION['price'] += 5;
    }
}

if (!isset($_GET['food'])) {
    $products = $products_food;
    validateOrder();
} else if (isset($_GET['food'])) {
    validateOrder();
    if ($_GET['food'] == 0) {   // when order drinks is clicked, show the drinks items. Otherwise show the food items
        $products = $products_drinks;
        validateOrder();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (validateFields()) {
        $totalValue += $_SESSION['price'];
        $totalValue = strval($totalValue);
        $_COOKIE['totalValue'] = $totalValue;
        setcookie('totalValue', $totalValue, time() + (86400 * 30));
        $alert_class = "success";
        $confirmation_msg = "Thank you for your order. The delivery time is " . $delivery_time . ". The price is " . $_SESSION['price'] . "€.";
    } else {
        $alert_class = "danger";
        $confirmation_msg = "Please fill in all the fields.";
    }
} else {
    if (isset($_GET['food'])) {
        if ($_GET['food'] == 0) {   // when order drinks is clicked, show the drinks items. Otherwise show the food items
            $products = $products_drinks;
            validateOrder();
        }
    } else {
        if (validateFields()) {
            $totalValue += $_SESSION['price'];
            $totalValue = strval($totalValue);
            $_COOKIE['totalValue'] = $totalValue;
            setcookie('totalValue', $totalValue, time() + (86400 * 30));
            $alert_class = "success";
            $confirmation_msg = "Thank you for your order. The delivery time is " . $delivery_time . ". The price is " . $_SESSION['price'] . "€.";
        } else {
            $alert_class = "danger";
            $confirmation_msg = "Please fill in all the fields.";
        }
    }
}
//}

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