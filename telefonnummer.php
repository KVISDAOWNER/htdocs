<!DOCTYPE html>
<html>
	<head>
	    <script src="https://js.stripe.com/v3/"></script>
	  	<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div class="wrapper">
		<?php
		 	require_once 'functions.php';
		 	h();
			
			echo '<div class="box content content2" style="text-align:center;">';

				$telefonnummer =  $_GET['telefonnummer'];
				$conn = connectToDb();
			    $stmt = $conn->prepare('SELECT * FROM godenumre.telefonnummer WHERE name = ? limit 1');
				$stmt->bind_param('s', $telefonnummer); // 's' specifies the variable type => 'string'
				$stmt ->execute();
				$result = $stmt -> get_result() -> fetch_assoc();
				$price = $result['price'];

				echo 
				'<div class="left-element">
					<img src="telefonnumre-til-salg\\' . $telefonnummer . '.webp">
				</div>';


				$encoded_session = base64_encode(json_encode((object) ['product' => $telefonnummer, 'product_price' => $price]));
				echo '
				<div class="right-element">
					<h2>Guldnummer ' . $_GET['telefonnummer'] . '</h3>
					<p class="grid-product-price">kr. ' . strrev(implode(".", str_split(strrev($price), 3))) . '</p>

					<button id="checkout-button" onclick="location.href=\'checkout.php?session=' . $encoded_session . '\'" type="button">Køb Nu</button>

					<p>
						Ingen binding! Vælg frit teleselskab og abonnement efter køb af dit firmanummer. Du modtager SIM-nr på kvittering direkte efter køb.
						<u><a href="overdragelse.php">Se den hurtige guide for overdragelse her</a></u>.
					</p>	
				</div>';

			echo '</div>';
		    
		 	require_once 'functions.php';
		 	f();
		?>
		</div>
	</body>
</html>
