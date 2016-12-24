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
                   
                    <!-- User id text field -->
                    <input name="searchTxt" type="text" maxlength="512" id="searchTxt" class="searchField" />
                    <button onclick="location.href = 'www.yoursite.com';" id="ButtonFollowId" class="float-left submit-button" >Follow</button>
                    
                    <script type="text/javascript">
                        var input = document.getElementById("searchTxt");   
                        console.log(input);
                       document.getElementById("ButtonFollowId").onclick = function () {
                           location.href = "?u="+ input.value;
                       };
                    </script>
                    <br>
                    
                    <!-- PHP Part -->
                    <?php
                    $user=NULL;
                    if (isset($_GET['u'])) {
                       $user=$_GET['u'];
                    } 
                    
                    
                    if (isset($user)) // Check user parameter is set in the URL
                    {
                            echo 'Track for : <b>' . htmlspecialchars($user) . "</b><br>";

                                    // On récupère tout le contenu de la table position pour un user
                                    $req = $bdd->prepare('SELECT * FROM position  WHERE user= :user ORDER BY `position`.`datept` DESC');
                                    $req->execute(array('user' => $user));
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
                                            echo " Number of points: ".count($track)."<br>";
                                             if (count($track)==0){
                                                 echo '<span style="color: red;text-align:center;">No points found for this user id (or user id not valid )!</span>  ';
                                             }
                                            
                                            
                                           // echo "<br>".'-----Last Point details:-----'."<br>";
                                           // echo $track[0]->displayNicelyPosition();
                                           // echo "<br>".'-----All Points details:-----'."<br>";
                                            
                                            //foreach ($track as $item) {
                                             //   echo $item->displayPosition() ;
                                            //}
                    }
                    else // Il manque des paramètres, on avertit le visiteur
                    {
                            echo 'No user set';
                    }


                    echo "<br>".'-----MAP:-----'."<br>";
                    ?>

                     <div id="macarte" style="width:70vw; height:400px"></div>
            </div>

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
                              mapopup.setContent(DisplayPositionInComment(jTrackArray[i]));
                             // mapopup.setContent('Salut, ça zeste ?'+jTrackArray[i]._datept);
                             // marker.openPopup();
                             if (i==0){
                                 marker.openPopup();
                             }
                    }

                    //Draw line
                    var arrayPointLatLong=new Array();
                    for(var i=0;i<jTrackArray.length;i++){
                            //alert(jArray[i]._lat);
                            console.log("traitement" ,i ,jTrackArray[i]._datept);
                              arrayPointLatLong.push([jTrackArray[i]._lat, jTrackArray[i]._long]);
                    }
                    var eskimon = L.polyline(arrayPointLatLong, {color: 'red'}).addTo(carte);
                    
                    
                    //function JS to display position:
                    function DisplayPositionInComment(pos) {
                        return "Date: " + pos._datept +"<br> Altitude: " + pos._alt +" m "+"<br> Battery: "+pos._bat+" %";      // The function returns the product of p1 and p2
}
             </script>

            <!-- Foot Page -->
            <?php include("footpage.php"); ?>
    </body>
</html>
	  

