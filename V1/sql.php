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
		OSO Database API SQL<br />
	</p>
	<?php
        // exmple : http://192.168.1.56/oso_web/V1/sql.php?key=osopass&sql=INSERT%20INTO%20`oso_test`.`position`%20(`id`,%20`user`,%20`datept`,%20`latitude`,%20`longitude`,%20`altitude`,%20`battery`)%20VALUES%20(NULL,%20%2725fp2112%27,%20%272016-12-21%2023:01:06%27,%20%2745.4225%27,%20%273.30178%27,%20%271520%27,%20%2760%27);
        // INSERT INTO `oso_test`.`position` (`id`, `user`, `datept`, `latitude`, `longitude`, `altitude`, `battery`) VALUES (NULL, '25fp2112', '2016-12-21 23:01:06', '45.4225', '3.30178', '1520', '60');

	// Connexion database
		try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=oso_test;charset=utf8', 'root', 'mysqlfred', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		
		echo "<br>".'key :' . $_GET['key'] . ': ' .sha1($_GET['key'])."<br>";
		
		if (isset($_GET['sql']) AND isset($_GET['key'])) // Check if  there is a sql parameter
			{


				if (check_auth($_GET['key'])) {
					echo 'Le mot de passe est valide !'."<br>";
					echo 'sql :' . $_GET['sql'] . ' !'."<br>";
					
					// getting the sql requery
					$reponse = $bdd->query($_GET['sql']);
					//INSERT INTO `oso_test`.`position` (`id`, `user`, `datept`, `latitude`, `longitude`, `altitude`, `battery`) VALUES (NULL, '25fp2112', '2016-12-21 22:01:06', '45.4625', '3.30478', '1520', '60');
					echo "<br>".'-----------'."<br>";
					echo 'Sql executed!';
					echo "<br>".'-----------'."<br>";
					var_export($reponse);
					debug_to_console($_GET['sql']);
					// On affiche chaque entrée une à une
					
					$reponse->closeCursor(); // Termine le traitement de la requête
				
				} else {
					echo 'Wrong password';
				}
				
				
			}
			else // sql missing
			{
				echo 'No sql set';
			}
			
			
			
			
	function check_auth($pass)
	{
            //osopass
		$hash = '7f77f2e50c992b2f223c00cb24f5faa72b72861e';
		if (sha1($pass) == $hash)
		{   // Login/password is correct.
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