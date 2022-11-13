<?php

	function h(){
		echo "<div class='box header'>
				<div class='logo'>
					<a href='index.php'>
						<h1>GODENUMRE.DK</h1>
					</a>
				</div>
				
				<nav>
					<a href='alle-guldnumre.php'>Alle Guldnumre</a>
					<a href='overdragelse.php'>Overdragelse</a>
					<a href='om-os.php'>Om Os</a>
				</nav>
			</div>";
	}

	function f(){
		echo "<div style='padding: 2% 10% 4% 10%; text-align: center;' class='box footer'>
				<div style='display: inline-block; vertical-align:top; width: 31%;'>
					<p style='font-weight: bolder;'>Vi garanterer</p>
					<p>✓ Hurtig online levering og nem overdragelse!</p>
					<p>✓ God kundeservice</p>
				</div>
				<div style='display: inline-block; width: 31%;'>
				</div>
				<div style='display: inline-block; vertical-align:top; width: 31%;'>
					<p style='font-weight: bolder;'>Kontakt</p>
					<p>CVR-nr. 41243848</p>
					<p>Aalborg, Visionsvej 11, 1, -3</p>
					<p>(+45) 222-111-30</p>
					<p>info@godenumre.dk</p>
					<a>Cookie- og privatlivspolitik</a>
				</div>
			</div>";
	}

	function connectToDb(){
		$conn = new mysqli("127.0.0.1", "root", "");

		// Check connection
		if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
		  throw new ErrorException("Unable to connect to database");
		}

		$conn->set_charset('utf8mb4'); // charset

		return $conn;
	}


?>