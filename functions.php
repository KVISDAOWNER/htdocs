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


	function create_product_image(string $product_name)
	{
		if(file_exists('telefonnumre-til-salg/'. $product_name . '.jpg')){
			return;
		}
		// Load And Create Image From Source
		$jpg_image = imagecreatefromjpeg('telefonnumre-til-salg/blank.jpg');

		// Allocate A Color For The Text
		$color = imagecolorallocate($jpg_image, 255, 255, 255);

		// Set Path to Font File
		$font_path = 'Segoe Mono Bold.ttf';

		// Set Text to Be Printed On Image
		if (strlen($product_name) == 11) {
			$text1 = str_split($product_name)[0] . str_split($product_name)[1];
			$text2 = str_split($product_name)[3] . str_split($product_name)[4];
			$text3 = str_split($product_name)[6] . str_split($product_name)[7];
			$text4 = str_split($product_name)[9] . str_split($product_name)[10];

			// Print Text On Image
			imagettftext($jpg_image, 53, 0, 160, 268, $color, $font_path, $text1);
			imagettftext($jpg_image, 53, 0, 264, 268, $color, $font_path, $text2);
			imagettftext($jpg_image, 53, 0, 368, 268, $color, $font_path, $text3);
			imagettftext($jpg_image, 53, 0, 472, 268, $color, $font_path, $text4);
		} else if (str_split($product_name)[3] == '-' && str_split($product_name)[7]== '-') {
			// Set Text to Be Printed On Image
			$text1 = str_split($product_name)[0] . str_split($product_name)[1] . str_split($product_name)[2];
			$text2 = str_split($product_name)[4] . str_split($product_name)[5] . str_split($product_name)[6];
			$text3 = str_split($product_name)[8] . str_split($product_name)[9];

			// Print Text On Image
			imagettftext($jpg_image, 53, 0, 170, 268, $color, $font_path, $text1);
			imagettftext($jpg_image, 53, 0, 315, 268, $color, $font_path, $text2);
			imagettftext($jpg_image, 53, 0, 461, 268, $color, $font_path, $text3);
		} else if (str_split($product_name)[2] == '-' && str_split($product_name)[7] == '-') {
			// Set Text to Be Printed On Image
			$text1 = str_split($product_name)[0] . str_split($product_name)[1];
			$text2 = str_split($product_name)[3] . str_split($product_name)[4] . str_split($product_name)[5] . str_split($product_name)[6];
			$text3 = str_split($product_name)[8] . str_split($product_name)[9];

			// Print Text On Image
			imagettftext($jpg_image, 53, 0, 170, 268, $color, $font_path, $text1);
			imagettftext($jpg_image, 53, 0, 273, 268, $color, $font_path, $text2);
			imagettftext($jpg_image, 53, 0, 462, 268, $color, $font_path, $text3);
		} else if (str_split($product_name)[2] == '-' && str_split($product_name)[6] == '-') {
			// Set Text to Be Printed On Image
			$text1 = str_split($product_name)[0] . str_split($product_name)[1];
			$text2 = str_split($product_name)[3] . str_split($product_name)[4] . str_split($product_name)[5];
			$text3 = str_split($product_name)[7] . str_split($product_name)[8] . str_split($product_name)[9];

			// Print Text On Image
			imagettftext($jpg_image, 53, 0, 170, 268, $color, $font_path, $text1);
			imagettftext($jpg_image, 53, 0, 274, 268, $color, $font_path, $text2);
			imagettftext($jpg_image, 53, 0, 420, 268, $color, $font_path, $text3);
		}
		else if (str_split($product_name)[2] == '-' && str_split($product_name)[5] == '-') {
			// Set Text to Be Printed On Image
			$text1 = str_split($product_name)[0] . str_split($product_name)[1];
			$text2 = str_split($product_name)[3] . str_split($product_name)[4];
			$text3 = str_split($product_name)[6] . str_split($product_name)[7] . str_split($product_name)[8] . str_split($product_name)[9];

			// Print Text On Image
			imagettftext($jpg_image, 53, 0, 170, 268, $color, $font_path, $text1);
			imagettftext($jpg_image, 53, 0, 273, 268, $color, $font_path, $text2);
			imagettftext($jpg_image, 53, 0, 380, 268, $color, $font_path, $text3);
		}
		imagejpeg($jpg_image, 'telefonnumre-til-salg/' . $product_name . '.jpg');

		// Clear Memory
		imagedestroy($jpg_image);
	}
?>