<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="description" content="Betaling GODENUMRE.dk" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="checkout.css" />
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="styles.css">

  </head>
  <body>
    <div class="wrapper">

      <?php
      require_once 'functions.php';
      h();

			echo '<div class="box content content0">';

      $payment_intent_client_secret = $_GET['payment_intent_client_secret'];

      $conn = connectToDb();
      $getStmt = $conn->prepare('SELECT godenumre.transaction.* , godenumre.telefonnummer.sim, godenumre.telefonnummer.teleselskab  FROM godenumre.transaction JOIN godenumre.telefonnummer ON transaction.product_name = telefonnummer.name where status = "in progress" and client_secret = ? LIMIT 1');
      $getStmt->bind_param('s', $payment_intent_client_secret); // 's' specifies the variable type => 'string'
      $getStmt ->execute();
      $transaction_result = $getStmt -> get_result() -> fetch_assoc();


      if(!isset($transaction_result) || strcmp($_GET['redirect_status'],'succeeded') !== 0){
        echo "<h2>Noget gik galt :( Prøv igen eller kontakt support.</h2>";
      }
      else{
        try{
          $product_name = $transaction_result['product_name'];
          $now = date_create()->format('Y-m-d H:i:s');
          $insertStmt = $conn->prepare("INSERT INTO godenumre.transaction (client_secret, name, email, company, cvr, product_name, product_price, status, datetime)
          VALUES (?,?,?,?,?,?,?,'success',?)");
          $insertStmt->bind_param('ssssssds',
          $transaction_result['client_secret'],
          $transaction_result['name'],
          $transaction_result['email'],
          $transaction_result['company'],
          $transaction_result['cvr'],
          $transaction_result['product_name'],
          $transaction_result['product_price'],
          $now);
          $insertStmt ->execute();
        }
        catch(Exception){

        }


        $updateStmt = $conn->prepare('UPDATE godenumre.telefonnummer SET telefonnummer.sold = 1  where telefonnummer.name = ?;');
        $updateStmt->bind_param('s', $product_name); // 's' specifies the variable type => 'string'
        $updateStmt ->execute();

        echo '<h2>Tak for købet! </h2>';
        echo '<p>Faktura og SIM-kort nummer er også tilsendt til ' . $transaction_result['email']. ' :)</p>';
        echo '<p>Du skal nu vælge det teleselskab og abonnement du ønsker og oplyse dem om overdragelsen. <a href="overdragelse.php" title="Overdragelse">Se den hurtige guide til overdragelse <strong>her</strong>.</a></p>';
        echo '<br>';

        echo '<h3>Faktura Nr. #1039</h3>';
        echo '<pre>Dato: ' . date_create()->format('d/m/Y') . '</pre>';
        echo '<br>';
        echo '<h4>Telefonnummer: ' . $transaction_result['product_name'] . '</h4>';
        echo '<pre>Teleselskab: ' . $transaction_result['teleselskab'] . '</pre>';
        echo '<pre>SIM-kort nummer: ' . $transaction_result['sim'] . '</pre>';
        echo '<br>';
        echo '<p>Subtotal: <b>' . strrev(implode(".", str_split(strrev($transaction_result['product_price'] + 0 ), 3))) . ' kr</b></p>';
        echo '<p>Moms: <b>' . strrev(implode(".", str_split(strrev(0 ), 3))) . ' kr</b></p>';
        echo '<br>';
        echo '<p>I alt: <b style="font-size:24px;">' .  strrev(implode(".", str_split(strrev($transaction_result['product_price'] +  0), 3))) . ' kr</b></p>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<p style="font-size:20px;"><b>Kundeinformation</b></p>';
        echo '<p>att.: '. $transaction_result['name'] .'</p>';
        echo '<p>'. $transaction_result['company'] .'</p>';
        echo '<p>CVR: '. $transaction_result['cvr'] .'</p>';
        echo '<p>'. $transaction_result['email'] .'</p>';


        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<p><em>OBS: Overdragelse fra CBB til CBB kræver en underskrevet ejeroverdragelse. Men overdragelse fra CBB til ethvert andet teleselskab kræver blot at oplyse om SIM-kort nummer.</em></p>';
        echo '<br>';
        echo '<p><b>Vær opmærksom på at overdragelsen skal ske senest 40 dage efter køb, så overdrag hurtigst muligt.</b></p>';
      }

      echo '</div>';
      f();
      ?>
  </body>
</html>