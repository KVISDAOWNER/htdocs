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
			<div class="box content content1">
				<div style="text-align:center;">
					<?php 

						// Create connection
						$conn = new mysqli("127.0.0.1", "root", "");

						// Check connection
						if ($conn->connect_error) {
						  die("Connection failed: " . $conn->connect_error);
						  throw new ErrorException("Unable to connect to database");
						}

						$results = $conn->query('SELECT * FROM godenumre.telefonnummer WHERE sold = 0 ORDER BY createdAt desc');

						$files = array_diff(scandir('telefonnumre-til-salg'), array('..', '.'));
						foreach ($results as $result) {
						    $telefonnummer = $result['name'];
						    $price = $result['price'];
						    echo 
						    '<div class="product">
								<a href="telefonnummer.php?telefonnummer=' . $telefonnummer . '">
									<img width=300rem src="telefonnumre-til-salg\\' . $telefonnummer . '.webp">
							    	<p class="grid-product-title">'. str_replace('-', ' ', $telefonnummer) . '</p>
							    	<p class="grid-product-price"> kr. '. strrev(implode(".", str_split(strrev($price), 3))) . '</p>
								</a>
						    </div>';
						}
					?>
				</div>
			</div>
			<?php
			 	require_once 'functions.php';
			 	f();
			?>
		</div>
	</body>
</html>
