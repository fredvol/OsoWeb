<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Oso Website</title>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
		<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    </head>
    <body>
	<?php include("class/classPosition.php"); ?>
	<!-- adresse test  : http://192.168.1.56/oso_web/?u=25fp2012 -->

		
		<?php
		try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=oso_test;charset=utf8', 'root', 'mysqlfred', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		?>
		
		<!-- Le corps -->
		<div id="corps">
			<h1>OSO</h1>
			<p>
				OSO web site draft !<br />
			</p>
			<?php
			if (isset($_GET['u'])) // Check user parameter is set in the URL
			{
				echo 'Track for :' . htmlspecialchars($_GET['u']) . ' !'."<br>";
					
					// On récupère tout le contenu de la table position pour un user
					$req = $bdd->prepare('SELECT * FROM position  WHERE user= :user ORDER BY `position`.`datept` DESC');
					$req->execute(array('user' => $_GET['u']));
					//echo "<br>".'-----------'."<br>";
					
					// create track 
					$track = array();
					// On affiche chaque entrée une à une
						while ($donnees = $req->fetch())
						{
							array_push($track, new Position($donnees['id'], $donnees['user'], $donnees['datept'], $donnees['latitude'], $donnees['longitude'], $donnees['altitude'], $donnees['battery']));
							//echo 'ID :' . htmlspecialchars($donnees['id']) . ' user :'.$donnees['user'].' !'."<br>";
						}
						$req->closeCursor(); // Termine le traitement de la requête
			}
			else // Il manque des paramètres, on avertit le visiteur
			{
				echo 'No user set';
			}
			
			echo "<br>".'-----array:-----'."<br>";
			
			foreach ($track as $item) {
				echo $item->displayPosition() ;
				
			}

			echo "<br>".'-----MAP:-----'."<br>";
			?>
			
			 <div id="macarte" style="width:800px; height:800px"></div>
		</div>
		<?php echo "netbeamOsoWeb"; ?>;
		<!-- Map -->
		<script type="text/javascript">
			 // import array from Php
                        var jTrackArray= <?php echo json_encode($track ); ?>;
                        
                        //Prepare map
                        var carte = L.map('macarte').setView([jTrackArray[0]._lat, jTrackArray[0]._long], 10);
			
			L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(carte);
		
			// Add points
			for(var i=0;i<jTrackArray.length;i++){
				//alert(jArray[i]._lat);
				var marker = L.marker([jTrackArray[i]._lat, jTrackArray[i]._long]).addTo(carte);
				  marker.bindPopup(''); // Je ne met pas de texte par défaut
				  var mapopup = marker.getPopup();
				  //mapopup.setContent(string.concat(jArray[i]._id," user:",jArray[i]._user, " Bat:",jArray[i]._bat ));
				  mapopup.setContent(jTrackArray[i]._datept);
				 // marker.openPopup();
			}
                        
                        //Draw line
                        var arrayPointLatLong=new Array();
                        for(var i=0;i<jTrackArray.length;i++){
				//alert(jArray[i]._lat);
				console.log("traitement" ,i ,jTrackArray[i]._datept);
				  arrayPointLatLong.push([jTrackArray[i]._lat, jTrackArray[i]._long]);
			}
                        var eskimon = L.polyline(arrayPointLatLong, {color: 'red'}).addTo(carte);
		 </script>

		<!-- Foot Page -->
		<?php include("footpage.php"); ?>
    </body>
</html>
	  

