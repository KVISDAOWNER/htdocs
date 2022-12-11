<?php

require_once('lib/stripe/init.php');
require_once 'functions.php';

// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51LvjtlJZ8RZt5un1XIza8PG3nNwmMn9oKuEPah2Tu9OlzBxwIOHMwnHTcPxq7zhCITNATh2lLTYyCfkK8xoTiJgO00548qDUFk');


header('Content-Type: application/json');

try {
    $conn = connectToDb();

    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr) -> transactionInitiation;
    $now = date_create()->format('Y-m-d H:i:s');

    if (isset($jsonObj->name) && isset($jsonObj->email)) {
        $stmt = $conn->prepare("INSERT INTO godenumre.transaction (client_secret, name, email, company, cvr, product_name, product_price, status, datetime)
        VALUES (?,?,?,?,?,?,?,'in progress',?)");

        $stmt->bind_param('ssssssds',
        $jsonObj->client_secret,
        $jsonObj->name,
        $jsonObj->email,
        $jsonObj->company,
        $jsonObj->cvr,
        $jsonObj->product_name,
        $jsonObj->product_price,
        $now);

        $stmt ->execute();
    }
    else if (isset($jsonObj->product_price)) {
        // Create a PaymentIntent with amount and currency
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $jsonObj->product_price . '00',
            'currency' => 'dkk',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
        $stmt = $conn->prepare("INSERT INTO godenumre.transaction (client_secret, product_name, product_price, status, datetime)
        VALUES (?,?,?, 'waiting',?)");

        $stmt->bind_param('ssds',
        $paymentIntent->client_secret,
        $jsonObj->product_name,
        $jsonObj->product_price,
        $now); // 's' specifies the variable type => 'string'. d => decimal

        $stmt ->execute();

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];

        echo json_encode($output);
    } else{
        throw new Exception("Failed to process transaction. Error occured during checkout.");
    }


} catch(PDOException $e) {
    throw new Exception('Transaction insert failed. Contact support');
}




?>