<!DOCTYPE html>
<html>
	<head>
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
						
						$telefonnummer =  $_GET['telefonnummer'];
						$conn = connectToDb();
					    $stmt = $conn->prepare('SELECT * FROM godenumre.telefonnummer WHERE name = ? limit 1');
						$stmt->bind_param('s', $telefonnummer); // 's' specifies the variable type => 'string'
						$stmt ->execute();
						$result = $stmt -> get_result() -> fetch_assoc();

						echo 
						'<div class="left-element">
							<img src="telefonnumre-til-salg\\' . $telefonnummer . '.webp">
						</div>';

						echo '
						<div class="right-element">
							<h2>Guldnummer ' . $_GET['telefonnummer'] . '</h3>
							<p class="grid-product-price">kr. ' . strrev(implode(".", str_split(strrev($result['price']), 3))) . '</p>
							<button type="submit">Køb Telefonnummer</button>
							<p>
								Ingen binding! Vælg frit teleselskab og abonnement efter køb af dit firmanummer. Du modtager SIM-nr på kvittering direkte efter køb.
								<u><a href="overdragelse.php">Se den hurtige guide for overdragelse her</a></u>.
							</p>	
						</div>'
					?>
				

				
			</div>

			<?php
			 	require_once 'functions.php';
			 	f();
			?>
		</div>
	</body>
</html>
