<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Oso Website</title>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
                <link type="text/css" rel="stylesheet" media="screen and (max-width: 4000px)" href="/oso_web/assets/css/style.css" />
                <link type="text/css" rel="stylesheet" href="/oso_web/assets/css/bootstrap.min.css" />
                <!-- <link type="text/css" rel="stylesheet" media="screen and (max-width: 640px)" href="/oso_web/assets/css/stylePetit.css" /> -->
                
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
                
		<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
		<script src="/oso_web/assets/css/bootstrap.min.js"></script>
		<script src="/oso_web/assets/css/npm.js"></script>
    </head>
    <body>
	<?php include("class/classPosition.php"); ?>
	<!-- adresse test  : http://192.168.0.52/oso_web/?u=25fp2012 -->

		
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
                   
                    <!-- User id text field -->
                    <div class="row">
                        <div class="col-md-2 col-xs-2">
                            <input name="searchTxt" type="text" maxlength="512" id="searchTxt" class="form-control searchField" />
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <button id="ButtonFollowId" type="submit" class="btn btn-info" >Follow</button> 
                            <button onclick="FooterAppear()" class="btn btn-success" >Credits</button>
                        </div>
                    </div>
                    
                    
                    <img style="width:5%;position: fixed;float:right;bottom: 1%;right: 1px;" src="/oso_web/assets/img/logo.png">
                    
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
                            echo 'Track for: <b>' . htmlspecialchars($user) . "</b><br>";

                                    // On récupère tout le contenu de la table position pour un user
                                    $req = $bdd->prepare('SELECT * FROM position  WHERE user= :user ORDER BY `position`.`timestamp` DESC');
                                    $req->execute(array('user' => $user));
                                    //echo "<br>".'-----------'."<br>";

                                    // create track 
                                    $track = array();
                                    // On affiche chaque entrée une à une
                                            while ($donnees = $req->fetch())
                                            {
                                                array_push($track, new Position($donnees['id'], $donnees['user'], $donnees['datept'],$donnees['timestamp'], $donnees['latitude'], $donnees['longitude'], $donnees['altitude'], $donnees['battery']));
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

                    ?>

                     
            </div>
            
            
                     <div id="macarte" style="width:100%; height:100%; position:fixed;"> </div>
                                          
                     <div id="sidebar" class="sidebar hidden">
                         <button style="padding: 5px;border: 1px solid black;" onclick="FooterDisapear()" > X</button>
                         <p></p>
                         <?php include("footpage.php"); ?> 
                     </div>

            <!-- Map -->
            <script type="text/javascript">
                     // import array from Php
                     
                    var jTrackArray= <?php echo json_encode($track ); ?>;

                    var carte = L.map('macarte');
                    //Prepare map
                    if ( jTrackArray.length >= 3) {
                                 carte.setView([jTrackArray[0]._lat, jTrackArray[0]._long], 10);
                             } else {
                               carte.setView([45.6, 3.7], 5);  
                             }
                    

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
                        return "Date: " + timeConverter(pos._timestamp) +"<br> Altitude: " + pos._alt +" m "+"<br> Battery: "+pos._bat+" %";      // The function returns the product of p1 and p2
                    }
                    

                    function timeConverter(UNIX_timestamp){
               
                        console.log("UNIX_timestamp: "+UNIX_timestamp);
                        var date = new Date(Math.floor(UNIX_timestamp));

                             console.log("date: "+ dateToYMDHMS(date));
                      return dateToYMDHMS(date);
                    }

            function dateToYMDHMS(date) {
                var d = date.getDate();
                var m = date.getMonth() + 1;
                var y = date.getFullYear();
                var H = date.getHours()
                var M = date.getMinutes()
                var S = date.getSeconds()
                return '' + y + '/' + (m<=9 ? '0' + m : m) + '/' + (d <= 9 ? '0' + d : d+ '  '+ (H<=9 ? '0' + H : H)+':'+(M<=9 ? '0' + M : M)+':'+(S<=9 ? '0' + S : S));
            }


             </script>
             
             <script>
                 function FooterAppear() {
                     console.log("La slide bar se montre");
                    $("#sidebar").removeClass("hidden");    
                 }
                 
                 function FooterDisapear() {
                     console.log("La sidebar se cache");
                    $("#sidebar").addClass("hidden");   
                     
                 }
                 
             </script>

            <!-- Foot Page -->
            <!-- -->
    </body>
</html>
	  

