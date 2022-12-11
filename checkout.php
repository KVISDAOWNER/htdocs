<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="description" content="Betaling GODENUMRE.dk" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="checkout.css" />
    <script src="https://js.stripe.com/v3/"></script>
    <script src="checkout.js" defer></script>
    <link rel="stylesheet" href="styles.css">

  </head>
  <body>
    <div class="wrapper">
      <?php

      require_once 'functions.php';
      h();
      ?>
      <div class="box content content2" style="text-align:center;">


      <?php
      require_once 'functions.php';
      static $telefonnummer;
      static $price;

      if (!isset($_GET['session'])) {
        echo '<h3 id="payment-message-title" class="hidden">Betaling Succesfuld</h3>
        <p id="payment-message-text" class="hidden">Du tages nu videre... Øjeblik :)</p>';
      }
      else if (isset($_GET['session'])) {
        $session = json_decode(base64_decode($_GET['session']));

        $telefonnummer = $session->product;
        $price = $session->product_price;
        $conn = connectToDb();
        $stmt = $conn->prepare('SELECT * FROM godenumre.telefonnummer WHERE name = ? limit 1');
        $stmt->bind_param('s', $telefonnummer); // 's' specifies the variable type => 'string'
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        echo
          '<div class="left-element">
            <h3>Ordre Detaljer</h3>
            <img width=300rem src="telefonnumre-til-salg\\' . $telefonnummer . '.webp">
                      <p >' . strrev(implode(".", str_split(strrev($price), 3))) . ',00 DKK</p>
                      <p> Du modtager en kvittering på mail med SIM-kort nummer. </p>
        </div>';


        echo '<div class="right-element">
        <br>
        <br>
        <br>
        <form id="payment-form">
          <input type="text" id="email" placeholder="Email" />
          <input type="text" id="name" placeholder="Fulde Navn" />
          <input type="text" id="company" placeholder="(Navn på Virksomhed)" />
          <input type="text" id="cvr" placeholder="(CVR-nummer)" />
          <div id="payment-element">
            <!--Stripe.js injects the Payment Element-->
          </div>
          <button id="submit">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Betal Nu</span>
          </button>
        </form>
        <p style="font-size:smaller">Betales igennem <a href="https://stripe.com/en-dk">Stripe</a> </p>
      </div>';
      }
      ?>
    </div>

    <?php
    require_once 'functions.php';
    f();
    ?>
  </body>
</html>