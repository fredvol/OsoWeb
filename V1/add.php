<!DOCTYPE html>
<html>
<!-- Api v1 -->

<head>
        <meta charset="utf-8" />
        <title>Oso Api V1</title>
</head>
	<body>
	
	<h1>OSO Api V1</h1>
	<p>
		OSO Database API add<br />
	</p>
	<?php
	// exemple url: http://192.168.1.56/oso_web/V1/add.php?u=26fp2112&datept=2016-12-21%2023:25:06&lat=45.4655&long=3.30528&alt=1520&bat=18
	// Connexion database
		try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=oso_test;charset=utf8', 'root', 'mysqlfred', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		echo "<br>".'allParam :';
		if (allParam()) {
			echo 'true '."<br>";
		}else{
			echo 'false '."<br>";
		}
			
		echo "<br>".'allParam are valid:' . allParam()."<br>"."<br>";
		
		if (allParam()) // Check if  there is a sql parameter
			{
				try
				{
					echoParam();
					echo "<br>".'----query-- '."<br>";
					echo "<br>".addParaminquery()."<br>";
					echo "<br>".'------ '."<br>";

					// getting the sql requery
					
					/*$req = $bdd->prepare('INSERT INTO oso_test.position (id, user, datept, latitude, longitude, altitude, battery) VALUES (NULL, :user, :datept, :lat, :long, :alt, :bat');
					//debug_to_console( $req );
					echo "<br>".'------ '."<br>";
					
					$req->bindValue(':user', $_GET['u'], PDO::PARAM_STR);
					$req->bindValue(':datept', $_GET['datept'], PDO::PARAM_STR);
					$req->bindValue(':lat', $_GET['lat'], PDO::PARAM_STR);
					$req->bindValue(':long', $_GET['long'], PDO::PARAM_STR);
					$req->bindValue(':alt', $_GET['alt'], PDO::PARAM_STR);
					$req->bindValue(':bat', $_GET['bat'], PDO::PARAM_STR);*/
					
					
					$req =$bdd->query(addParaminquery());
					/*
					$req->execute(array(
						'user' => $_GET['u'],
						'datept' => $_GET['datept'],
						'lat' => $_GET['lat'],
						'long' => $_GET['long'],
						'alt' => $_GET['alt'],
						'bat'=> $_GET['bat']
						));*/
						
					echo "<br>".'------ '."<br>";
					echo "Number of row affected :" . $req->rowCount();
					echo "<br>".'------ '."<br>";
				
				

					$req->closeCursor(); // Termine le traitement de la requête
				}
				catch(Exception $e)
				{
					die('Erreur : '.$e->getMessage());
				}
			}
			else // sql missing
			{
				echo 'Parameter not valid';
			}
			
	
		function echoParam(){
			echo 'u '.($_GET['u'])."<br>";
			echo 'datept '.($_GET['datept'])."<br>";
			echo 'lat '.($_GET['lat'])."<br>";
			echo 'long '.($_GET['long'])."<br>";
			echo 'alt '.($_GET['alt'])."<br>";
			echo 'bat '.($_GET['bat'])."<br>";				
		}  	
	
		function addParaminquery(){
			$query='INSERT INTO oso_test.position (id, user, datept, latitude, longitude, altitude, battery) VALUES (NULL, \':user\', \':datept\', :lat, :long, :alt, :bat)';
			$assoc=array(

			':user'=> $_GET['u'],
			':datept'=> $_GET['datept'],
			':lat'=> $_GET['lat'],
			':long'=> $_GET['long'],
			':alt'=> $_GET['alt'],
			':bat'=> $_GET['bat']
			);
			$exQuery=str_replace(array_keys($assoc), array_values($assoc), $query);
			//echo $exQuery."<br>";
			return $exQuery;
		}
	
		function allParam(){
			if (isset($_GET['u']) 
				AND isset($_GET['datept'])
				AND isset($_GET['lat'])
				AND isset($_GET['long'])
				AND isset($_GET['alt'])
				AND isset($_GET['bat'])	)
				{
					return true;
				} 
					return false;
		}  
	
	function debug_to_console( $data ) {
		if ( is_array( $data ) )
			$output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
		else
			$output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
		echo $output;
	}
	?>
	</body>
</html>