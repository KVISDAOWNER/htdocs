<!DOCTYPE html>
<html>
	<head>
	  	<link rel="stylesheet" href="styles.css">
	  	<!-- TrustBox script -->
		<script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
		<!-- End TrustBox script -->
	</head>
	<body>
		<div class="wrapper">
			<?php
			 	require_once 'functions.php';
			 	h();
			?>

			<div class="box sidebar1"></div>
			<div class="box sidebar2"></div>

			<div class="box content center">
				<div class="left-element">
					<?php
						require_once 'functions.php';
						$conn = connectToDb();
						
						$result = $conn->query('SELECT * FROM godenumre.telefonnummer WHERE sold = 0 ORDER BY price desc limit 1') -> fetch_assoc();
						
						$files = array_diff(scandir('telefonnumre-til-salg'), array('..', '.'));
						    $telefonnummer = $result['name'];
						    $price = $result['price'];
						    echo
						    '<div class="bigproduct">
								<a href="telefonnummer.php?telefonnummer=' . $telefonnummer . '">
									<img width=600rem src="telefonnumre-til-salg\\' . $telefonnummer . '.webp">
							    	<p class="grid-product-title">'. str_replace('-', ' ', $telefonnummer) . '</p>
							    	<p class="grid-product-price"> kr. '. strrev(implode(".", str_split(strrev($price), 3))) . '</p>
								</a>
						    </div>';
					?>
				</div>
				<div style="max-width: 800px; margin: auto;" class="right-element">
              		<br>
              		<br>
              		<h4>Overdragelse</h4>
          			<p>
          				Der er <strong>ingen binding</strong> ved godenumre.dk. Du kan <span style="text-decoration:underline">helt frit vælge teleselskab og abonnement</span> efter køb. </p>
          			<p>
          				<a href="overdragelse.php" title="Overdragelse" aria-describedby="a11y-external-message">Læs guiden for overdragelse af dit guldnummer <strong>her</strong>.
          				</a>
          			</p>
              </div>
			</div>
			<div class="box content center content1">
				<h3>NOGLE AF VORES FANTASTISKE KUNDER</h3>
				
				<a href="https://www.dhkbyg.dk">
					<img style="margin: -80px 1em 0 1em;  position: relative; transform: translateY(50%);" width=160px src="kunder\www.dhkbyg.dk.avif">
				</a>
				<a href="https://www.vikingsushi.dk">
					<img style="margin: -80px 1em 0 1em;  position: relative; transform: translateY(50%);" width=160px src="kunder\\www.vikingsushi.dk.avif">
				</a>
				<a href="https://www.ints.dk">
					<img style="margin: -80px 1em 0 1em;  position: relative; transform: translateY(50%);" width=160px src="kunder\www.ints.dk.avif">
				</a>
				<a href="https://www.jojo.town">
					<img style="margin: -80px 1em 0 1em;  position: relative; transform: translateY(50%);" width=160px src="kunder\www.jojo.town.avif">
				</a>
				<a href="https://www.belowtheline.dk">
					<img style="margin: -80px 1em 0 1em;  position: relative; transform: translateY(50%);" width=160px src="kunder\www.belowtheline.dk.avif">
				</a>
          		<br>
          		<br>
          		<br>
          		<br>
          		<br>

				<!-- TrustBox widget - Micro Review Count -->
				<div class="trustpilot-widget" data-locale="da-DK" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="608837d438f35b0001091e41" data-style-height="24px" data-style-width="100%" data-theme="light" data-min-review-count="10" data-style-alignment="center">
				  <a href="https://dk.trustpilot.com/review/godenumre.dk" target="_blank" rel="noopener">Trustpilot</a>
				</div>
				<!-- End TrustBox widget -->
			</div>

			<div class="box content center content2">
				<h3>UDVALGTE GULDNUMRE</h3>
				<div style="text-align:center;">
					<?php
						require_once 'functions.php';
						// Create connection
						$conn = connectToDb();

						$results = $conn->query('SELECT * FROM godenumre.telefonnummer WHERE sold = 0 ORDER BY createdAt desc limit 4');

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
			<div class="box content center content3">

				<?php
					require_once 'functions.php';
					$conn = connectToDb();

					$results = $conn->query('SELECT * FROM godenumre.telefonnummer WHERE sold = 0 ORDER BY price ASC limit 4');

					echo
					'<h3>GULDNUMRE FRA '. strrev(implode(".", str_split(strrev(mysqli_fetch_array($results)["price"]), 3))) .',-</h3>
					<div style="text-align:center;">';

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
					echo '</div>';
				?>

					<a class='button'  href="alle-guldnumre.php">SE&nbsp;ALLE</a>

			</div>
			<div style="max-width: 800px;" class="box content center content4">
  				<h4 >Hvad gavner et unikt guldnummer?</h4>
              	<p style="font-size: .9375em;">Et godt guldnummer signalerer professionalisme og er med til at tilføje troværdighed til dit brand. Brug det til at styrke dine salgskanaler og din kundeservice. godenumre.dk har guldnumre til alle virksomheder. Håndværkervirksomheder og lignende kan få særlig gavn af at markedsføre med let genkendeligt guldnummer som firmanummer på firmabil. Desuden kan IT Startups og andre nystartede virksomheder få særlig gavn af at få et unikt guldnummer til at signalere et professionelt og troværdigt brand. Køb dit nye guldnummer ved godenumre.dk og vi garanterer at du kan starte overdragelsen af dit nye nummer allerede i dag!</p>
			</div>

			<?php
			 	require_once 'functions.php';
			 	f();
			?>
		</div>
	</body>
</html>
