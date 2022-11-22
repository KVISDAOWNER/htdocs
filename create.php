<?php

require_once('lib/stripe/init.php');
require_once 'functions.php';

// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51LvjtlJZ8RZt5un1XIza8PG3nNwmMn9oKuEPah2Tu9OlzBxwIOHMwnHTcPxq7zhCITNATh2lLTYyCfkK8xoTiJgO00548qDUFk');


header('Content-Type: application/json');


$conn = connectToDb();

// retrieve JSON from POST body
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr) ->transactionInitiation;

// Create a PaymentIntent with amount and currency
$paymentIntent = \Stripe\PaymentIntent::create([
    'amount' => $jsonObj->product_price,
    'currency' => 'dkk',
    'automatic_payment_methods' => [
        'enabled' => true,
    ],
]);

if (!$conn->query("INSERT INTO godenumre.transaction (client_secret, name, email, company, cvr, product_name, product_price, status, datetime)
VALUES ('" . $paymentIntent->client_secret . "','". $jsonObj->name . "','". $jsonObj->email . "','". $jsonObj->company ."','". $jsonObj->cvr . "','". $jsonObj->product_name ."','". $jsonObj->product_price . "','". "waiting" . "','" . date_create()->format('Y-m-d H:i:s') . "')"))
throw new Exception('Transaction insert failed. Contact support');


$output = [
    'clientSecret' => $paymentIntent->client_secret,
];


  echo json_encode($output);
